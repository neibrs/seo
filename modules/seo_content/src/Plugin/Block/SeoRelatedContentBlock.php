<?php

namespace Drupal\seo_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "seo_content_related_content",
 *   admin_label = @Translation("Related any content"),
 * )
 */
class SeoRelatedContentBlock extends BlockBase {

  public function build() {
    $build = [];

    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $query = $node_storage->getQuery();
    $query->condition('type', 'article');
    $query->sort('created', 'DESC');
    $query->range(0, 20);
    $ids = $query->execute();
    $nodes = array_map(function ($node){
      return $node->toLink();
    }, $node_storage->loadMultiple($ids));

    $build['#content'] = $nodes;
    $build['#attached']['library'][] = 'seo_content/related';
    $build['#theme'] = 'seo_related_content';
    return $build;
  }

}
