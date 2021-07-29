<?php

namespace Drupal\spiders\Entity;


/**
 * Defines the Spiders entity.
 *
 * @ingroup spiders
 *
 * @ContentEntityType(
 *   id = "spiders",
 *   label = @Translation("蜘蛛记录"),
 *   label_collection = @Translation("蜘蛛记录"),
 *   bundle_label = @Translation("蜘蛛名称"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\spiders\SpidersListBuilder",
 *     "views_data" = "Drupal\spiders\Entity\SpidersViewsData",
 *     "translation" = "Drupal\spiders\SpidersTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\spiders\Form\SpidersForm",
 *       "add" = "Drupal\spiders\Form\SpidersForm",
 *       "edit" = "Drupal\spiders\Form\SpidersForm",
 *       "delete" = "Drupal\spiders\Form\SpidersDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\spiders\SpidersHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\spiders\SpidersAccessControlHandler",
 *   },
 *   base_table = "spiders",
 *   data_table = "spiders_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer spiders entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/spiders/{spiders}",
 *     "add-page" = "/admin/spiders/add",
 *     "add-form" = "/admin/spiders/add/{spiders_type}",
 *     "edit-form" = "/admin/spiders/{spiders}/edit",
 *     "delete-form" = "/admin/spiders/{spiders}/delete",
 *     "collection" = "/admin/spiders",
 *   },
 *   bundle_entity_type = "spiders_type",
 *   field_ui_base_route = "entity.spiders_type.edit_form"
 * )
 */
class Spiders extends \Drupal\spiders\Airui\Entity\Spiders {

}
