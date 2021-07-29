<?php

namespace Drupal\seo_logo\Entity;
use Drupal\seo_logo\Airui\Entity\Logo as LogoBase;

/**
 * Defines the Logo entity.
 *
 * @ingroup seo_logo
 *
 * @ContentEntityType(
 *   id = "seo_logo",
 *   label = @Translation("Logo"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_logo\LogoListBuilder",
 *     "views_data" = "Drupal\seo_logo\Entity\LogoViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_logo\Form\LogoForm",
 *       "add" = "Drupal\seo_logo\Form\LogoForm",
 *       "edit" = "Drupal\seo_logo\Form\LogoForm",
 *       "delete" = "Drupal\seo_logo\Form\LogoDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_logo\LogoHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_logo\LogoAccessControlHandler",
 *   },
 *   base_table = "seo_logo",
 *   translatable = FALSE,
 *   admin_permission = "administer logo entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_logo/{seo_logo}",
 *     "add-form" = "/admin/seo_logo/add",
 *     "edit-form" = "/admin/seo_logo/{seo_logo}/edit",
 *     "delete-form" = "/admin/seo_logo/{seo_logo}/delete",
 *     "collection" = "/admin/seo_logo",
 *   },
 *   field_ui_base_route = "seo_logo.settings"
 * )
 */
class Logo extends LogoBase {
}
