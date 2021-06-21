<?php

namespace Drupal\seo_station\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Station entities.
 *
 * @ingroup seo_station
 */
interface StationInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Station name.
   *
   * @return string
   *   Name of the Station.
   */
  public function getName();

  /**
   * Sets the Station name.
   *
   * @param string $name
   *   The Station name.
   *
   * @return \Drupal\seo_station\Entity\StationInterface
   *   The called Station entity.
   */
  public function setName($name);

  /**
   * Gets the Station creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Station.
   */
  public function getCreatedTime();

  /**
   * Sets the Station creation timestamp.
   *
   * @param int $timestamp
   *   The Station creation timestamp.
   *
   * @return \Drupal\seo_station\Entity\StationInterface
   *   The called Station entity.
   */
  public function setCreatedTime($timestamp);

}
