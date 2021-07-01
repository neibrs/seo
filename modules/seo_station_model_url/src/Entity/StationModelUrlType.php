<?php

namespace Drupal\seo_station_model_url\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the 模板URL规则类型 entity.
 *
 * @ConfigEntityType(
 *   id = "seo_station_model_url_type",
 *   label = @Translation("模板URL规则类型"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station_model_url\StationModelUrlTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\seo_station_model_url\Form\StationModelUrlTypeForm",
 *       "edit" = "Drupal\seo_station_model_url\Form\StationModelUrlTypeForm",
 *       "delete" = "Drupal\seo_station_model_url\Form\StationModelUrlTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station_model_url\StationModelUrlTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "seo_station_model_url_type",
 *   admin_permission = "administer station model url entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_station_model_url_type/{seo_station_model_url_type}",
 *     "add-form" = "/admin/seo_station_model_url_type/add",
 *     "edit-form" = "/admin/seo_station_model_url_type/{seo_station_model_url_type}/edit",
 *     "delete-form" = "/admin/seo_station_model_url_type/{seo_station_model_url_type}/delete",
 *     "collection" = "/admin/seo_station_model_url_type"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *   }
 * )
 */
class StationModelUrlType extends ConfigEntityBase implements StationModelUrlTypeInterface {

  /**
   * The 模板URL规则类型 ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The 模板URL规则类型 label.
   *
   * @var string
   */
  protected $label;

}
