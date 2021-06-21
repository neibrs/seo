<?php

namespace Drupal\dsi_pseudo_api\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Pseudo api entities.
 *
 * @ingroup dsi_pseudo_api
 */
interface PseudoApiInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Pseudo api name.
   *
   * @return string
   *   Name of the Pseudo api.
   */
  public function getName();

  /**
   * Sets the Pseudo api name.
   *
   * @param string $name
   *   The Pseudo api name.
   *
   * @return \Drupal\dsi_pseudo_api\Entity\PseudoApiInterface
   *   The called Pseudo api entity.
   */
  public function setName($name);

  /**
   * Gets the Pseudo api creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Pseudo api.
   */
  public function getCreatedTime();

  /**
   * Sets the Pseudo api creation timestamp.
   *
   * @param int $timestamp
   *   The Pseudo api creation timestamp.
   *
   * @return \Drupal\dsi_pseudo_api\Entity\PseudoApiInterface
   *   The called Pseudo api entity.
   */
  public function setCreatedTime($timestamp);

}
