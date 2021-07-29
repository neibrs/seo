<?php

namespace Drupal\seo_station_model_url\Airui\Entities;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\seo_station_model_url\Entity\StationModelUrlTypeInterface;

class StationModelUrlType extends ConfigEntityBase implements StationModelUrlTypeInterface {

  /**
   * The 模板URL规则类型 ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The 模板URL规则类型 label.
   *
   * @var string
   */
  protected $label;

}
