<?php

namespace Drupal\seo_station_tkdb;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Tkdb entities.
 *
 * @ingroup seo_station_tkdb
 */
class TkdbListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Tkdb ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\seo_station_tkdb\Entity\Tkdb $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.seo_station_tkdb.model_station.edit_form',
      ['seo_station_tkdb' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
