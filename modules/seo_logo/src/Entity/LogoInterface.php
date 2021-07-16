<?php

namespace Drupal\seo_logo\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Logo entities.
 *
 * @ingroup seo_logo
 */
interface LogoInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Logo name.
   *
   * @return string
   *   Name of the Logo.
   */
  public function getName();

  /**
   * Sets the Logo name.
   *
   * @param string $name
   *   The Logo name.
   *
   * @return \Drupal\seo_logo\Entity\LogoInterface
   *   The called Logo entity.
   */
  public function setName($name);

  /**
   * Gets the Logo creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Logo.
   */
  public function getCreatedTime();

  /**
   * Sets the Logo creation timestamp.
   *
   * @param int $timestamp
   *   The Logo creation timestamp.
   *
   * @return \Drupal\seo_logo\Entity\LogoInterface
   *   The called Logo entity.
   */
  public function setCreatedTime($timestamp);

}
