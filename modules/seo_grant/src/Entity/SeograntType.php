<?php

namespace Drupal\seo_grant\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Seogrant type entity.
 *
 * @ConfigEntityType(
 *   id = "seo_grant_type",
 *   label = @Translation("授权类型"),
 *   label_collection = @Translation("授权类型"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_grant\SeograntTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\seo_grant\Form\SeograntTypeForm",
 *       "edit" = "Drupal\seo_grant\Form\SeograntTypeForm",
 *       "delete" = "Drupal\seo_grant\Form\SeograntTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_grant\SeograntTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "seo_grant_type",
 *   admin_permission = "administer seogrant entities",
 *   bundle_of = "seo_grant",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_grant_type/{seo_grant_type}",
 *     "add-form" = "/admin/seo_grant_type/add",
 *     "edit-form" = "/admin/seo_grant_type/{seo_grant_type}/edit",
 *     "delete-form" = "/admin/seo_grant_type/{seo_grant_type}/delete",
 *     "collection" = "/admin/seo_grant_type"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *   }
 * )
 */
class SeograntType extends ConfigEntityBundleBase implements SeograntTypeInterface {

  /**
   * The Seogrant type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Seogrant type label.
   *
   * @var string
   */
  protected $label;

}
