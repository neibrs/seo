<?php

/**
 * @file
 * Contains seo_grant.page.inc.
 *
 * Page callback for Seogrant entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Seogrant templates.
 *
 * Default template: seo_grant.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_seo_grant(array &$variables) {
  // Fetch Seogrant Entity Object.
  $seo_grant = $variables['elements']['#seo_grant'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
