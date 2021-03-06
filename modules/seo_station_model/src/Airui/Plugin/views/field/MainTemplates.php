<?php

namespace Drupal\seo_station_model\Airui\Plugin\views\field;

use Drupal\views\Plugin\views\field\Standard;
use Drupal\views\ResultRow;

class MainTemplates extends Standard {

  public function getValue(ResultRow $values, $field = NULL) {
    $data = parent::getValue($values, $field);

    if (!empty($values->_entity->secondary->value)) {
      if (empty($data)) {
        $data = $values->_entity->secondary->value;
      }
      else {
        $data = $data . ',' . $values->_entity->secondary->value;
      }
    }

    return $data;
  }

}
