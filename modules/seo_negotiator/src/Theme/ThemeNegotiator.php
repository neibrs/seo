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
    $theme = $full_path = '';
    // 1. full
    // 2. wild
    $full_path = $request->getHost();
    $full_path .= ':' . $request->getPort();
    $full_path .= $request->getPathInfo();
    $negotiators = $theme_negotiator_storage->loadByProperties([
      'name' => $full_path,
    ]);
    if (!empty($negotiators)) {
      $negotiator = reset($negotiators);
      return $negotiator->get('theme')->value;
    }

    //域名完全相等的情况
    $domain = $request->getHost();
    $negotiators = $theme_negotiator_storage->loadByProperties([
      'name' => $domain,
    ]);
    if (!empty($negotiators)) {
      $negotiator = reset($negotiators);
      return $negotiator->get('theme')->value;
    }

    // 下面是泛域名解析 wild domain.
    $host = $request->getHost();
    $host_arr = explode('.', $host);

    // 多级域名时，需要递归处理最接近的一个泛域名
    $count = count($host_arr);
    if($count < 3){
      return ''; // 如果数组小于3， 就当成没有模板处理， 返回空
    }
    for ($i = 1; $i < $count - 1; $i++){
      $sub_domains = array_slice($host_arr, $i);
      $wild_string = '*.' . implode('.', $sub_domains);

      $negotiators = $theme_negotiator_storage->loadByProperties([
        'name' => $wild_string,
      ]);
      if (!empty($negotiators)) {
        $negotiator = reset($negotiators);
        $theme = $negotiator->get('theme')->value;
        break;
      }
    }

    return $theme;
  }
}
