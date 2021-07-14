<?php

/**
 * @file
 * Contains seo_flink.page.inc.
 *
 * Page callback for Flink entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Flink templates.
 *
 * Default template: seo_flink.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seo_flink(array &$variables) {
  // Fetch Flink Entity Object.
  $seo_flink = $variables['elements']['#seo_flink'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
