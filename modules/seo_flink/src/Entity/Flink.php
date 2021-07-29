<?php

namespace Drupal\seo_flink\Entity;

use Drupal\seo_flink\Airui\Entity\Flink as FlinkBase;

/**
 * Defines the Flink entity.
 *
 * @ingroup seo_flink
 *
 * @ContentEntityType(
 *   id = "seo_flink",
 *   label = @Translation("友情链接"),
 *   label_collection = @Translation("友情链接"),
 *   handlers = {
 *     "storage" = "Drupal\seo_flink\FlinkStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_flink\FlinkListBuilder",
 *     "views_data" = "Drupal\seo_flink\Entity\FlinkViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_flink\Form\FlinkForm",
 *       "add" = "Drupal\seo_flink\Form\FlinkForm",
 *       "edit" = "Drupal\seo_flink\Form\FlinkForm",
 *       "delete" = "Drupal\seo_flink\Form\FlinkDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_flink\FlinkHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_flink\FlinkAccessControlHandler",
 *   },
 *   base_table = "seo_flink",
 *   translatable = FALSE,
 *   admin_permission = "administer flink entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_flink/{seo_flink}",
 *     "add-form" = "/admin/seo_flink/add",
 *     "edit-form" = "/admin/seo_flink/{seo_flink}/edit",
 *     "delete-form" = "/admin/seo_flink/{seo_flink}/delete",
 *     "collection" = "/admin/seo_flink",
 *   },
 *   field_ui_base_route = "seo_flink.settings"
 * )
 */
class Flink extends FlinkBase {
}
