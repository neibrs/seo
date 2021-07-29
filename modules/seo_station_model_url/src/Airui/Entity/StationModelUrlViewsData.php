<?php

namespace Drupal\seo_station_model_url\Airui\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Station model url entities.
 */
class StationModelUrlViewsData extends EntityViewsData {

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
