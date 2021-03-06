<?php

namespace Drupal\seo_station_tkdb\Entity;


/**
 * Defines the Tkdb type entity.
 *
 * @ConfigEntityType(
 *   id = "seo_station_tkdb_type",
 *   label = @Translation("Tkdb type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station_tkdb\TkdbTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\seo_station_tkdb\Form\TkdbTypeForm",
 *       "edit" = "Drupal\seo_station_tkdb\Form\TkdbTypeForm",
 *       "delete" = "Drupal\seo_station_tkdb\Form\TkdbTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station_tkdb\TkdbTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "seo_station_tkdb_type",
 *   admin_permission = "administer tkdb entities",
 *   bundle_of = "seo_station_tkdb",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_station_tkdb_type/{seo_station_tkdb_type}",
 *     "add-form" = "/admin/seo_station_tkdb_type/add",
 *     "edit-form" = "/admin/seo_station_tkdb_type/{seo_station_tkdb_type}/edit",
 *     "delete-form" = "/admin/seo_station_tkdb_type/{seo_station_tkdb_type}/delete",
 *     "collection" = "/admin/seo_station_tkdb_type"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *   }
 * )
 */
class TkdbType extends \Drupal\seo_station_tkdb\Airui\Entity\TkdbType {

}
