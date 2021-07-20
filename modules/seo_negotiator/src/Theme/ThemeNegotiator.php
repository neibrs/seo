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
    $theme_name = $this->getThemeByRequest($request);
    if (!empty($theme_name)) {
      if (!\Drupal::service('theme_handler')->themeExists($theme_name)) {
        // Theme not active, and install it.
        \Drupal::service('theme_installer')->install([$theme_name]);
      }
      return $theme_name;
    }

    return FALSE;
  }

  protected function getThemeByRequest($request) {
    $theme_negotiator_storage = \Drupal::entityTypeManager()->getStorage('seo_negotiator');
    $theme = '';
    // 1. full
    // 2. wild
    $fullpath = $request->getHost();
    $fullpath .= ':' . $request->getPort();
    $fullpath .= $request->getPathInfo();
    $negotiators = $theme_negotiator_storage->loadByProperties([
      'name' => $fullpath,
    ]);
    if (!empty($negotiators)) {
      $negotiator = reset($negotiators);
      $theme = $negotiator->get('theme')->value;
    }

    // wild domain.
    $host = $request->getHost();
    $host_arr = explode('.', $host);
    // 多级域名时，需要递归处理最接近的一个泛域名


    return $theme;
  }
}
