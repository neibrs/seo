<?php
namespace Drupal\seo_station_address\Airui\Plugin\RestApiConnection;

use Drupal\Component\Serialization\Json;
use Drupal\seo_station_address\Airui\Plugin\RestApiConnectionBase;
use GuzzleHttp\RequestOptions;

class Authenticate extends RestApiConnectionBase {

  public function authentication($data): bool {
    $state = \Drupal::state()->get('user_authentication_info');
    \Drupal::state()->delete('user_authentication_info');
    $server_mac = \Drupal::service('seo_station_address.manager')->getMac();
    if (empty($server_mac)) {
      \Drupal::messenger()->addError('系统无法获取机器码');
      return FALSE;
    }
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
    try {
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

      $response = $this->sendRequest('erel/authorize?_format=json&uid=' . $state['current_user']['uid'] . '&mac=' . $server_mac, "POST", $options);
      if(empty($response)) {
        return FALSE;
      }


      // 数组结构

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
      \Drupal::state()->delete('user_authentication_info');

      $data = Json::decode($response);
      $string = md5($server_mac . $data['name']);
      if ($string === $data['status']) {
        if (strtotime() < $data['date']) {
          return TRUE;
        }
        else {
          return FALSE;
        }
      }
      else {
        return FALSE;
      }

    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError($e->getMessage());
    }

    return FALSE;
  }

}
