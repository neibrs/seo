<?php

namespace Drupal\seo_station_address\EventSubscriber;

use Drupal\Core\Site\Settings;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
/**
 */
class CheckRequest implements EventSubscriberInterface {

  protected $mac_arr;

  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onKernelRequest'];
    return $events;
  }

  public function onKernelRequest(RequestEvent $event) {
    // TODO, check local grant code.
    $x = 'a';
    // 1. 获取本地的机器码,生成机器码|config_sync目录名字，服务器IP信息
    $server_mac = $this->getMac();
    // send mac to api server, and get user register information: username, end_date, used_number, email
    $data = '';

    // 2. post token到www.airuidata.com上验证授权
    // 2.1 根据服务器IP进行查找，返回所有相同服务器IP的数据
    // 3. 获取用户注册信息;包括授权截止日期
  }

  protected function getMac() {
    switch (strtolower(PHP_OS)) {
      case "solaris" :
      case "unix" :
      case "aix" :
      case "linux" :
        $this->forLinux ();
        break;
      default :
        $this->forWindows ();
        break;
    }

    $temp_array = array ();
    foreach ( $this->mac_arr as $value ) {
      if (preg_match ( "/[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f]/i", $value, $temp_array )) {
        $this->mac_addr = $temp_array[0];
        break;
      }
    }
    unset ( $temp_array );
    return $this->mac_addr;
  }
  protected function forLinux() {
    @exec ( "ifconfig", $this->mac_arr );
    return $this->mac_arr;
  }

  protected function forWindows() {
    @exec ( "ipconfig /all", $this->mac_arr );
    if ($this->mac_arr)
      return $this->mac_arr;
    else {
      $ipconfig = $_SERVER ["WINDIR"] . "/system32/ipconfig.exe";
      if (is_file ( $ipconfig ))
        @exec ( $ipconfig . " /all", $this->mac_arr );
      else
        @exec ( $_SERVER ["WINDIR"] . "/system/ipconfig.exe /all", $this->mac_arr );
      return $this->mac_arr;
    }
  }
}
