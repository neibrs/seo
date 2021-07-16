<?php

namespace Drupal\seo_flink;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

class FlinkStorage extends SqlContentEntityStorage implements FlinkStorageInterface {

  /**
   * {@inheritDoc}
   */
  public function loadEnabledFlink() {
    $query = $this->getQuery();
    $query->condition('mode', TRUE);
    $ids = $query->execute();
    return $this->loadMultiple($ids);
  }

}
