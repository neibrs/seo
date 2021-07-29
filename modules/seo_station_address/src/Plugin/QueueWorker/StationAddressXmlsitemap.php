<?php

namespace Drupal\seo_station_address\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\xmlsitemap\Entity\XmlSitemap;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @QueueWorker(
 *   id = "station_address_xmlsitemap",
 *   title = @Translation("Station Address xmlsitemap"),
 *   cron = {"time" = 60}
 * )
 */
class StationAddressXmlsitemap extends \Drupal\seo_station_address\Airui\Plugin\QueueWorker\StationAddressXmlsitemap {

}
