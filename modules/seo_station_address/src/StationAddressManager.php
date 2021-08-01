<?php

namespace Drupal\seo_station_address;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class StationAddressManager implements StationAddressManagerInterface {
  /** @var \Drupal\Core\Entity\EntityTypeManagerInterface */
  protected $entityTypeManager;

  protected $stationAddressStorage;
  protected $mac_arr;
  /**
   * {@inheritDoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->stationAddressStorage = $entity_type_manager->getStorage('seo_station_address');
    $this->mac_arr = '';
  }

  public function getThemeNameByRequest($request) {
    $theme = '';
    // Add official to station address.
    $addresses = $this->stationAddressStorage->loadByProperties([
      'domain' => $request->getHost(),
    ]);
    if (empty($addresses)) {
      $values = [
        'name' => $request->getHost(),
        'domain' => $request->getHost(),
        'theme' => 'xbarrio', //\Drupal::service('theme.manager')->getActiveTheme()->getName(),
      ];
      $this->stationAddressStorage->create($values)->save();
    }
    else {
      $address = reset($addresses);
      $theme = $address->theme->value;
    }

    return $theme;
  }

  public function getThemeByDomain($request, $theme) {

  }

  public function updateThemeByDomain($request, $theme, $domain, $station) {
    $station_address_storage = \Drupal::entityTypeManager()->getStorage('seo_station_address');
    $station_addresses = $station_address_storage->loadByProperties([
      'domain' => $request->getHost(),
    ]);

    if (!empty($station_addresses)) {
      $station_address = reset($station_addresses);
      $theme = $station_address->theme->value;
    }
    if (empty($theme)) {
      $theme = $this->getThemeByStationModel($request, $theme, $domain, $station);
    }
    if (!empty($theme) && empty($station_addresses)) {
      $web_name = \Drupal::service('seo_textdata.manager')->getWebNameByTextdata($station);
      $web_name = trim(strip_tags($web_name));
      // 固化主题到域名上
        $values = [
          'name' => $request->getHost(),
          'domain' => $request->getHost(),
          'theme' => $theme,
          'webname' => $web_name,
      ];
      $station_address_storage->create($values)->save();

      $arr = explode('.', $request->getHost());
      if (count($arr) == 2) {
        // 固化主题到域名上
        $values = [
          'name' => 'www.' . $request->getHost(),
          'domain' => 'www.' . $request->getHost(),
          'webname' => $web_name,
          'theme' => $theme,
        ];
        $station_address_storage->create($values)->save();
      }
      $site_config = \Drupal::configFactory()->getEditable('system.site');
      $site_config->set('name', $web_name)->save();
    }

    return $theme;
  }


  public function getThemeByStationModel($request, $theme, $domain, $station) {
    // Station 是否是泛域名模式
    if (!empty($theme)) {
      return $theme;
    }
    // 是
    if ($station->site_mode->value) {
      $theme = $this->getWildSiteMode($request, $domain, $station);
    }
    // 否
    else {
      $theme = $this->getSingleSiteMode($request, $domain, $station);
    }

    return $theme;
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


  public function getMac() {
    switch (strtolower(PHP_OS)) {
      case "solaris" :
      case "unix" :
      case "aix" :
      case "linux" :
        $this->forLinux ();
        break;
      default :
        $this->forWindows ();
        break;
    }

    $temp_array = array ();
    foreach ( $this->mac_arr as $value ) {
      if (preg_match ( "/[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f]/i", $value, $temp_array )) {
        $this->mac_addr = $temp_array[0];
        break;
      }
    }
    unset ($temp_array);
    return @$this->mac_addr;
  }
  protected function forLinux() {
    try {
      @exec ( "ifconfig", $this->mac_arr );
      return $this->mac_arr;
    }
    catch (\Exception $exception) {
      //
    }
  }

  protected function forWindows() {
    @exec ( "ipconfig /all", $this->mac_arr );
    if ($this->mac_arr)
      return $this->mac_arr;
    else {
      if (isset($_SERVER ["WINDIR"])) {
        $ipconfig = @$_SERVER ["WINDIR"] . "/system32/ipconfig.exe";
        if (is_file ( $ipconfig ))
          @exec ( $ipconfig . " /all", $this->mac_arr );
        else
          @exec ( $_SERVER ["WINDIR"] . "/system/ipconfig.exe /all", $this->mac_arr );
      }
      return $this->mac_arr;
    }
  }
}
