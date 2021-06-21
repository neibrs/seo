<?php

namespace Drupal\seo_station_model\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Station model entities.
 *
 * @ingroup seo_station_model
 */
interface StationModelInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Station model name.
   *
   * @return string
   *   Name of the Station model.
   */
  public function getName();

  /**
   * Sets the Station model name.
   *
   * @param string $name
   *   The Station model name.
   *
   * @return \Drupal\seo_station_model\Entity\StationModelInterface
   *   The called Station model entity.
   */
  public function setName($name);

  /**
   * Gets the Station model creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Station model.
   */
  public function getCreatedTime();

  /**
   * Sets the Station model creation timestamp.
   *
   * @param int $timestamp
   *   The Station model creation timestamp.
   *
   * @return \Drupal\seo_station_model\Entity\StationModelInterface
   *   The called Station model entity.
   */
  public function setCreatedTime($timestamp);

}
