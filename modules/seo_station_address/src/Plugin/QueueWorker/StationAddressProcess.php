<?php

namespace Drupal\seo_station_address\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\xmlsitemap\Entity\XmlSitemap;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @QueueWorker(
 *   id = "station_address_process",
 *   title = @Translation("Station Address process"),
 *   cron = {"time" = 60}
 * )
 * 保存动态生成的域名
 */
class StationAddressProcess extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /** @var \Drupal\Core\Entity\EntityTypeManagerInterface */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    try {
//      data结构
//      $data = [
//        'name' => 'dxxdf.sdfd.com/show/2343.html',
//        'domain' => 'dxxdf.sdfd.com',
//        'replacement' => '/show/2343.html',
//      ];
      $storage = \Drupal::entityTypeManager()->getStorage('seo_station_address');
      $station_address = $storage->create($data);
      $station_address->save();
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addWarning($e);
    }
  }
}
