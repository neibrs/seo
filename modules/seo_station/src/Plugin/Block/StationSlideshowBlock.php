<?php

namespace Drupal\seo_station\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'StationSlideshowBlock' block.
 *
 * @Block(
 *  id = "station_slideshow_block",
 *  admin_label = @Translation("动态幻灯片(6个)"),
 * )
 */
class StationSlideshowBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'items' => 6,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties([
      'type' => 'article',
    ]);

    $pend_nodes = array_rand($nodes, $this->configuration['items']);
    foreach ($pend_nodes as $node) {
      $file_uri = isset($nodes[$node]->field_image->target_id) ? $nodes[$node]->field_image->entity->createFileUrl() : '';
      $build['items'][] = [
        'id' => $nodes[$node]->id(),
        'name' => $nodes[$node]->label(),
        'link' => $nodes[$node]->toUrl(),
        'image' => $file_uri,
      ];
    }

    return $build;
  }

}
