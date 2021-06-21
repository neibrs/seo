<?php

/**
 * @file
 * Contains dsi_pseudo_api.page.inc.
 *
 * Page callback for Pseudo api entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Pseudo api templates.
 *
 * Default template: dsi_pseudo_api.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dsi_pseudo_api(array &$variables) {
  // Fetch PseudoApi Entity Object.
  $dsi_pseudo_api = $variables['elements']['#dsi_pseudo_api'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
