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
class StationAddressXmlsitemap extends QueueWorkerBase implements ContainerFactoryPluginInterface {

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
    // TODO， 暂时只做一个新闻类型的网站文章数据.
    try {
      $xmlsitemap = $this->entityTypeManager->getStorage('xmlsitemap');
      $exist = XmlSitemap::loadByContext($data['context']);
      if (empty($exist)) {
        $data['id'] = xmlsitemap_sitemap_get_context_hash($data['context']);
        $xmlsitemap->create($data)
          ->save();
      }
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addWarning($e);
    }
  }
}
