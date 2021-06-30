<?php

namespace Drupal\seo_negotiator;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Negotiator entity.
 *
 * @see \Drupal\seo_negotiator\Entity\Negotiator.
 */
class NegotiatorAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\seo_negotiator\Entity\NegotiatorInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished negotiator entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published negotiator entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit negotiator entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete negotiator entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add negotiator entities');
  }


}
