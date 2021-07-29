<?php

namespace Drupal\seo_station\Entity;

/**
 * Defines the Station entity.
 *
 * @ingroup seo_station
 *
 * @ContentEntityType(
 *   id = "seo_station",
 *   label = @Translation("网站管理"),
 *   label_collection = @Translation("网站管理"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station\StationListBuilder",
 *     "views_data" = "Drupal\seo_station\Entity\StationViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_station\Form\StationForm",
 *       "add" = "Drupal\seo_station\Form\StationForm",
 *       "edit" = "Drupal\seo_station\Form\StationForm",
 *       "delete" = "Drupal\seo_station\Form\StationDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station\StationHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_station\StationAccessControlHandler",
 *   },
 *   base_table = "seo_station",
 *   translatable = FALSE,
 *   admin_permission = "administer station entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_station/{seo_station}",
 *     "add-form" = "/admin/seo_station/add",
 *     "edit-form" = "/admin/seo_station/{seo_station}/edit",
 *     "delete-form" = "/admin/seo_station/{seo_station}/delete",
 *     "collection" = "/admin/seo_station",
 *   },
 *   field_ui_base_route = "seo_station.settings"
 * )
 */
class Station extends \Drupal\seo_station\Airui\Entity\Station {
}
