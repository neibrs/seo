<?php

namespace Drupal\seo_textdata\Entity;


/**
 * Defines the Text data entity.
 *
 * @ingroup seo_textdata
 *
 * @ContentEntityType(
 *   id = "seo_textdata",
 *   label = @Translation("内容库"),
 *   label_collection = @Translation("内容库"),
 *   bundle_label = @Translation("内容库类型"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_textdata\TextDataListBuilder",
 *     "views_data" = "Drupal\seo_textdata\Entity\TextDataViewsData",
 *     "translation" = "Drupal\seo_textdata\TextDataTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_textdata\Form\TextDataForm",
 *       "add" = "Drupal\seo_textdata\Form\TextDataForm",
 *       "edit" = "Drupal\seo_textdata\Form\TextDataForm",
 *       "delete" = "Drupal\seo_textdata\Form\TextDataDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_textdata\TextDataHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_textdata\TextDataAccessControlHandler",
 *   },
 *   base_table = "seo_textdata",
 *   data_table = "seo_textdata_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer text data entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_textdata/{seo_textdata}",
 *     "add-page" = "/admin/seo_textdata/add",
 *     "add-form" = "/admin/seo_textdata/add/{seo_textdata_type}",
 *     "edit-form" = "/admin/seo_textdata/{seo_textdata}/edit",
 *     "delete-form" = "/admin/seo_textdata/{seo_textdata}/delete",
 *     "collection" = "/admin/seo_textdata",
 *   },
 *   bundle_entity_type = "seo_textdata_type",
 *   field_ui_base_route = "entity.seo_textdata_type.edit_form"
 * )
 */
class TextData extends \Drupal\seo_textdata\Airui\Entities\TextData {

}
