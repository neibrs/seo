<?php

namespace Drupal\seo_station_model_url\Entity;


/**
 * Defines the Station model url entity.
 *
 * @ingroup seo_station_model_url
 *
 * @ContentEntityType(
 *   id = "seo_station_model_url",
 *   label = @Translation("站群模型Url规则"),
 *   label_collection = @Translation("站群模型Url规则"),
 *   handlers = {
 *     "storage" = "Drupal\seo_station_model_url\StationModelUrlStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station_model_url\StationModelUrlListBuilder",
 *     "views_data" = "Drupal\seo_station_model_url\Entity\StationModelUrlViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_station_model_url\Form\StationModelUrlForm",
 *       "add" = "Drupal\seo_station_model_url\Form\StationModelUrlForm",
 *       "edit" = "Drupal\seo_station_model_url\Form\StationModelUrlForm",
 *       "delete" = "Drupal\seo_station_model_url\Form\StationModelUrlDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station_model_url\StationModelUrlHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_station_model_url\StationModelUrlAccessControlHandler",
 *   },
 *   base_table = "seo_station_model_url",
 *   translatable = FALSE,
 *   admin_permission = "administer station model url entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_station_model_url/{seo_station_model_url}",
 *     "add-page" = "/admin/seo_station_model_url/add",
 *     "add-form" = "/admin/seo_station_model_url/add/{seo_station_model_url_type}",
 *     "edit-form" = "/admin/seo_station_model_url/{seo_station_model_url}/edit",
 *     "delete-form" = "/admin/seo_station_model_url/{seo_station_model_url}/delete",
 *     "collection" = "/admin/seo_station_model_url",
 *   },
 *   bundle_entity_type = "seo_station_model_url_type",
 *   field_ui_base_route = "entity.seo_station_model_url_type.edit_form",
 * )
 */
class StationModelUrl extends \Drupal\seo_station_model_url\Airui\Entities\StationModelUrl {

}
