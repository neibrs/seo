<?php

namespace Drupal\seo_negotiator;

class NegotiatorManager extends NegotiatorManagerInterface {

  public function getDynamicDomains() {
    $addresses = \Drupal::entityTypeManager()->getStorage('seo_station_address')->loadMultiple();
    $domains = array_map(function ($address) {
      // no https:// or http://
      $ads = explode('/', $address->label());
      return $ads[0];
    }, $addresses);

    $domains[] = \Drupal::request()->getHost();
    return array_combine($domains, $domains);
  }
}
