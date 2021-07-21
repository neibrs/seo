<?php

namespace Drupal\spiders;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Spiders group entity.
 *
 * @see \Drupal\spiders\Entity\SpidersGroup.
 */
class SpidersGroupAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\spiders\Entity\SpidersGroupInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished spiders group entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published spiders group entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit spiders group entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete spiders group entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add spiders group entities');
  }


}
