<?php

namespace Drupal\seo_station_address\Controller;

use Drupal\Core\Controller\ControllerBase;

class StationAddressController extends ControllerBase {

  public function rebuildSitemapxml() {
    xmlsitemap_link_bundle_settings_save('node', 'article', [
      'status' => "1",
      'priority' => "0.5",
      "changefreq" => "60",
    ], TRUE);
    xmlsitemap_link_bundle_enable('node', 'article');
    $batch = xmlsitemap_rebuild_batch(['node' => 'node'], 1);
    batch_set($batch);

    return batch_process('/admin/seo_station');
  }

}
