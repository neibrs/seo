<?php

namespace Drupal\spiders;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Spiders group entities.
 *
 * @ingroup spiders
 */
class SpidersGroupListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Spiders group ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\spiders\Entity\SpidersGroup $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.spiders_group.edit_form',
      ['spiders_group' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
