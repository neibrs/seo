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


  public function getThemeByRequest($request) {
    $stations = \Drupal::entityTypeManager()->getStorage('seo_station')->loadMultiple();
    $theme = '';
    $find = FALSE;
    foreach ($stations as $station) {
      $domains = array_unique(explode(',', str_replace("\r\n",",", $station->domain->value)));
      $domains = array_filter($domains);
      foreach ($domains as $domain) {
        if (strpos($request->getHost(), $domain) !== FALSE) {
          // 找到已定义域名
          $find = TRUE;
          // 检查station_address下有没有该域名的主题数据.
          $theme = \Drupal::service('seo_station_address.manager')->updateThemeByDomain($request, $theme, $domain, $station);
          break;
        }
      }
      if ($find && !empty($theme)) {
        break;
      }
    }

    if (empty($stations)) {
      // 设置官网主题
      $theme = \Drupal::service('seo_station_address.manager')->getThemeNameByRequest($request);
    }

    return $theme;
  }

  public function getThemeByNegotiator($request) {
    $theme_negotiator_storage = \Drupal::entityTypeManager()->getStorage('seo_negotiator');
    $negs = $theme_negotiator_storage->loadMultiple();
    $host = $request->getHost();
    foreach ($negs as $neg) {
      if ($pos = strpos($neg->name->value, '*.') !== FALSE) {
        if (preg_match('#' . substr($neg->label(), 2). '$#', $host, $match)) {
          return $neg->theme->value;
        }
        else {
          continue;
        }
      }

      if ($host == strtolower($neg->name->value)) {
        return $neg->theme->value;
      }
    }

    return '';
  }

  /**
   * {@inheritDoc}
   */
  private function negotiateRoute(RouteMatchInterface $route_match) {
    $request = \Drupal::request();
    $theme_name = $this->getThemeByNegotiator($request);
    if (empty($theme_name)) {
      $theme_name = $this->getThemeByRequest($request);
    }

    // Get admin theme
    $special_path = [
      '/^\/admin/',
      '/^\/user/',
    ];
    foreach ($special_path as $special) {
      preg_match($special, $request->getPathInfo(),$result);
      if (!empty($result)) {
        $admin_theme = \Drupal::configFactory()->get('system.theme')->get('admin');
        if (in_array($admin_theme, ['seven', 'xbarrio'])) {
          $theme_name = $admin_theme;
        }
        else {
          $theme_name = 'xbarrio';
        }
        break;
      }
    }
    if (!empty($theme_name)) {
      if (!\Drupal::service('theme_handler')->themeExists($theme_name)) {
        // Theme not active, and install it.
        \Drupal::service('theme_installer')->install([$theme_name]);
      }
      return $theme_name;
    }

    return FALSE;
  }

}
