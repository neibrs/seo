<?php

namespace Drupal\seo_station\Airui\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

class TagsCloudBlock extends BlockBase {

  public function build() {
    $build = [];
    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties([
      'type' => 'article',
    ]);
    /** @var \Drupal\taxonomy\TermInterface [] $tags */
    $tags = [];
    foreach ($nodes as $node) {
      $referenced = $node->field_tags->referencedEntities();
      if (empty($referenced)) {
        continue;
      }
      foreach ($referenced as $item) {
        $tags[$item->id()] = $item;
      }
    }

    $tags_links = [];
    foreach ($tags as $tag) {
      $tags_links[] = [
        'name' => $tag->label(),
        'link' => $tag->toUrl(),
      ];
    }

    $build['tags_link'] = $tags_links;
    return $build;
  }

  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(),
    \Drupal::entityTypeManager()->getDefinition('node')->getListCacheTags()
    );
  }

}
