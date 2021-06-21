<?php

namespace Drupal\seo_station_tkdb\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Tkdb entities.
 */
class TkdbViewsData extends EntityViewsData {

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
