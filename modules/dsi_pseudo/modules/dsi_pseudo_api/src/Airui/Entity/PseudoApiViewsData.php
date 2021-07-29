<?php

namespace Drupal\dsi_pseudo_api\Airui\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Pseudo api entities.
 */
class PseudoApiViewsData extends EntityViewsData {

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
