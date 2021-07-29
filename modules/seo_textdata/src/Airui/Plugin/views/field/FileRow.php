<?php

namespace Drupal\seo_textdata\Airui\Plugin\views\field;

use Drupal\views\Plugin\views\field\Standard;
use Drupal\views\ResultRow;

class FileRow extends Standard {

  /**
   * {@inheritDoc}
   */
  public function getValue(ResultRow $values, $field = NULL) {
    $uri = $values->_entity->attachment->entity->getFileUri();
    $txt = seo_textdata_auto_read($uri);
    $rows = explode('|;', str_replace("\r\n","|;", $txt));

    return count($rows);
  }

}
