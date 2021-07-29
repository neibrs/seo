<?php

namespace Drupal\seo_textdata\Airui\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\seo_textdata\Entity\TextDataTypeInterface;

class TextDataType extends ConfigEntityBundleBase implements TextDataTypeInterface {

  /**
   * The Text data type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Text data type label.
   *
   * @var string
   */
  protected $label;

}
