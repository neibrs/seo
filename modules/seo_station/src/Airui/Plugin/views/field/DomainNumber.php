<?php

namespace Drupal\seo_station\Airui\Plugin\views\field;
use Drupal\views\Plugin\views\field\Standard;
use Drupal\views\ResultRow;

class DomainNumber extends Standard {

  /**
   * {@inheritDoc}
   */
  public function getValue(ResultRow $values, $field = NULL) {
    $domain_value = $values->_entity->domain->value;
    $olds = array_unique(explode(',', str_replace("\r\n",",", $domain_value)));
    $domains = array_filter($olds, function ($ol) use ($olds) {
      if ($ol !== '0' && !empty($ol)) {
        return $ol;
      }
    });
    return count($domains);
  }

}
