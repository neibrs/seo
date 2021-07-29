<?php

namespace Drupal\seo_station\Entity;


/**
 * Defines the Auto prefix entity.
 *
 * @ingroup seo_station
 *
 * @ContentEntityType(
 *   id = "seo_autoprefix",
 *   label = @Translation("Auto prefix"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station\AutoPrefixListBuilder",
 *     "views_data" = "Drupal\seo_station\Entity\AutoPrefixViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_station\Form\AutoPrefixForm",
 *       "add" = "Drupal\seo_station\Form\AutoPrefixForm",
 *       "edit" = "Drupal\seo_station\Form\AutoPrefixForm",
 *       "delete" = "Drupal\seo_station\Form\AutoPrefixDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station\AutoPrefixHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_station\AutoPrefixAccessControlHandler",
 *   },
 *   base_table = "seo_autoprefix",
 *   translatable = FALSE,
 *   admin_permission = "administer auto prefix entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_autoprefix/{seo_autoprefix}",
 *     "add-form" = "/admin/seo_autoprefix/add",
 *     "edit-form" = "/admin/seo_autoprefix/{seo_autoprefix}/edit",
 *     "delete-form" = "/admin/seo_autoprefix/{seo_autoprefix}/delete",
 *     "collection" = "/admin/seo_autoprefix",
 *   },
 *   field_ui_base_route = "seo_autoprefix.settings"
 * )
 */
class AutoPrefix extends \Drupal\seo_station\Airui\Entities\AutoPrefix {
}
