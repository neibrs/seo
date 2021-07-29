<?php

namespace Drupal\seo_textdata\Airui\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Text data entities.
 */
class TextDataViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    $data['seo_textdata_field_data']['number']['field']['id'] = 'file_row';
    $data['seo_textdata_field_data']['size']['field']['id'] = 'file_size';
    return $data;
  }

}
