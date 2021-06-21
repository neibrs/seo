<?php

namespace Drupal\seo_station;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Auto prefix entity.
 *
 * @see \Drupal\seo_station\Entity\AutoPrefix.
 */
class AutoPrefixAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\seo_station\Entity\AutoPrefixInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished auto prefix entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published auto prefix entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit auto prefix entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete auto prefix entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add auto prefix entities');
  }


}
