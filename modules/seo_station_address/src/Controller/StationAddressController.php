<?php

namespace Drupal\seo_station_address\Controller;

use Drupal\Core\Controller\ControllerBase;

class StationAddressController extends ControllerBase {

  public function rebuildSitemapxml() {
    $batch = xmlsitemap_rebuild_batch(['node' => 'node'], 1);
    batch_set($batch);

    return batch_process('/admin/seo_station');
  }

}
