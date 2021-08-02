<?php
namespace Drupal\seo_station_address\Airui\Plugin\RestApiConnection;

use Drupal\Component\Serialization\Json;
use Drupal\seo_station_address\Airui\Plugin\RestApiConnectionBase;
use GuzzleHttp\RequestOptions;

class Authenticate extends RestApiConnectionBase {

  public function authentication($data)
  {
    $state = \Drupal::state()->get('user_platform_login_info');
    $server_mac = \Drupal::service('seo_station_address.manager')->getMac();
    if (empty($server_mac)) {
      \Drupal::messenger()->addError('系统无法获取机器码, 请先确保可执行ifconfig');
      return FALSE;
    }
//    if (!$state) {
    // Login
    $options = [
      RequestOptions::BODY => $data,
    ];

    $response_data = $response = $this->sendRequest('user/login?_format=json', "POST", $options);
    if (empty($response)) {
      return FALSE;
    }
    $response_status = 'abc';
    $xx = $server_mac . $response_status;
    // 加一个定时清理的任务.
    \Drupal::state()->set('user_platform_login_info', md5($xx));
//  }
    try {
      $state = $response_data;//\Drupal::state()->get('user_platform_login_info');
      // Login
      $options = [
        RequestOptions::BODY => $data,
        RequestOptions::QUERY => [
          'token' => $state['csrf_token'],
          '_format' => 'json',
          'uid' => $state['current_user']['uid'],
          'mac' => $server_mac,
          'payload' => $server_mac,
        ],
        RequestOptions::HEADERS => [
          'Content-Type' => 'application/json',
//          'X-Csrf-Token' => $state['csrf_token'],
          'Authorization' => 'Basic ' . base64_encode($data['name'] . ':' . $data['pass']),
//          'Authentication' => base64_encode($data['name'] . ':' . $data['pass']),
        ],
      ];

      $response = $this->sendRequest('api/authentication', 'POST', $options);

      $options = [
        RequestOptions::QUERY => [
          'token' => $state['logout_token'],
        ],
      ];

      // Logout success.
      $this->sendRequest('user/logout?_format=json', "POST", $options);

      \Drupal::state()->delete('user_platform_login_info');

      $items = [];
      if (is_string($response)) {
        $items = Json::decode($response);
      }
      $mac_string = $server_mac . \Drupal::state()->get('authenticate_username');
      if (isset($items['status']) && $items['status'] !== md5($mac_string)) {
        return FALSE;
      }
      if (isset($data['date']) && strtotime() >= $data['date']) {
        return FALSE;
      }
      \Drupal::state()->set('authorize_token_key', 1);
      return TRUE;
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError($e->getMessage());
    }

    return FALSE;
  }

}
