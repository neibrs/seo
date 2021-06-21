<?php

namespace Drupal\seo_grant;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Seogrant entity.
 *
 * @see \Drupal\seo_grant\Entity\Seogrant.
 */
class SeograntAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\seo_grant\Entity\SeograntInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished seogrant entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published seogrant entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit seogrant entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete seogrant entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add seogrant entities');
  }


}
