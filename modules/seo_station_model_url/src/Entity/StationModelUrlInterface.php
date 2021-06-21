<?php

namespace Drupal\seo_station_model_url\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Station model url entities.
 *
 * @ingroup seo_station_model_url
 */
interface StationModelUrlInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Station model url name.
   *
   * @return string
   *   Name of the Station model url.
   */
  public function getName();

  /**
   * Sets the Station model url name.
   *
   * @param string $name
   *   The Station model url name.
   *
   * @return \Drupal\seo_station_model_url\Entity\StationModelUrlInterface
   *   The called Station model url entity.
   */
  public function setName($name);

  /**
   * Gets the Station model url creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Station model url.
   */
  public function getCreatedTime();

  /**
   * Sets the Station model url creation timestamp.
   *
   * @param int $timestamp
   *   The Station model url creation timestamp.
   *
   * @return \Drupal\seo_station_model_url\Entity\StationModelUrlInterface
   *   The called Station model url entity.
   */
  public function setCreatedTime($timestamp);

}
