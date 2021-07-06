<?php
namespace Drupal\seo_negotiator\Theme;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

class ThemeNegotiator implements ThemeNegotiatorInterface {

  /**
   * {@inheritDoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    return $this->negotiateRoute($route_match) ? TRUE : FALSE;
  }

  /**
   * {@inheritDoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    return $this->negotiateRoute($route_match) ?: NULL;
  }

  /**
   * {@inheritDoc}
   */
  private function negotiateRoute(RouteMatchInterface $route_match) {
    $request = \Drupal::request();
    $url = $request->getSchemeAndHttpHost() . $request->getRequestUri();
    $negotiators = \Drupal::entityTypeManager()->getStorage('seo_negotiator')->loadByProperties([
      'name' => $url,
    ]);
    if (!empty($negotiators)) {
      $negotiator = reset($negotiators);
      // If theme is active.
      $theme_name = $negotiator->get('theme')->value;
      if (!\Drupal::service('theme_handler')->themeExists($theme_name)) {
        // Theme not active, and install it.
        \Drupal::service('theme_installer')->install([$negotiator->get('theme')->value]);
      }
      return $theme_name;
    }

    return FALSE;
  }
}
