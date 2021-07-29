<?php

namespace Drupal\seo_station_tkdb\Airui\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\seo_station_tkdb\Entity\TkdbTypeInterface;

class TkdbType extends ConfigEntityBundleBase implements TkdbTypeInterface {

  /**
   * The Tkdb type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Tkdb type label.
   *
   * @var string
   */
  protected $label;

}
