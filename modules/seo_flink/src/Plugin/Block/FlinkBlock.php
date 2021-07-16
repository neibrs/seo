<?php

namespace Drupal\seo_flink\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

/**
 * 提供站群友情链接.
 *
 * @Block(
 *   id = "flink_block",
 *   admin_label = @Translation("友情链接"),
 * )
 */
class FlinkBlock extends BlockBase {

  /**
   * {@inheritDoc}
   */
  public function build(): array {
    $build = [];
    $flinks = \Drupal::entityTypeManager()->getStorage('seo_flink')->loadEnabledFlink();

    $links = [];
    foreach ($flinks as $flink) {
      // Domain limit
      if (!$this->getUrlByDomain($flink)) {
        continue;
      }

      $urls = array_unique(explode(',', str_replace("\r\n",",", $flink->links->value)));
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
    // TODO ADD host
    return $this->getCacheContexts();
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
