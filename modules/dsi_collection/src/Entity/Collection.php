<?php

namespace Drupal\dsi_collection\Entity;

use Drupal\dsi_collection\Airui\Entity\Collection as CollectionBase;

/**
 * Defines the Collection entity.
 *
 * @ingroup dsi_collection
 *
 * @ContentEntityType(
 *   id = "dsi_collection",
 *   label = @Translation("采集"),
 *   label_collection = @Translation("采集"),
 *   bundle_label = @Translation("采集类型"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dsi_collection\CollectionListBuilder",
 *     "views_data" = "Drupal\dsi_collection\Entity\CollectionViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dsi_collection\Form\CollectionForm",
 *       "add" = "Drupal\dsi_collection\Form\CollectionForm",
 *       "edit" = "Drupal\dsi_collection\Form\CollectionForm",
 *       "delete" = "Drupal\dsi_collection\Form\CollectionDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\dsi_collection\CollectionHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\dsi_collection\CollectionAccessControlHandler",
 *   },
 *   base_table = "dsi_collection",
 *   translatable = FALSE,
 *   admin_permission = "administer collection entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/dsi_collection/{dsi_collection}",
 *     "add-page" = "/dsi_collection/add",
 *     "add-form" = "/dsi_collection/add/{dsi_collection_type}",
 *     "edit-form" = "/dsi_collection/{dsi_collection}/edit",
 *     "delete-form" = "/dsi_collection/{dsi_collection}/delete",
 *     "collection" = "/dsi_collection",
 *   },
 *   bundle_entity_type = "dsi_collection_type",
 *   field_ui_base_route = "entity.dsi_collection_type.edit_form"
 * )
 */
class Collection extends CollectionBase {
}
