<?php

namespace Drupal\seo_station_model;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Station model entities.
 *
 * @ingroup seo_station_model
 */
class StationModelListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Station model ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\seo_station_model\Entity\StationModel $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.seo_station_model.edit_form',
      ['seo_station_model' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
