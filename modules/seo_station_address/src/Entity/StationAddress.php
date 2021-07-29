<?php

namespace Drupal\seo_station_address\Entity;

/**
 * Defines the Station address entity.
 *
 * @ingroup seo_station_address
 *
 * @ContentEntityType(
 *   id = "seo_station_address",
 *   label = @Translation("提取地址"),
 *   label_collection = @Translation("提取地址"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station_address\StationAddressListBuilder",
 *     "views_data" = "Drupal\seo_station_address\Entity\StationAddressViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_station_address\Form\StationAddressForm",
 *       "add" = "Drupal\seo_station_address\Form\StationAddressForm",
 *       "edit" = "Drupal\seo_station_address\Form\StationAddressForm",
 *       "delete" = "Drupal\seo_station_address\Form\StationAddressDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station_address\StationAddressHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_station_address\StationAddressAccessControlHandler",
 *   },
 *   base_table = "seo_station_address",
 *   translatable = FALSE,
 *   admin_permission = "administer station address entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_station_address/{seo_station_address}",
 *     "add-form" = "/admin/seo_station_address/add",
 *     "edit-form" = "/admin/seo_station_address/{seo_station_address}/edit",
 *     "delete-form" = "/admin/seo_station_address/{seo_station_address}/delete",
 *     "collection" = "/admin/seo_station_address",
 *   },
 *   field_ui_base_route = "seo_station_address.settings"
 * )
 */
class StationAddress extends \Drupal\seo_station_address\Airui\Entities\StationAddress {

}
