<?php

namespace Drupal\seo_station;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Station entity.
 *
 * @see \Drupal\seo_station\Entity\Station.
 */
class StationAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\seo_station\Entity\StationInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished station entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published station entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit station entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete station entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add station entities');
  }


}
