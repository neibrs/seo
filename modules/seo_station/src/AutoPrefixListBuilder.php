<?php

namespace Drupal\seo_station;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Auto prefix entities.
 *
 * @ingroup seo_station
 */
class AutoPrefixListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Auto prefix ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\seo_station\Entity\AutoPrefix $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.seo_autoprefix.edit_form',
      ['seo_autoprefix' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
