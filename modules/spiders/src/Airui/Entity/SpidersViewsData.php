<?php

namespace Drupal\spiders\Airui\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Spiders entities.
 */
class SpidersViewsData extends EntityViewsData {

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
