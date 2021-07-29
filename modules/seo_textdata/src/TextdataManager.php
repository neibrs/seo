<?php

namespace Drupal\seo_textdata;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\file\Entity\File;
use Drupal\taxonomy\TermInterface;

class TextdataManager implements TextdataManagerInterface {

  /** @var  */
  protected $entityTypeManager;
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * @param $theme_info
   *
   * @return mixed|string
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getWebnameByStationAddress($theme_info) {
    $sitename = '';
    // 网站名称
    $textdata_storage = \Drupal::entityTypeManager()->getStorage('seo_textdata');
    $textdata_query = $textdata_storage->getQuery();
    $textdata_query->condition('type', 'webname');
    $textdata_query->condition('model.entity.config_dir', $theme_info['seo_theme']);
    $ids = $textdata_query->execute();
    if (empty($ids)) {
      return '';
    }
    $textdata = $textdata_storage->load($ids[array_rand($ids, 1)]);

    if (!empty($textdata->attachment->target_id)) {
      $webnames = getTextdataArrayFromUri($textdata->attachment->entity->getFileUri());
      $sitename = $webnames[array_rand($webnames, 1)];
    }

    return $sitename;
  }

  // 随机获取title类型的标题库的文件一份
  public function getTitle($body_title = NULL, $station = NULL) {
    $title = $this->getFileUri($station, 'title', 'site_title');

    if (empty($title)) {
      return [];
    }

    $uri = $title->get('attachment')->entity->getFileUri();

    // 随机模式
    $ds = getTextdataArrayFromUri($uri);
    if (in_array($body_title, $ds)) {
      return $body_title;
    }
    if (!empty($body_title)) {
      return $body_title;
    }
    $dst = $ds[mt_rand(0, count($ds))];
    //    \Drupal::messenger()->addWarning(t('随机标题: %title', ['%title' => $dst]));
    return explode('******', $dst);
  }

  public function getFileUri($station, $type = 'article', $field = NULL) {
    $con = NULL;
    $textdata_storage = \Drupal::entityTypeManager()->getStorage('seo_textdata');
    // Use locale content library.
    if (!$station->get('use_official')->value) {
      if (empty($station->{$field}->target_id)) {
        $query = $textdata_storage->getQuery();
        $query->condition('type', $type);

        //限定相同分组下的内容
        $query->condition('model', $station->model->target_id);

        // The industry filter.
        // 找对应行业的内容数据TODO
        $tags = $station->get('tags')->referencedEntities();
        $tags = array_map(function ($tag) {
          return $tag->id();
        }, $tags);
        if (!empty($tags)) {
          // TODO, check validate.
          $query->condition('tags.entity.id', $tags, 'IN');
        }
        $ids = $query->execute();
        $con = $textdata_storage->loadMultiple($ids);

        // 任意取一个textdata
        $con = reset($con);
      }
      else {
        // 取本身设置的textdata
        $con = $station->{$field}->entity;
      }
    }
    else {
      // TODO, use official content.从官网远程获取功能，待处理
    }

    return $con;
  }

  // ================================

  public function getBody($station, $default_title = NULL) {
    $body = $this->getFileUri($station, 'article', 'site_node');

    if (empty($body)) {
      return [];
    }
    $uri = $body->get('attachment')->entity->getFileUri();

    // 随机模式
    $data = seo_textdata_auto_read($uri);
    $ds = array_unique(explode('-||-', str_replace("\r\n","-||-", $data)));

    $index = 0;
    foreach ($ds as $i => $d) {
      if (strpos($d, $default_title . '******') !== FALSE) {
        $index = $i;
        break;
      }
    }
    $dst = '';
    if ($index) {
      $dst = $ds[$index];
    }
    else {
      $dst = $ds[mt_rand(0, count($ds))];
    }
    return explode('******', $dst);
  }

  public function getTkdbValues($data, $station): array {
    // 这里添加tkdb规则,用以对每个node进行定义。
    // 设置title, keywords, description, content. metatag在此处理.
    $config = \Drupal::configFactory()->getEditable('seo_station.custom_domain_tkd');
    $tkdb_config = $config->get('custom_domain_tkd');
    // 先解析
    $rules = array_unique(explode('-||-', str_replace("\r\n","-||-", $tkdb_config)));
    $field_metadata = [
      'canonical' => '[node:url]',
    ];
    $web_name = '';
    foreach ($rules as $rule) {
      if (empty($rule)) {
        continue;
      }
      // rule: 域名----网站名称----首页标题----关键词----描述
      $rule_domain = explode('----', $rule); //Tkd全局设置的需要覆写的域名
      $rule_url = parse_url($data['domain']); //数据的域名
      $status = $this->getWildRule($rule_url, $rule_domain);

      if (!$status) {
        // Append domain site name into settings.
        continue;
      }
      if (empty($web_name)) {
        $web_name = $rule_domain[1];
      }
      // 站点标题.
      if (isset($rule_domain[1])) {
        $field_metadata['title'] = '[node:title]-' . $web_name;
      }
      if (isset($rule_domain[2])) {
        $field_metadata['abstract'] = $rule_domain[2];
      }
      if (isset($rule_domain[3])) {
        $field_metadata['keywords'] = $rule_domain[3];
      }
      if (isset($rule_domain[4])) {
        $field_metadata['description'] = $rule_domain[4];
      }
      break;
    }
    if (empty($web_name)) {
      // TODO, 自动追加网站名称到站点设置里面
      // eg. 成都宏义动力科技有限公司, TODO, 去除地域名，行业名(科技有限公司)
      $web_name = $this->getWebNameByTextdata($station);
      $web_name = trim(strip_tags($web_name));
      if (!empty($web_name)) {
        $field_metadata['title'] = '[node:title]-' . $web_name;
      }
    }
    try {
      $address_storage = $this->entityTypeManager->getStorage('seo_station_address');
      $address_values = [
        'name' => $data['domain'] . '/' . $data['replacement'],
        'station' => $data['station']->id(),
        'domain' => $data['domain'],
        'replacement' => $data['replacement'],
      ];
      $addresses = $address_storage->loadByProperties($address_values);
      $address_values['webname'] = $web_name;
      if (!empty($addresses)) {
        $address = reset($addresses);
        foreach ($address_values as $key => $value) {
          $address->set($key, $value);
        }
        $address->save();
      }
      else {
        $address_storage->create($address_values)->save();
      }
    } catch (InvalidPluginDefinitionException | PluginNotFoundException | EntityStorageException $e) {
      \Drupal::messenger()->addError($e->getMessage());
    }

    return [$web_name, $field_metadata];
  }

  public function getWebNameByTextdata($station) {
    $ds = [];
    if ($station->site_name->target_id) {
      $web_name = $station->site_name->entity;
    }
    else {
      // Get an arbitrarily webname file.
      $web_names = $this->entityTypeManager->getStorage('seo_textdata')->loadByProperties([
        'type' => 'webname',
        'model' => $station->model->target_id,
      ]);

      $web_name = reset($web_names);
    }
    if (empty($web_name)) {
      \Drupal::messenger()->addError('缺乏网站名称内容数据。');
    }
    else {
      $uri = $web_name->get('attachment')->entity->getFileUri();
      $ds = getTextdataArrayFromUri($uri);
    }

    // TODO, bug
    return $ds[mt_rand(0, 1)];
  }

  /**
   * @param $rule_url //全局设置的需要覆写的域名
   * @param $rule_domain //数据的域名
   *
   * @return bool
   */
  public function getWildRule($rule_url, $rule_domain) : bool {
    if ($rule_url['path'] != $rule_domain[0]) {
      // 泛域名匹配
      if (strpos($rule_domain[0], '*.') !== FALSE) {
        $wild_string = substr($rule_domain[0], 2);
        $pos = strpos($rule_url['path'], $wild_string);
        if (!$pos) {
          // 不是当前泛域名
          return FALSE;
        }
        // 找到了当前的泛域名
        return TRUE;
      }
      else {
        // 不是泛域名
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getTaxonomyValues($station): array {
    $textdata = '';
    if (empty($station->site_column->target_id)) {
      $textdata = $this->entityTypeManager->getStorage('seo_textdata')->loadByProperties([
        'type' => 'typename',
        'model' => $station->model->target_id,
      ]);
      $rand_id = array_rand($textdata);
      $textdata = $textdata[$rand_id];
    }
    else {
      $textdata = $station->site_column->entity;
    }

    return getAllTaxonomyByTextdata($textdata);
  }

  public function transliterate($name, $transliteration): string {
    /** @var \Drupal\Component\Transliteration\TransliterationInterface $transliteration */
    return $transliteration->transliterate($name, 'zh-hans');
  }

  public function appendTaxonomy2Title($term, $tkdb_values, $site_name) {
    // 提取文章分类到标题后缀
    if ($term instanceof TermInterface) {
      $tkdb_values['title'] . $term->label();
    }

    // if not set tkdb title
    if (empty($tkdb_values['title'])) {
      if (empty($site_name)) {
        $tkdb_values['title'] = '[node:title]';
      }
      else {
        $tkdb_values['title'] = '[node:title]-' . $site_name;
      }
    }

    return $tkdb_values;
  }

  public function generateNodeFields(&$entity) {
    // 生成内容
    // 生成标签
    // 生成图片
    $text_body = $this->getBody($entity->field_station->entity, $entity->label());

    //  // 分类
    $taxonomies = $this->getTaxonomyValues($entity->field_station->entity);
    // 初始化node的值, 及Site name.
//    [$site_name, $tkdb_values] = $this->getTkdbValues($data, $entity->field_station->entity);
    // 提取文章分类到标题后缀
    // 构造一个tid的数组.
    $rand_tids = [];
    if (!empty($taxonomies)) {
      $rand_tids = array_rand($taxonomies, 5);
    }
    if (!is_array($rand_tids)) {
      $rand_tids[] = $rand_tids;
    }
    $rs_rand_tid = reset($rand_tids);
    $term = $taxonomies[$rs_rand_tid];
//    $tkdb_values = $this->appendTaxonomy2Title($term, $tkdb_values, $site_name);

    $values = [
      'body' => [
        'value' => $text_body[1],
        'summary' => mb_substr($text_body[1], 0, 100),
        'format' => 'basic_html',
      ],
      'field_tags' => $rand_tids,
    ];

    foreach ($values as $key => $val) {
      $entity->set($key, $val);
    }

    return $entity;
  }


  public function generateNode($data) {
    $station = $data['station'];
    // TODO， 暂时只做一个新闻类型的网站文章数据.
    $node_storage = $this->entityTypeManager->getStorage('node');

    $body = $this->getBody($station);
    if (empty($body)) {
      return;
    }
    // 找标题的原则是body的标题在title库里面找到了就用，没找到就使用自己的标题！如果自己的标题为空，则随机调用标题库里面的标题
    //    $title = $this->getTitle($body[0], $station);
    $title = \Drupal::service('seo_textdata.manager')->getTitle($body[0], $station);
    if (empty($title)) {
      return;
    }
    try {
      // 初始化node的值, 及Site name.
      [$site_name, $tkdb_values] = $this->getTkdbValues($data, $station);

      // 这里会创建N多，但站群网站却只要几个，矛盾点，待优化
      $taxonomies = $this->getTaxonomyValues($station);

      // 提取文章分类到标题后缀
      // 构造一个tid的数组.
      $rand_tids = [];
      if (!empty($taxonomies)) {
        $rand_tids = array_rand($taxonomies, 5);
      }
      if (!is_array($rand_tids)) {
        $rand_tids[] = $rand_tids;
      }
      $rs_rand_tid = reset($rand_tids);
      $term = $taxonomies[$rs_rand_tid];
      $tkdb_values = $this->appendTaxonomy2Title($term, $tkdb_values, $site_name);

      // TODO, refactor it and delete this.
      $file_image = File::create([
        'filename' => 'node-default.jpg',
        'uri' => 'public://node-default.jpg',
        'filemime' => 'image/jpeg',
        'status' => FILE_STATUS_PERMANENT,
      ]);
      $file_image->save();
      $title = is_array($title) ? mb_substr($title[0], 0, 60) : mb_substr($title, 0, 60);
      $values = [
        'type' => 'article',
        'title' => $title,
        'field_image' => [
          'target_id' => $file_image->id(),
          'alt' => $title,
          'title' => $title,
        ],
        'body' => [
          'value' => $body[1],
          'summary' => mb_substr($body[1], 0, 100),
          'format' => 'basic_html',
        ],
        'path' => '/'.$data['replacement'],//Alias
        'field_metatag' => [
          'value' => serialize($tkdb_values),
        ],
        //TODO, Add taxonomy
        'field_tags' => $rand_tids, //$tids,
        'field_station' => $station->id(),
      ];
      // 创建该别名的文章数据.
      $node = $node_storage->create(['type' => 'article']);
      foreach ($values as $key => $val) {
        $node->set($key, $val);
      }
      $node->save();
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addWarning($e);
    }
  }
}
