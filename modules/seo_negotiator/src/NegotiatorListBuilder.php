<?php

namespace Drupal\seo_negotiator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Negotiator entities.
 *
 * @ingroup seo_negotiator
 */
class NegotiatorListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Negotiator ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\seo_negotiator\Entity\Negotiator $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.seo_negotiator.edit_form',
      ['seo_negotiator' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
