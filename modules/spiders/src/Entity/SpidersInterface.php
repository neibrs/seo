<?php

namespace Drupal\spiders\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Spiders entities.
 *
 * @ingroup spiders
 */
interface SpidersInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Spiders name.
   *
   * @return string
   *   Name of the Spiders.
   */
  public function getName();

  /**
   * Sets the Spiders name.
   *
   * @param string $name
   *   The Spiders name.
   *
   * @return \Drupal\spiders\Entity\SpidersInterface
   *   The called Spiders entity.
   */
  public function setName($name);

  /**
   * Gets the Spiders creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Spiders.
   */
  public function getCreatedTime();

  /**
   * Sets the Spiders creation timestamp.
   *
   * @param int $timestamp
   *   The Spiders creation timestamp.
   *
   * @return \Drupal\spiders\Entity\SpidersInterface
   *   The called Spiders entity.
   */
  public function setCreatedTime($timestamp);

}
