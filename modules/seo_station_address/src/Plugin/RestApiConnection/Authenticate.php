<?php
namespace Drupal\seo_station_address\Plugin\RestApiConnection;

use Drupal\api_connection\Plugin\RestApiConnectionBase;
use Drupal\Component\Serialization\Json;
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

  public function authentication($data): bool {
    $server_mac = \Drupal::service('seo_station_address.manager')->getMac();
    $options = [
      RequestOptions::DEBUG => TRUE,
      RequestOptions::AUTH => [
        'name' => 'admin',
        'pass' => 'admin',
      ],
      RequestOptions::HEADERS => Json::encode([
        'Content-Type' => 'application/hal+json',
        'X-CSRF-Token' => 'token',
        'Authorization' => 'Basic ' + '',
      ]),
      RequestOptions::JSON=> Json::encode([
        'server_mac' => $server_mac,
      ] + $data),
    ];

    $response = $this->sendRequest('api/authentication', "POST", $options);


    return FALSE;
  }



}
