<?php

namespace Drupal\seo_station\Controller;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Controller\ControllerBase;

class StationController extends ControllerBase {

  public function getExtract($number = 1) {
    $data = $this->getStationLinks($number);

    $build = [];
    $build['#theme'] = 'seo_station_extract';
    $build['#content'] = $data;
    $build['#cache']['tags'] = Cache::mergeTags(\Drupal::entityTypeManager()->getDefinition('seo_station')->getListCacheTags(), \Drupal::entityTypeManager()->getDefinition('seo_station_model_url')->getListCacheTags(), \Drupal::entityTypeManager()->getDefinition('seo_station_model')->getListCacheTags());

    return $build;
  }

  protected function getStationLinks($number = 1) {
    $stations = \Drupal::entityTypeManager()->getStorage('seo_station')->loadMultiple();

    $links = [];
    foreach ($stations as $station) {
      list($domains, $rule) = $this->getWildDomains($station, $number);
      $links = $this->getSingleDomainRule($domains, $rule);
    }

    $data = [];
    foreach ($links as $link) {
      $data[] = $this->generateLinks($link, $number);
    }

    return $data;
  }

  protected function generateLinks($link, $number) {
    if (empty($link)) {
      return [];
    }
    $data = [];

    for($i = 0; $i < $number; $i++) {
      $data[] = \Drupal::service('seo_station.token.manager')->generate([$link]);
    }

    return $data;
  }

  /**
   * 站点模式为单域名时
   */
  function getSingleDomainRule($domains, $rule) {
    $links = [];
    // 单域名时
    foreach ($domains as $domain) {
      foreach ($domain as $d) {
        if ($d === '0' || empty($d)) {
          continue;
        }
        $links[] = $_SERVER['REQUEST_SCHEME'] . '://' . $d . '/' . $rule;
      }
    }

    return $links;
  }

  // 获取当前系统域名列表,包括Station里面定义的域名，以及Station里面定义的所有泛域名列表.
  public function getDomainIndex() {
    // 1. 获取所有泛域名
    $stations = \Drupal::entityTypeManager()
      ->getStorage('seo_station')
      ->loadMultiple();
    $data = [];
    foreach ($stations as $station) {
      list($domains,) = $this->getWildDomains($station);
      $data = array_merge($data, $domains);
    }

    $build = [];
    $build['#theme'] = 'seo_station_domain_index';
    $build['#content'] = $data;

    return $build;
  }

  /**
   * @param $station
   * @param bool $wild true: 泛域名，false：单域名
   */
  public function getWildDomains($station, $number = 1) {
    $conditions = [
      'model' => $station->model->target_id,
      'type' => $station->url_mode->value == 0 ? 'dynamic' : 'static',
    ];
    // 这里待确定是否只用这一个模板。
    $template = [
      'template' => 'show',
    ];

    $conditions += $template;
    $url_rules = \Drupal::entityTypeManager()->getStorage('seo_station_model_url')->loadByProperties($conditions);
    $url_rules = reset($url_rules);


    // url 规则
    $rules = array_unique(explode(',', str_replace("\r\n",",", $url_rules->rule_url_content->value)));
    $rule = array_pop($rules);

    // 提取所有域名
    $domains = [];
    $wild = $station->site_mode->value;
    // 单域名时
    $domains[] = array_unique(explode(',', str_replace("\r\n",",", $station->domain->value)));
    if ($wild) {
      // 单域名改泛域名时
      $domains = \Drupal::service('seo_station.manager')->getMultiDomainByStation($domains, $station, $number);
    }

    return [$domains, $rule];
  }
}
