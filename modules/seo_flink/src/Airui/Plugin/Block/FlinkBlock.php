<?php

namespace Drupal\seo_flink\Airui\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\seo_flink\Entity\Flink;

class FlinkBlock extends BlockBase {

  /**
   * {@inheritDoc}
   */
  public function build(): array {
    $build = [];
    $flinks = \Drupal::entityTypeManager()->getStorage('seo_flink')->loadEnabledFlink();
    if (empty($flinks)) {
      $flinks[] = Flink::load(1);
    }

    $links = [];
    foreach ($flinks as $flink) {
      // Domain limit
      if (!$this->getUrlByDomain($flink)) {
        continue;
      }

      $urls = array_unique(explode(',', str_replace("\n",",", str_replace("\r\n",",", $flink->links->value))));
      if (empty($urls)) {
        continue;
      }
      $urls_link = $this->getUrlByEffectiveTime($urls);

      $number = $flink->number->value > count($urls_link) ? count($urls_link) : $flink->number->value;
      $keys = array_rand($urls_link, $number);
      foreach ($keys as $key) {
        $links[] = $urls_link[$key];
      }
    }

    $build['links'] = $links;
    return $build;
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheTags(): array {
    return Cache::mergeTags(parent::getCacheTags(),
      \Drupal::entityTypeManager()->getDefinition('seo_flink')->getListCacheTags()
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheContexts() {
    return ['url'];
  }

  protected function getUrlByEffectiveTime($urls): array {
    $urls_link = [];
    foreach ($urls as $url) {
      $d = explode('#', $url);
      if (count($d) < 2) {
        continue;
      }
      if (isset($d[2]) && strtotime($d[2]) < \Drupal::time()->getRequestTime()) {
        continue;
      }
      $urls_link[] = $d;
    }

    return $urls_link;
  }

  protected function getUrlByDomain($flink): bool {
    $domains = array_unique(explode(',', str_replace("\r\n",",", $flink->domain->value)));
    foreach ($domains as $domain) {
      // Fixed, domain未添加数据时，直接返回。
      if (empty($domain)) {
        return TRUE;
      }
      if (strpos($domain, '*') !== FALSE) {
        if (strpos(\Drupal::request()->getHost(), stristr($domain, '*')) !== FALSE) {
          return TRUE;
        }
        else {
          return FALSE;
        }
      }
      if (\Drupal::request()->getHost() == $domain) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    return TRUE;
  }
}
