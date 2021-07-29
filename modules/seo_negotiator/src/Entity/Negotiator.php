<?php

namespace Drupal\seo_negotiator\Entity;

use Drupal\seo_negotiator\Airui\Entity\Negotiator as NegotiatorBase;

/**
 * Defines the Negotiator entity.
 *
 * @ingroup seo_negotiator
 *
 * @ContentEntityType(
 *   id = "seo_negotiator",
 *   label = @Translation("Negotiator"),
 *   handlers = {
 *     "storage" = "Drupal\seo_negotiator\NegotiatorStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_negotiator\NegotiatorListBuilder",
 *     "views_data" = "Drupal\seo_negotiator\Entity\NegotiatorViewsData",
 *
 *     "access" = "Drupal\seo_negotiator\NegotiatorAccessControlHandler",
 *   },
 *   base_table = "seo_negotiator",
 *   translatable = FALSE,
 *   admin_permission = "administer negotiator entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 * )
 */
class Negotiator extends NegotiatorBase {
}
