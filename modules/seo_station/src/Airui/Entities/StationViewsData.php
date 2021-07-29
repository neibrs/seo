<?php

namespace Drupal\seo_station\Airui\Entities;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Station entities.
 */
class StationViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    $data['seo_station']['domain_number'] = [
      'title' => '域名个数',
      'help' => '统计域名字段的个数',
      'field' => [
        'id' => 'domain_number',
        'real field' => 'domain__value',
      ],
    ];
    $data['seo_station']['prefix_multi']['field']['id'] = 'domain_prefix';
    return $data;
  }

}
