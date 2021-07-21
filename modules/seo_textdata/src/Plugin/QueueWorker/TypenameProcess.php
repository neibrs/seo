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
 *   id = "typename_process",
 *   title = @Translation("Link Rule"),
 *   cron = {"time" = 60}
 * )
 */
class TypenameProcess extends QueueWorkerBase implements ContainerFactoryPluginInterface {

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
        $rand_tids = array_rand($taxonomies, 5);
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

}
