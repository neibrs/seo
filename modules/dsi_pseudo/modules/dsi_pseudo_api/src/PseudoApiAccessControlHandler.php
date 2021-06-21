<?php

namespace Drupal\dsi_pseudo_api;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Pseudo api entity.
 *
 * @see \Drupal\dsi_pseudo_api\Entity\PseudoApi.
 */
class PseudoApiAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dsi_pseudo_api\Entity\PseudoApiInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished pseudo api entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published pseudo api entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit pseudo api entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete pseudo api entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add pseudo api entities');
  }


}
