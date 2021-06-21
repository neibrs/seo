<?php

namespace Drupal\seo_station_model_url;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Station model url entities.
 *
 * @ingroup seo_station_model_url
 */
class StationModelUrlListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Station model url ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\seo_station_model_url\Entity\StationModelUrl $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.seo_station_model_url.edit_form',
      ['seo_station_model_url' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
