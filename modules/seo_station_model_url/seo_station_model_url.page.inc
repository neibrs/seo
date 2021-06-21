<?php

/**
 * @file
 * Contains seo_station_model_url.page.inc.
 *
 * Page callback for Station model url entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Station model url templates.
 *
 * Default template: seo_station_model_url.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seo_station_model_url(array &$variables) {
  // Fetch StationModelUrl Entity Object.
  $seo_station_model_url = $variables['elements']['#seo_station_model_url'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
