<?php

namespace Drupal\seo_station_model_url;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Station model url entity.
 *
 * @see \Drupal\seo_station_model_url\Entity\StationModelUrl.
 */
class StationModelUrlAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\seo_station_model_url\Entity\StationModelUrlInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished station model url entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published station model url entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit station model url entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete station model url entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add station model url entities');
  }


}
