<?php

namespace Drupal\seo_station_address\Airui\Entities;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Station address entities.
 */
class StationAddressViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
