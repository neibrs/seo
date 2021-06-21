<?php

namespace Drupal\seo_textdata\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Text data entities.
 *
 * @ingroup seo_textdata
 */
interface TextDataInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Text data name.
   *
   * @return string
   *   Name of the Text data.
   */
  public function getName();

  /**
   * Sets the Text data name.
   *
   * @param string $name
   *   The Text data name.
   *
   * @return \Drupal\seo_textdata\Entity\TextDataInterface
   *   The called Text data entity.
   */
  public function setName($name);

  /**
   * Gets the Text data creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Text data.
   */
  public function getCreatedTime();

  /**
   * Sets the Text data creation timestamp.
   *
   * @param int $timestamp
   *   The Text data creation timestamp.
   *
   * @return \Drupal\seo_textdata\Entity\TextDataInterface
   *   The called Text data entity.
   */
  public function setCreatedTime($timestamp);

}
