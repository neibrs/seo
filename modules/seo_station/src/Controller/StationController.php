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
    $build['#cache']['max-age'] = 0;

    return $build;
  }

  protected function getStationLinks($number = 1) {
    $stations = \Drupal::entityTypeManager()->getStorage('seo_station')->loadMultiple();

    $data = [];
    foreach ($stations as $station) {
      [$domains, $rule] = $this->getWildDomains($station, $number);
      $data[$station->id()] = $this->getSingleDomainRule($station, $domains, $rule, $number);
    }

    return $data;
  }

  /**
   * 站点模式为单域名时
   *
   * @param string $station
   * @param array $domains
   * @param string $rule
   * @param int $number
   *
   * @return array
   */
  function getSingleDomainRule($station = '', $domains = [], $rule = '', $number = 1) {
    $links = [];
    // 单域名时
    foreach ($domains as $domain) {
      foreach ($domain as $d) {
        if ($d === '0' || empty($d)) {
          continue;
        }

        $replacements = [];
        for($i = 0; $i < $number; $i++) {
          $real_data = \Drupal::service('seo_station.token.manager')->generate([$rule]);
          $replacements[] = reset($real_data);
        }
        foreach ($replacements as $replacement) {
          $links[] = $_SERVER['REQUEST_SCHEME'] . '://' . $d . '/' . $replacement;
        }

        // 生成真实的链接数据，并加入相应的队列.
        // 插入队列.
        $arr = [
          'station' => $station->id(),
          'domain' => $d,
        ];
        foreach ($replacements as $replacement) {
          $new_arr = $arr + ['replacement' => $replacement];
          \Drupal::moduleHandler()->alter('link_rule_data', $new_arr);
        }
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
      [$domains,] = $this->getWildDomains($station);
      if (!empty($domains)) {
        $data = array_merge($data, $domains);
      }
    }

    $build = [];
    $build['#theme'] = 'seo_station_domain_index';
    $build['#content'] = $data;

    return $build;
  }

  /**
   * @param $station
   * @param int $number
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
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

    if (empty($url_rules) || !$url_rules) {
      \Drupal::messenger()->addError('站群URL规则没有设置');
      return [];
    }

    // url 规则
    // 处理\r\n
    $rules = array_unique(explode(',', str_replace("\r\n",",", $url_rules->rule_url_content->value)));

    $rule = array_pop($rules);

    // 处理\n
    $rules = array_unique(explode(',', str_replace("\n",",", $rule)));
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

  /**
   * Execute cron for generate node data.
   */
  public function executeCron() {
    try {
      $cron_service = \Drupal::service('cron');
      $cron_service->run();
    }
    catch (\Exception $e) {
    }

    return ['#markup' => '成功生成数据'];
  }
}
