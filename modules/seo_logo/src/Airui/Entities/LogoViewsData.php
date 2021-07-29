<?php

namespace Drupal\seo_logo\Airui\Entities;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Logo entities.
 */
class LogoViewsData extends EntityViewsData {

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
