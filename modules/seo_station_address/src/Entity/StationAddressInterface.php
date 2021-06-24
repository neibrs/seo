<?php

namespace Drupal\seo_station_address\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Station address entities.
 *
 * @ingroup seo_station_address
 */
interface StationAddressInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Station address name.
   *
   * @return string
   *   Name of the Station address.
   */
  public function getName();

  /**
   * Sets the Station address name.
   *
   * @param string $name
   *   The Station address name.
   *
   * @return \Drupal\seo_station_address\Entity\StationAddressInterface
   *   The called Station address entity.
   */
  public function setName($name);

  /**
   * Gets the Station address creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Station address.
   */
  public function getCreatedTime();

  /**
   * Sets the Station address creation timestamp.
   *
   * @param int $timestamp
   *   The Station address creation timestamp.
   *
   * @return \Drupal\seo_station_address\Entity\StationAddressInterface
   *   The called Station address entity.
   */
  public function setCreatedTime($timestamp);

}
