<?php

namespace Drupal\seo_station_tkdb\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Tkdb entities.
 *
 * @ingroup seo_station_tkdb
 */
interface TkdbInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Tkdb name.
   *
   * @return string
   *   Name of the Tkdb.
   */
  public function getName();

  /**
   * Sets the Tkdb name.
   *
   * @param string $name
   *   The Tkdb name.
   *
   * @return \Drupal\seo_station_tkdb\Entity\TkdbInterface
   *   The called Tkdb entity.
   */
  public function setName($name);

  /**
   * Gets the Tkdb creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Tkdb.
   */
  public function getCreatedTime();

  /**
   * Sets the Tkdb creation timestamp.
   *
   * @param int $timestamp
   *   The Tkdb creation timestamp.
   *
   * @return \Drupal\seo_station_tkdb\Entity\TkdbInterface
   *   The called Tkdb entity.
   */
  public function setCreatedTime($timestamp);

}
