<?php

namespace Drupal\spiders\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Spiders group entities.
 */
class SpidersGroupViewsData extends EntityViewsData {

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