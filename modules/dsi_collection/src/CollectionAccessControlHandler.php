<?php

namespace Drupal\dsi_collection;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Collection entity.
 *
 * @see \Drupal\dsi_collection\Entity\Collection.
 */
class CollectionAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dsi_collection\Entity\CollectionInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished collection entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published collection entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit collection entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete collection entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add collection entities');
  }


}
