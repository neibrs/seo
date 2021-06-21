<?php

namespace Drupal\seo_textdata\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Text data type entity.
 *
 * @ConfigEntityType(
 *   id = "seo_textdata_type",
 *   label = @Translation("Text data type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_textdata\TextDataTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\seo_textdata\Form\TextDataTypeForm",
 *       "edit" = "Drupal\seo_textdata\Form\TextDataTypeForm",
 *       "delete" = "Drupal\seo_textdata\Form\TextDataTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_textdata\TextDataTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "seo_textdata_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "seo_textdata",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/seo_textdata_type/{seo_textdata_type}",
 *     "add-form" = "/seo_textdata_type/add",
 *     "edit-form" = "/seo_textdata_type/{seo_textdata_type}/edit",
 *     "delete-form" = "/seo_textdata_type/{seo_textdata_type}/delete",
 *     "collection" = "/seo_textdata_type"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *   }
 * )
 */
class TextDataType extends ConfigEntityBundleBase implements TextDataTypeInterface {

  /**
   * The Text data type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Text data type label.
   *
   * @var string
   */
  protected $label;

}
