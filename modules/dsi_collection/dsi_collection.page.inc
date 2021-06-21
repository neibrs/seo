<?php

/**
 * @file
 * Contains dsi_collection.page.inc.
 *
 * Page callback for Collection entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Collection templates.
 *
 * Default template: dsi_collection.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dsi_collection(array &$variables) {
  // Fetch Collection Entity Object.
  $dsi_collection = $variables['elements']['#dsi_collection'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
