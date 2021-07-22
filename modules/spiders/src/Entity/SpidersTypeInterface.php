<?php

namespace Drupal\spiders\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Spiders type entities.
 */
interface SpidersTypeInterface extends ConfigEntityInterface {

  // Add get/set methods for your configuration properties here.
  public function getUserAgent();
  public function getSource();
}
