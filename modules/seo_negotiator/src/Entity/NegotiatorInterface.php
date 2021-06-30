<?php

namespace Drupal\seo_negotiator\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Negotiator entities.
 *
 * @ingroup seo_negotiator
 */
interface NegotiatorInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Negotiator name.
   *
   * @return string
   *   Name of the Negotiator.
   */
  public function getName();

  /**
   * Sets the Negotiator name.
   *
   * @param string $name
   *   The Negotiator name.
   *
   * @return \Drupal\seo_negotiator\Entity\NegotiatorInterface
   *   The called Negotiator entity.
   */
  public function setName($name);

  /**
   * Gets the Negotiator creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Negotiator.
   */
  public function getCreatedTime();

  /**
   * Sets the Negotiator creation timestamp.
   *
   * @param int $timestamp
   *   The Negotiator creation timestamp.
   *
   * @return \Drupal\seo_negotiator\Entity\NegotiatorInterface
   *   The called Negotiator entity.
   */
  public function setCreatedTime($timestamp);

}
