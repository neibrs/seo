<?php

namespace Drupal\seo_station_model;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Station model entity.
 *
 * @see \Drupal\seo_station_model\Entity\StationModel.
 */
class StationModelAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\seo_station_model\Entity\StationModelInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished station model entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published station model entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit station model entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete station model entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add station model entities');
  }


}
