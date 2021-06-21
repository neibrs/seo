<?php

namespace Drupal\seo_station_tkdb;


use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TkdbManager extends TkdbManagerInterface implements ContainerInjectionInterface {

  protected $tkdb_storage;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->tkdb_storage = $entity_type_manager->getStorage('seo_station_tkdb');
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * @param $keywords
   *
   * $keywords = [
   *  'name' => $v['template'],
   *  'type' => $type,
   *  'model' => $this->model,
   *  'group' => $this->station,
   *  ];
   * @return mixed
   */
  public function getTkdb($keywords) {
    $query = $this->tkdb_storage->getQuery();
    foreach ($keywords as $k => $v) {
      if (empty($v)) {
        $query->condition($k, $v, 'IS NULL');
      }
      else {
        $query->condition($k, $v);
      }
    }
    $ids = $query->execute();

    return $ids;
  }

}
