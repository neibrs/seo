<?php

namespace Drupal\spiders\Entity;

/**
 * Defines the Spiders group entity.
 *
 * @ingroup spiders
 *
 * @ContentEntityType(
 *   id = "spiders_group",
 *   label = @Translation("蜘蛛防火墙配置"),
 *   label_collection = @Translation("蜘蛛防火墙配置"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\spiders\SpidersGroupListBuilder",
 *     "views_data" = "Drupal\spiders\Entity\SpidersGroupViewsData",
 *     "translation" = "Drupal\spiders\SpidersGroupTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\spiders\Form\SpidersGroupForm",
 *       "add" = "Drupal\spiders\Form\SpidersGroupForm",
 *       "edit" = "Drupal\spiders\Form\SpidersGroupForm",
 *       "delete" = "Drupal\spiders\Form\SpidersGroupDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\spiders\SpidersGroupHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\spiders\SpidersGroupAccessControlHandler",
 *   },
 *   base_table = "spiders_group",
 *   data_table = "spiders_group_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer spiders group entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/spiders_group/{spiders_group}",
 *     "add-form" = "/admin/spiders_group/add",
 *     "edit-form" = "/admin/spiders_group/{spiders_group}/edit",
 *     "delete-form" = "/admin/spiders_group/{spiders_group}/delete",
 *     "collection" = "/admin/spiders_group",
 *   },
 *   field_ui_base_route = "spiders_group.settings"
 * )
 */
class SpidersGroup extends \Drupal\spiders\Airui\Entity\SpidersGroup {

}
