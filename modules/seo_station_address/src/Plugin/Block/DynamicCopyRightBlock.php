<?php

namespace Drupal\seo_station_address\Plugin\Block;

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

  /**
   * {@inheritDoc}
   */
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


    $build['#content']['time'] = ['#markup' => $time_start . ' - ' . $time];
    $build['#content']['site_name'] = ['#markup' => $site_name];
    $prefix = $this->getProvincePrefix();
    $build['#content']['beian'] = ['#markup' => $prefix . 'ICP备' . $this->getBeanId() .'号-1'];
    $build['#content']['anbei'] = ['#markup' => $prefix . '公网安备110106' . $this->getAneId() . '号-1'];

    return $build;
  }

  public function getAneId(): int {
    return mt_rand(1000000, 9999999);
  }
  public function getBeanId(): int {
    return mt_rand(1000000, 9999999);
  }
  public function getProvincePrefix() {
    $data = [
      '渝',
      '蜀',
    ];
    return $data[array_rand($data, 1)];
  }
  /**
   * {@inheritDoc}
   */
  public function getCacheContexts(): array {
    return ['url'];
  }

}
