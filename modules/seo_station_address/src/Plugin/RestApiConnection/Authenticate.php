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
    $state = \Drupal::state()->get('user_authentication_info');
    \Drupal::state()->delete('user_authentication_info');
    $server_mac = \Drupal::service('seo_station_address.manager')->getMac();
    if (!$state) {
      // Login
      $options = [
        RequestOptions::BODY => $data,
      ];

      $response = $this->sendRequest('user/login?_format=json', "POST", $options);
      if(empty($response)) {
        return FALSE;
      }
      // 加一个定时清理的任务.
      \Drupal::state()->set('user_authentication_info', $response);
    }
    $state = \Drupal::state()->get('user_authentication_info');
    // Login
    $options = [
      RequestOptions::BODY => $data,
      RequestOptions::QUERY => [
        'token' => $state['csrf_token'],
      ],
      RequestOptions::HEADERS => [
        'Content-Type' => 'application/json',
        'X-Csrf-Token' => $state['csrf_token'],
        'Authentication' => base64_encode($data['name'] . ':' . $data['pass']),
      ],
    ];

    $response = $this->sendRequest('erel/authorize?_format=json&xxx=ldf', "POST", $options);
    if(empty($response)) {
      return FALSE;
    }
//    $options = [
//      RequestOptions::BODY => [
//        'payload' => 'body',
//      ],
//      RequestOptions::FORM_PARAMS => [
//        'payload' => 'xxx',
//      ],
//      RequestOptions::DEBUG => TRUE,
//      RequestOptions::AUTH => $data,
//      RequestOptions::QUERY => [
//        'payload' => 'xxx',
//      ],
//      RequestOptions::JSON => [
//        'xx' => 'x',
//      ],
//      RequestOptions::HEADERS => [
//        'Content-Type' => 'application/json',
//        'X-CSRFToken' => 'oVaAKU02ChPj293AeQlr2rUiP0N6rGPVcctNcx3TMFM',//$state['csrf_token'],
//      ],
//    ];
//    $response = $this->sendRequest('api/authentication?_format=json', "POST", $options);
    $x = 'a';

    $options = [
      RequestOptions::QUERY => [
        'token' => $state['logout_token'],
      ],
    ];

    // Logout success.
    $response = $this->sendRequest('user/logout?_format=json', "POST", $options);
    if ($response) {
      return TRUE;
    }
    $state = \Drupal::state()->delete('user_authentication_info');

    return FALSE;
  }



}
