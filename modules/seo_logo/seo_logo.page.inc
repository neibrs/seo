<?php

/**
 * @file
 * Contains seo_logo.page.inc.
 *
 * Page callback for Logo entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Logo templates.
 *
 * Default template: seo_logo.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seo_logo(array &$variables) {
  // Fetch Logo Entity Object.
  $seo_logo = $variables['elements']['#seo_logo'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
