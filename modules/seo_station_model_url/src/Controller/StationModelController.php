<?php

namespace Drupal\seo_station_model_url\Controller;

use Drupal\Core\Controller\ControllerBase;

class StationModelController extends ControllerBase {

  /**
   * 动态获取标题.
   */
  public function getTitle($seo_station_model, $seo_station_model_url_type) {
    $type = \Drupal::entityTypeManager()->getStorage('seo_station_model_url_type')->load($seo_station_model_url_type);
    return $type->label() . 'URL规则';
  }
}
