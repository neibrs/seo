<?php

namespace Drupal\seo_station\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\taxonomy\Entity\Term;
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
    // TODO， 暂时只做一个新闻类型的网站文章数据.
    $node_storage = $this->entityTypeManager->getStorage('node');

    $body = $this->getBody($data);
    if (empty($body)) {
      return;
    }
    $title = $this->getTitle($body[0], $data);
    if (empty($title)) {
      return;
    }
    try {
      // 初始化node的值
      $tkdb_values = $this->getTkdbValues($data);
      $taxonomies = $this->getTaxonomyValues($data);

      // 构造一个tid的数组.
      $rand_tids = [];
      if (!empty($taxonomies)) {
        $rand_tids = array_rand($taxonomies, 2);
      }
      if (!is_array($rand_tids)) {
        $rand_tids[] = $rand_tids;
      }
      $tids = [];
      foreach ($rand_tids as $rand_tid) {
        $tids[$rand_tid] = Term::load($rand_tid);
//        [
//          'target_id' => Term::load($rand_tid),
//        ];
      }
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
        'field_tags' => $tids,
      ];
      // 创建该别名的文章数据.
      $node = $node_storage->create(['type' => 'article']);
      foreach ($values as $key => $val) {
        $node->set($key, $val);
      }
      $node->save();
      $x = 'a';
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addWarning($e);
    }

  }

  // 随机获取title类型的标题库的文件一份
  protected function getTitle($body_title = NULL, $data = []) {
    $station = $this->entityTypeManager->getStorage('seo_station')->load($data['station']);
    $title = NULL;
    if (empty($station->site_title->target_id)) {
      $storage = $this->entityTypeManager->getStorage('seo_textdata');
      $title = $storage->loadByProperties([
        'type' => 'title',
      ]);
      $title = reset($title);
    }
    else {
      $title = $station->site_title->entity;
    }
    if (empty($title)) {
      return [];
    }

    $uri = $title->get('attachment')->entity->getFileUri();

    // 随机模式
    $ds = getTextdataArrayFromUri($uri);
    if (in_array($body_title, $ds)) {
      return $body_title;
    }
    $dst = $ds[mt_rand(0, count($ds))];
    \Drupal::messenger()->addWarning(t('随机标题: %title', ['%title' => $dst]));
    return explode('******', $dst);
  }

  protected function getBody($data) {
    $station = $this->entityTypeManager->getStorage('seo_station')->load($data['station']);
    $body = NULL;
    if (empty($station->site_node->target_id)) {
      $storage = $this->entityTypeManager->getStorage('seo_textdata');
      $body = $storage->loadByProperties([
        'type' => 'article',
      ]);
      $body = reset($body);
    }
    else {
      $body = $station->site_node->entity;
    }

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

  public function getTkdbValues($data) {
    // 这里添加tkdb规则,用以对每个node进行定义。
    $tkdb_manager = \Drupal::service('seo_station_tkdb.manager');
    $tkdb_rules = $tkdb_manager->getTkdbShowRule($data);
    // 根据规则，寻找可替换的TKDB. 这里寻找的是show规则.

    // 设置title, keywords, description, content. metatag在此处理.
    $tkdb_config = \Drupal::config('seo_station.custom_domain_tkd')->get('custom_domain_tkd');
    // 先解析
    $rules = array_unique(explode('-||-', str_replace("\r\n","-||-", $tkdb_config)));
    $field_metatag = [
      'canonical' => '[node:url]',
    ];
    foreach ($rules as $rule) {
      // rule: 域名----网站名称----首页标题----关键词----描述
      $rule_domain = explode('----', $rule);
      $rule_url = parse_url($data['domain']);
      $status = $this->getWildRule($rule, $rule_url, $rule_domain);
      if (!$status) {
        continue;
      }
      // 站点标题.
      if (isset($rule_domain[1])) {
        $field_metatag['title'] = '[node:title]-' . $rule_domain[1];
      }
      if (isset($rule_domain[2])) {
        $field_metatag['abstract'] = $rule_domain[2];
      }
      if (isset($rule_domain[3])) {
        $field_metatag['keywords'] = $rule_domain[3];
      }
      if (isset($rule_domain[4])) {
        $field_metatag['description'] = $rule_domain[4];
      }
      break;
    }

//    foreach ($tkdb_rules as $tkdb_rule) {
//      switch ($tkdb_rule->type->entity->id()) {
//        case 'title':
//        case 'keywords':
//        case 'description':
//        case 'content':
//          $rule = strip_tags($tkdb_rule->content->value);
//
//          break;
//      }
//    }
    // TODO

    return $field_metatag;
  }

  public function getWildRule($rule, $rule_url, $rule_domain) {
    if ($rule_url['path'] != $rule_domain[0]) {
      // 泛域名匹配
      if (strpos($rule_domain[0], '*') === 0) {
        $wild_string = substr($rule_domain[0], 2);
        $pos = strpos($rule, $wild_string);
        if (!$pos) {
          return FALSE;
        }
        // 找到了主要的泛域名
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    return TRUE;
  }

  public function getTaxonomyValues($data) {
    $station = $this->entityTypeManager->getStorage('seo_station')->load($data['station']);
    $textdata = NULL;
    if (empty($station->site_column->target_id)) {
      $textdata = $this->entityTypeManager->getStorage('seo_textdata')->loadByProperties([
        'type' => 'typename'
      ]);
      $textdata = reset($textdata);
    }
    else {
      $textdata = $station->site_column->entity;
    }
    $typename_uri = $textdata->get('attachment')->entity->getFileUri();
    $ds = getTextdataArrayFromUri($typename_uri);
    $tids = [];
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');
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
          // TODO, 添加station来标识?
        ]);
      }
      else {
        $taxonomy = $storage->load(reset($ids));
      }
      $taxonomy->save();
      $tids[] = $taxonomy->id();
    }

    return $tids;
  }
}
