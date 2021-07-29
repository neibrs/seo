<?php

namespace Drupal\seo_station_tkdb\Entity;


/**
 * Defines the Tkdb entity.
 *
 * @ingroup seo_station_tkdb
 *
 * @ContentEntityType(
 *   id = "seo_station_tkdb",
 *   label = @Translation("Tkdb"),
 *   label_collection = @Translation("Tkdb"),
 *   bundle_label = @Translation("Tkdb type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station_tkdb\TkdbListBuilder",
 *     "views_data" = "Drupal\seo_station_tkdb\Entity\TkdbViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_station_tkdb\Form\TkdbForm",
 *       "add" = "Drupal\seo_station_tkdb\Form\TkdbForm",
 *       "edit" = "Drupal\seo_station_tkdb\Form\TkdbForm",
 *       "delete" = "Drupal\seo_station_tkdb\Form\TkdbDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station_tkdb\TkdbHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_station_tkdb\TkdbAccessControlHandler",
 *   },
 *   base_table = "seo_station_tkdb",
 *   translatable = FALSE,
 *   admin_permission = "administer tkdb entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_station_tkdb/{seo_station_tkdb}",
 *     "add-page" = "/admin/seo_station_tkdb/add",
 *     "add-form" = "/admin/seo_station_tkdb/add/{seo_station_tkdb_type}",
 *     "edit-form" = "/admin/seo_station_tkdb/{seo_station_tkdb}/edit",
 *     "delete-form" = "/admin/seo_station_tkdb/{seo_station_tkdb}/delete",
 *     "collection" = "/admin/seo_station_tkdb",
 *   },
 *   bundle_entity_type = "seo_station_tkdb_type",
 *   field_ui_base_route = "entity.seo_station_tkdb_type.edit_form"
 * )
 */
class Tkdb extends \Drupal\seo_station_tkdb\Airui\Entities\Tkdb {

}
