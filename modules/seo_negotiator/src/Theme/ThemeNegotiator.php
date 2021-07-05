<?php
namespace Drupal\seo_negotiator\Theme;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

class ThemeNegotiator implements ThemeNegotiatorInterface {

  public function applies(RouteMatchInterface $route_match) {
    return $this->negotiateRoute($route_match) ? TRUE : FALSE;
  }

  public function determineActiveTheme(RouteMatchInterface $route_match) {
    return $this->negotiateRoute($route_match) ?: NULL;
  }

  private function negotiateRoute(RouteMatchInterface $route_match) {
    $address = \Drupal::moduleHandler()->moduleExists('seo_station_address');

    $request = \Drupal::request();
    $url = $request->getHttpHost() . $request->getRequestUri();
    if ($address) {
      $query = \Drupal::entityTypeManager()->getStorage('seo_negotiator')->getQuery();
      $query->condition('name', $url);
      $ids = $query->execute();
      if (!empty($ids)) {
        $negotiator = \Drupal::entityTypeManager()->getStorage('seo_negotiator')->load(reset($ids));

        $theme_active = FALSE;
        // If theme is active.
        if ($theme_active) {

        }
        else {
          // Theme not active, and install it.
          \Drupal::service('theme_installer')->install([$negotiator->get('theme')->value]);
        }

        return $negotiator->theme->value;
      }
    }

    return FALSE;
  }
}
