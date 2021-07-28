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

  public function getWildSiteMode($request, $domain, $station) {
    $host = $request->getHost();

    $address_storage = \Drupal::entityTypeManager()->getStorage('seo_station_address');
    $query = $address_storage->getQuery();
    $query->condition('domain', $host);
    $ids = $query->execute();
    if (!empty($ids)) {
      $addr = $address_storage->load(reset($ids));
    }

    $theme = $addr->theme->value;
    if (empty($theme)) {
      $theme = $this->getThemeByStation($station);
    }

    // 将主题固化到域名上.
    if (!empty($theme) && !empty($ids)) {
      foreach ($address_storage->loadMultiple($ids) as $address) {
        $address->theme->value = $theme;
        $address->save();
      }
    }

    return $theme;
    // TODO
//    ->loadByProperties([
//      'domain' => $host,
//    ]);
//    $rand_theme = $this->getThemeByStation($station);

//    if ($pos = strpos($domain, '*.') !== FALSE) {
//      // Yes,
//      if (preg_match('#' . substr($domain, $pos). '$#', $host)) {
//        return $this->getThemeByStation($station);
//      }
//      else {
//        return '';
//      }
//    }

//    if ($host == $domain) {
//      return $this->getThemeByStation($station);
//    }
//    else {
//      return '';
//    }
  }

  public function getSingleSiteMode($request, $domain, $station) {
    $host = $request->getHost();
    if ($host == $domain) {
      return $this->getThemeByStation($station);
    }
    return '';
  }

  public function getThemeByStation($station) {
    // get theme;
    $type = $station->model->entity->config_dir->value;

    // Get all avaiable themes.
    $themes = \Drupal::service('theme_handler')->rebuildThemeData();
    uasort($themes, 'system_sort_modules_by_info_name');

    $type_themes = [];
    foreach ($themes as $theme) {
      if (isset($theme->info['seo_theme']) && $theme->info['seo_theme'] == $type) {
        $type_themes[] = $theme->getName();
      }
    }

    $i = array_rand($type_themes, 1);
    return $type_themes[$i];
  }

  public function getThemeByRequest($request) {
    $stations = \Drupal::entityTypeManager()->getStorage('seo_station')->loadMultiple();
    $theme = '';
    foreach ($stations as $station) {
      $domains = array_unique(explode(',', str_replace("\r\n",",", $station->domain->value)));
      $domains = array_filter($domains);
      foreach ($domains as $domain) {
        // Station 是否是泛域名模式
        if (!empty($theme)) {
          break;
        }
        // 是
        if ($station->site_mode->value) {
          $theme = $this->getWildSiteMode($request, $domain, $station);
        }
        // 否
        else {
          $theme = $this->getSingleSiteMode($request, $domain, $station);
        }
      }
      if (empty($theme)) {
        continue;
      }
      else {
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

      if ($host == $neg->name->value) {
        return $neg->theme->value;
      }
      else {
        continue;
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
