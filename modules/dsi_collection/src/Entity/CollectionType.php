<?php

namespace Drupal\dsi_collection\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Collection type entity.
 *
 * @ConfigEntityType(
 *   id = "dsi_collection_type",
 *   label = @Translation("采集类型"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dsi_collection\CollectionTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\dsi_collection\Form\CollectionTypeForm",
 *       "edit" = "Drupal\dsi_collection\Form\CollectionTypeForm",
 *       "delete" = "Drupal\dsi_collection\Form\CollectionTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\dsi_collection\CollectionTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "type",
 *   admin_permission = "administer collection entities",
 *   bundle_of = "dsi_collection",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/dsi_collection_type/{dsi_collection_type}",
 *     "add-form" = "/dsi_collection_type/add",
 *     "edit-form" = "/dsi_collection_type/{dsi_collection_type}/edit",
 *     "delete-form" = "/dsi_collection_type/{dsi_collection_type}/delete",
 *     "collection" = "/dsi_collection_type"
 *   }
 * )
 */
class CollectionType extends ConfigEntityBundleBase implements CollectionTypeInterface {

  /**
   * The Collection type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Collection type label.
   *
   * @var string
   */
  protected $label;

}
