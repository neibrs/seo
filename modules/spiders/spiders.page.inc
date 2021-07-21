<?php

/**
 * @file
 * Contains spiders.page.inc.
 *
 * Page callback for Spiders entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Spiders templates.
 *
 * Default template: spiders.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_spiders(array &$variables) {
  // Fetch Spiders Entity Object.
  $spiders = $variables['elements']['#spiders'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
