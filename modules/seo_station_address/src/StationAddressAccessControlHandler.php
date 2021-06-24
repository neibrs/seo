<?php

namespace Drupal\seo_station_address;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Station address entity.
 *
 * @see \Drupal\seo_station_address\Entity\StationAddress.
 */
class StationAddressAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\seo_station_address\Entity\StationAddressInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished station address entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published station address entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit station address entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete station address entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add station address entities');
  }


}
