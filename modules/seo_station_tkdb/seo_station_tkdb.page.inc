<?php

/**
 * @file
 * Contains seo_station_tkdb.page.inc.
 *
 * Page callback for Tkdb entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Tkdb templates.
 *
 * Default template: seo_station_tkdb.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seo_station_tkdb(array &$variables) {
  // Fetch Tkdb Entity Object.
  $seo_station_tkdb = $variables['elements']['#seo_station_tkdb'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
