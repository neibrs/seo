<?php

namespace Drupal\seo_station\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Auto prefix entities.
 *
 * @ingroup seo_station
 */
interface AutoPrefixInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Auto prefix name.
   *
   * @return string
   *   Name of the Auto prefix.
   */
  public function getName();

  /**
   * Sets the Auto prefix name.
   *
   * @param string $name
   *   The Auto prefix name.
   *
   * @return \Drupal\seo_station\Entity\AutoPrefixInterface
   *   The called Auto prefix entity.
   */
  public function setName($name);

  /**
   * Gets the Auto prefix creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Auto prefix.
   */
  public function getCreatedTime();

  /**
   * Sets the Auto prefix creation timestamp.
   *
   * @param int $timestamp
   *   The Auto prefix creation timestamp.
   *
   * @return \Drupal\seo_station\Entity\AutoPrefixInterface
   *   The called Auto prefix entity.
   */
  public function setCreatedTime($timestamp);

}
