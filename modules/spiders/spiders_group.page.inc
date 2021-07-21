<?php

/**
 * @file
 * Contains spiders_group.page.inc.
 *
 * Page callback for Spiders group entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Spiders group templates.
 *
 * Default template: spiders_group.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_spiders_group(array &$variables) {
  // Fetch SpidersGroup Entity Object.
  $spiders_group = $variables['elements']['#spiders_group'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
