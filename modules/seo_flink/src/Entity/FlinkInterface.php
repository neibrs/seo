<?php

namespace Drupal\seo_flink\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Flink entities.
 *
 * @ingroup seo_flink
 */
interface FlinkInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Flink name.
   *
   * @return string
   *   Name of the Flink.
   */
  public function getName();

  /**
   * Sets the Flink name.
   *
   * @param string $name
   *   The Flink name.
   *
   * @return \Drupal\seo_flink\Entity\FlinkInterface
   *   The called Flink entity.
   */
  public function setName($name);

  /**
   * Gets the Flink creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Flink.
   */
  public function getCreatedTime();

  /**
   * Sets the Flink creation timestamp.
   *
   * @param int $timestamp
   *   The Flink creation timestamp.
   *
   * @return \Drupal\seo_flink\Entity\FlinkInterface
   *   The called Flink entity.
   */
  public function setCreatedTime($timestamp);

}
