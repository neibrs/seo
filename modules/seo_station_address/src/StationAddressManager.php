<?php

namespace Drupal\seo_station_address;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class StationAddressManager implements StationAddressManagerInterface {
  /** @var \Drupal\Core\Entity\EntityTypeManagerInterface */
  protected $entityTypeManager;

  protected $stationAddressStorage;
  /**
   * {@inheritDoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->stationAddressStorage = $entity_type_manager->getStorage('seo_station_address');
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
}
