<?php

namespace Drupal\seo_station\Plugin\QueueWorker;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\TermInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @QueueWorker(
 *   id = "link_rule_process",
 *   title = @Translation("Link Rule"),
 *   cron = {"time" = 60}
 * )
 */
class LinkRuleProcess extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /** @var \Drupal\Core\Entity\EntityTypeManagerInterface */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    $station = $this->entityTypeManager->getStorage('seo_station')->load($data['station']);
    // TODO， 暂时只做一个新闻类型的网站文章数据.
    $node_storage = $this->entityTypeManager->getStorage('node');

    $body = $this->getBody($station);
    if (empty($body)) {
      return;
    }
    // 找标题的原则是body的标题在title库里面找到了就用，没找到就使用自己的标题！如果自己的标题为空，则随机调用标题库里面的标题
    $title = $this->getTitle($body[0], $station);
    if (empty($title)) {
      return;
    }
    try {
      // 初始化node的值, 及Site name.
      list($site_name, $tkdb_values) = $this->getTkdbValues($data, $station);

      // 这里会创建N多，但站群网站却只要几个，矛盾点，待优化
      $taxonomies = $this->getTaxonomyValues($station);

      // 提取文章分类到标题后缀
      // 构造一个tid的数组.
      $rand_tids = [];
      if (!empty($taxonomies)) {
        $rand_tids = array_rand($taxonomies, 3);
      }
      if (!is_array($rand_tids)) {
        $rand_tids[] = $rand_tids;
      }
      $rs_rand_tid = reset($rand_tids);
      $term = $taxonomies[$rs_rand_tid];
      $tkdb_values = $this->appendTaxonomy2Title($term, $tkdb_values, $site_name);

      $values = [
        'type' => 'article',
        'title' => is_array($title) ? mb_substr($title[0], 0, 60) : mb_substr($title, 0, 60),
//        'field_image' => '',
        'body' => [
          'value' => $body[1],
          'summary' => mb_substr($body[1], 0, 100),
          'format' => 'basic_html',
        ],
        'path' => '/'.$data['replacement'],//Alias
//        'domain' => $data['domain'], //domain该字段未添加
        'field_metatag' => [
          'value' => serialize($tkdb_values),
        ],
        // TODO, Add taxonomy
        'field_tags' => $rand_tids, //$tids,
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

  // 随机获取title类型的标题库的文件一份
  protected function getTitle($body_title = NULL, $station = NULL) {
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
    \Drupal::messenger()->addWarning(t('随机标题: %title', ['%title' => $dst]));
    return explode('******', $dst);
  }

  protected function getFileUri($station, $type = 'article', $field = NULL) {
    $con = NULL;
    $textdata_storage = $this->entityTypeManager->getStorage('seo_textdata');
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

  protected function getBody($station) {
    $body = $this->getFileUri($station, 'article', 'site_node');

    if (empty($body)) {
      return [];
    }
    $uri = $body->get('attachment')->entity->getFileUri();

    // 随机模式
    $data = seo_textdata_auto_read($uri);
    $ds = array_unique(explode('-||-', str_replace("\r\n","-||-", $data)));
    $dst = $ds[mt_rand(0, count($ds))];
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
      $web_name = $this->getWebName($station);
      $web_name = trim(strip_tags($web_name));
      if (!empty($web_name)) {
        $field_metadata['title'] = '[node:title]-' . $web_name;
      }
    }
    try {
      $address_storage = $this->entityTypeManager->getStorage('seo_station_address');
      $address_values = [
        'name' => $data['domain'] . '/' . $data['replacement'],
        'station' => $data['station'],
        'domain' => $data['domain'],
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
    }

    return [$web_name, $field_metadata];
  }

  public function getWebName($station) {
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
    $uri = $web_name->get('attachment')->entity->getFileUri();
    $ds = getTextdataArrayFromUri($uri);
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
      if (strpos($rule_domain[0], '*') !== FALSE) {
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

  public function getTaxonomyValues($station) {
    if (empty($station->site_column->target_id)) {
      $textdata = $this->entityTypeManager->getStorage('seo_textdata')->loadByProperties([
        'type' => 'typename',
        'model' => $station->model->target_id,
      ]);
      $textdata = reset($textdata);
    }
    else {
      $textdata = $station->site_column->entity;
    }
    $typename_uri = $textdata->get('attachment')->entity->getFileUri();
    $ds = getTextdataArrayFromUri($typename_uri);
    $terms = [];
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');

    $transliteration =  \Drupal::service('transliteration');

    foreach ($ds as $name) {
      if (strlen($name) > 100) {
        \Drupal::messenger()->addError('栏目名称太长.');
        return [];
      }
      $query = $storage->getQuery();
      $query->condition('name', $name);
      $query->condition('vid', 'typename');
      $ids = $query->execute();
      $taxonomy = NULL;
      if (empty($ids)) {
        $taxonomy = Term::create([
          'name' => $name,
          'vid' => 'typename',
          'path' => '/' . $this->transliterate($name, $transliteration),
          // TODO, 添加station来标识?
        ]);
        $taxonomy->save();
      }
      else {
        $taxonomy = $storage->load(reset($ids));
      }
      $terms[] = $taxonomy;
    }

    return $terms;
  }

  protected function transliterate($name, $transliteration) {
    /** @var \Drupal\Component\Transliteration\TransliterationInterface $transliteration */
    return $transliteration->transliterate($name, 'zh-hans');
  }

  protected function appendTaxonomy2Title($term, $tkdb_values, $site_name) {
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
}
