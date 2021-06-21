<?php

namespace Drupal\seo_station_tkdb;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Tkdb entity.
 *
 * @see \Drupal\seo_station_tkdb\Entity\Tkdb.
 */
class TkdbAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\seo_station_tkdb\Entity\TkdbInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished tkdb entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published tkdb entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit tkdb entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete tkdb entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add tkdb entities');
  }


}
