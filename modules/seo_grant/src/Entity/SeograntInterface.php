<?php

namespace Drupal\seo_grant\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Seogrant entities.
 *
 * @ingroup seo_grant
 */
interface SeograntInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Seogrant name.
   *
   * @return string
   *   Name of the Seogrant.
   */
  public function getName();

  /**
   * Sets the Seogrant name.
   *
   * @param string $name
   *   The Seogrant name.
   *
   * @return \Drupal\seo_grant\Entity\SeograntInterface
   *   The called Seogrant entity.
   */
  public function setName($name);

  /**
   * Gets the Seogrant creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Seogrant.
   */
  public function getCreatedTime();

  /**
   * Sets the Seogrant creation timestamp.
   *
   * @param int $timestamp
   *   The Seogrant creation timestamp.
   *
   * @return \Drupal\seo_grant\Entity\SeograntInterface
   *   The called Seogrant entity.
   */
  public function setCreatedTime($timestamp);

}
