<?php

namespace Drupal\seo_station_model\Airui\Entities;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Station model entities.
 */
class StationModelViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['seo_station_model']['main']['field']['id'] = 'main_templates';
    $data['seo_station_model']['main']['field']['real field'] = 'main';
    return $data;
  }

}
