<?php

namespace Drupal\seo_flink;

use Drupal\Core\Entity\ContentEntityStorageInterface;

interface FlinkStorageInterface extends ContentEntityStorageInterface {

  /**
   * @return \Drupal\seo_flink\Entity\FlinkInterface []
   */
  public function loadEnabledFlink();
}
