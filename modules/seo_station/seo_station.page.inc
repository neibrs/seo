<?php

/**
 * @file
 * Contains seo_station.page.inc.
 *
 * Page callback for Station entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Station templates.
 *
 * Default template: seo_station.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seo_station(array &$variables) {
  // Fetch Station Entity Object.
  $seo_station = $variables['elements']['#seo_station'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
