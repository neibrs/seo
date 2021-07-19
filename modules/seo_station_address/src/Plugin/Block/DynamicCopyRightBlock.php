<?php

namespace Drupal\seo_station_address\Plugin;

use Drupal\Core\Block\BlockBase;
/**
 * Provides a 'copy right' block.
 *
 * @Block(
 *   id = "dynamic_copyright_block",
 *   admin_label = @Translation("站群版本区块")
 * )
 */
class DynamicCopyRightBlock extends BlockBase {

  public function build() {
    $build = [];
    $station = \Drupal::entityTypeManager()->getStorage('seo_station_address');
    $host = \Drupal::request()->getHost();
    $query = $station->getQuery()
      ->condition('domain', $host);
    $ids = $query->execute();
    $id = reset($ids);
    $station_address = $station->load($id);

    // 1. sitename
    // 2. time
    // 3. bei an hao
    $site_name = $station_address->webname->value;
    $time = date('Y', REQUEST_TIME);
    $time_start = $time-20;


    $build['#content']['time'] = $time_start . ' - ' . $time;
    $build['#content']['site_name'] = $site_name;
    $build['#content']['beian'] = '京ICP备13001482号-1';
    $build['#content']['anbei'] = '京公网安备11010602004977号-1';

    return $build;
  }

}
