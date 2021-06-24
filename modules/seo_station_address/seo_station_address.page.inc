<?php

/**
 * @file
 * Contains seo_station_address.page.inc.
 *
 * Page callback for Station address entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Station address templates.
 *
 * Default template: seo_station_address.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seo_station_address(array &$variables) {
  // Fetch StationAddress Entity Object.
  $seo_station_address = $variables['elements']['#seo_station_address'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
