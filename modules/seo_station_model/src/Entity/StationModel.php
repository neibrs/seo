<?php

namespace Drupal\seo_station_model\Entity;


/**
 * Defines the Station model entity.
 *
 * @ingroup seo_station_model
 *
 * @ContentEntityType(
 *   id = "seo_station_model",
 *   label = @Translation("站群模型"),
 *   label_collection = @Translation("站群模型"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station_model\StationModelListBuilder",
 *     "views_data" = "Drupal\seo_station_model\Entity\StationModelViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_station_model\Form\StationModelForm",
 *       "add" = "Drupal\seo_station_model\Form\StationModelForm",
 *       "edit" = "Drupal\seo_station_model\Form\StationModelForm",
 *       "delete" = "Drupal\seo_station_model\Form\StationModelDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station_model\StationModelHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_station_model\StationModelAccessControlHandler",
 *   },
 *   base_table = "seo_station_model",
 *   translatable = FALSE,
 *   admin_permission = "administer station model entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_station_model/{seo_station_model}",
 *     "add-form" = "/admin/seo_station_model/add",
 *     "edit-form" = "/admin/seo_station_model/{seo_station_model}/edit",
 *     "delete-form" = "/admin/seo_station_model/{seo_station_model}/delete",
 *     "collection" = "/admin/seo_station_model",
 *   },
 *   field_ui_base_route = "seo_station_model.settings"
 * )
 */
class StationModel extends \Drupal\seo_station_model\Airui\Entities\StationModel {

}
