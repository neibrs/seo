<?php
namespace Drupal\seo_statioin_address\Plugin\RestApiConnection;

use Drupal\api_connection\Plugin\RestApiConnectionBase;
use GuzzleHttp\RequestOptions;

/**
 * REST API connection for the ReqRes example service.
 *
 * @RestApiConnection(
 *   id = "airui_authenticate",
 *   label = @Translation("Airui Authentication"),
 *   urls = {
 *     "dev" = "http://192.168.1.69:8081",
 *     "test" = "http://192.168.1.69:8081",
 *     "live" = "http://192.168.1.69:8081"
 *   }
 * )
 */
class Authenticate extends RestApiConnectionBase {

  public function authentication() {
    $server_mac = \Drupal::service('seo_station_address.manager')->getMac();
    $options = [
      RequestOptions::BODY => [
        'server_mac' => $server_mac,
      ],
    ];

    $response = $this->sendRequest('authenticate', "POST", $options);

    return FALSE;
  }



}
