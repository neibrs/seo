<?php

/**
 * @file
 * Contains seo_textdata.page.inc.
 *
 * Page callback for Text data entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Text data templates.
 *
 * Default template: seo_textdata.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seo_textdata(array &$variables) {
  // Fetch TextData Entity Object.
  $seo_textdata = $variables['elements']['#seo_textdata'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
