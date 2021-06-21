<?php

/**
 * @file
 * Contains seo_autoprefix.page.inc.
 *
 * Page callback for Auto prefix entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Auto prefix templates.
 *
 * Default template: seo_autoprefix.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seo_autoprefix(array &$variables) {
  // Fetch AutoPrefix Entity Object.
  $seo_autoprefix = $variables['elements']['#seo_autoprefix'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
