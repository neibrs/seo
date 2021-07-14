<?php

namespace Drupal\seo_flink;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Flink entity.
 *
 * @see \Drupal\seo_flink\Entity\Flink.
 */
class FlinkAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\seo_flink\Entity\FlinkInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished flink entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published flink entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit flink entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete flink entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add flink entities');
  }


}
