<?php

namespace Drupal\dsi_pseudo_api;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Pseudo api entities.
 *
 * @ingroup dsi_pseudo_api
 */
class PseudoApiListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Pseudo api ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\dsi_pseudo_api\Entity\PseudoApi $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.dsi_pseudo_api.edit_form',
      ['dsi_pseudo_api' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
