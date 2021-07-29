<?php

namespace Drupal\seo_station\Airui\Plugin\views\field;
use Drupal\views\Plugin\views\field\Standard;
use Drupal\views\ResultRow;

class DomainPrefix extends Standard {

  /**
   * {@inheritDoc}
   */
  public function getValue(ResultRow $values, $field = NULL) {
    return $values->_entity->prefix_multi->value == 1 ?  '自定义（推荐）' : '自动生成';
  }

}
