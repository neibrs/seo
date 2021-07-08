<?php

namespace Drupal\seo_station_address\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class StationAddressController extends ControllerBase {

  public function rebuildSitemapxml() {
    $batch = xmlsitemap_rebuild_batch(['node' => 'node'], 1);
    batch_set($batch);

    return [];
  }

}
