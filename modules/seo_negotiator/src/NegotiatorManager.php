<?php

namespace Drupal\seo_negotiator;

class NegotiatorManager extends NegotiatorManagerInterface {

  public function getDynamicDomains() {
    $addresses = \Drupal::entityTypeManager()->getStorage('seo_station_address')->loadMultiple();
    $domains = array_map(function ($address) {
      $domain = parse_url($address->name->value);
      return $domain['host'];
    }, $addresses);

    $domains[] = \Drupal::request()->getHttpHost();
    return array_combine($domains, $domains);
  }
}
