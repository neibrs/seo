<?php

namespace Drupal\seo_negotiator\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Negotiator entities.
 */
class NegotiatorViewsData extends EntityViewsData {

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
