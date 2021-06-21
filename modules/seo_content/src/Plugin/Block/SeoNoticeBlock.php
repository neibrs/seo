<?php
namespace Drupal\seo_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * 提供站点公告区块
 *
 * @Block(
 *   id = "seo_content_notice_block",
 *   admin_label = @Translation("站点公告"),
 * )
 */
class SeoNoticeBlock extends BlockBase {

  public function build() {
    $build = [];
    $term_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');

    $query = $term_storage->getQuery();
    $data = [
      '公告',
      '提示',
      '郑重声明',
      '违规处理',
    ];
    $query->condition('name', $data, 'IN');

    $ids = $query->execute();

    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $node_query = $node_storage->getQuery();
    $node_query->condition('field_tags', $ids, 'IN');
    $nids = $node_query->execute();

    $nodes = $node_storage->loadMultiple($nids);

    $nodes = array_map(function ($node) {
      return [
        'tag' => $node->field_tags->entity->label(),
        'title' => $node->toLink(),
      ];
    }, $nodes);

    $build['#content'] = $nodes;
    $build['#attached']['library'][] = 'seo_content/block';
    $build['#theme'] = 'seo_content_notice';
    return $build;
  }

}
