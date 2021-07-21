<?php

namespace Drupal\spiders\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Spiders group entities.
 *
 * @ingroup spiders
 */
interface SpidersGroupInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Spiders group name.
   *
   * @return string
   *   Name of the Spiders group.
   */
  public function getName();

  /**
   * Sets the Spiders group name.
   *
   * @param string $name
   *   The Spiders group name.
   *
   * @return \Drupal\spiders\Entity\SpidersGroupInterface
   *   The called Spiders group entity.
   */
  public function setName($name);

  /**
   * Gets the Spiders group creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Spiders group.
   */
  public function getCreatedTime();

  /**
   * Sets the Spiders group creation timestamp.
   *
   * @param int $timestamp
   *   The Spiders group creation timestamp.
   *
   * @return \Drupal\spiders\Entity\SpidersGroupInterface
   *   The called Spiders group entity.
   */
  public function setCreatedTime($timestamp);

}
