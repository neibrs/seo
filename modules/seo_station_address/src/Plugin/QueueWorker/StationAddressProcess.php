<?php

namespace Drupal\seo_station_address\Plugin\QueueWorker;


/**
 * @QueueWorker(
 *   id = "station_address_process",
 *   title = @Translation("Station Address process"),
 *   cron = {"time" = 60}
 * )
 * 保存动态生成的域名
 */
class StationAddressProcess extends \Drupal\seo_station_address\Airui\Plugin\QueueWorker\StationAddressProcess {

}
