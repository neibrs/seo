<?php

namespace Drupal\seo_textdata;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Text data entity.
 *
 * @see \Drupal\seo_textdata\Entity\TextData.
 */
class TextDataAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\seo_textdata\Entity\TextDataInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished text data entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published text data entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit text data entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete text data entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add text data entities');
  }


}
