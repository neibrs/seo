<?php

/**
 * @file
 * Contains seo_station_model.page.inc.
 *
 * Page callback for Station model entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Station model templates.
 *
 * Default template: seo_station_model.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seo_station_model(array &$variables) {
  // Fetch StationModel Entity Object.
  $seo_station_model = $variables['elements']['#seo_station_model'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
