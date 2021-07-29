<?php

namespace Drupal\dsi_pseudo_api\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Pseudo api entity.
 *
 * @ingroup dsi_pseudo_api
 *
 * @ContentEntityType(
 *   id = "dsi_pseudo_api",
 *   label = @Translation("Pseudo api"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dsi_pseudo_api\PseudoApiListBuilder",
 *     "views_data" = "Drupal\dsi_pseudo_api\Entity\PseudoApiViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dsi_pseudo_api\Form\PseudoApiForm",
 *       "add" = "Drupal\dsi_pseudo_api\Form\PseudoApiForm",
 *       "edit" = "Drupal\dsi_pseudo_api\Form\PseudoApiForm",
 *       "delete" = "Drupal\dsi_pseudo_api\Form\PseudoApiDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\dsi_pseudo_api\PseudoApiHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\dsi_pseudo_api\PseudoApiAccessControlHandler",
 *   },
 *   base_table = "dsi_pseudo_api",
 *   translatable = FALSE,
 *   admin_permission = "administer pseudo api entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/dsi_pseudo_api/{dsi_pseudo_api}",
 *     "add-form" = "/dsi_pseudo_api/add",
 *     "edit-form" = "/dsi_pseudo_api/{dsi_pseudo_api}/edit",
 *     "delete-form" = "/dsi_pseudo_api/{dsi_pseudo_api}/delete",
 *     "collection" = "/dsi_pseudo_api",
 *   },
 *   field_ui_base_route = "dsi_pseudo_api.settings"
 * )
 */
class PseudoApi extends \Drupal\dsi_pseudo_api\Airui\Entity\PseudoApi {

}
