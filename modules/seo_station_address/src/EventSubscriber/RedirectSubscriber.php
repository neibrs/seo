<?php

namespace Drupal\seo_station_address\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\EventSubscriber\HttpExceptionSubscriberBase;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Path\PathMatcherInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 */
class RedirectSubscriber extends HttpExceptionSubscriberBase {

  protected $mac_arr;

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The path matcher.
   *
   * @var \Drupal\Core\Path\PathMatcherInterface
   */
  protected $pathMatcher;

  /**
   * An event dispatcher instance to use for map events.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a new R4032LoginSubscriber.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Path\PathMatcherInterface $path_matcher
   *   The path matcher.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, AccountInterface $current_user, PathMatcherInterface $path_matcher, EventDispatcherInterface $event_dispatcher, MessengerInterface $messenger) {
    $this->configFactory = $config_factory;
    $this->currentUser = $current_user;
    $this->pathMatcher = $path_matcher;
    $this->eventDispatcher = $event_dispatcher;
    $this->messenger = $messenger;
  }

//  public static function getSubscribedEvents() {
//    $events[KernelEvents::REQUEST][] = ['onKernelRequest'];
//    return $events;
//  }
//
//  public function onKernelRequest(RequestEvent $event) {
//    // TODO, check local grant code.
//    $x = 'a';
//    // 1. 获取本地的机器码,生成机器码|config_sync目录名字，服务器IP信息
//    $server_mac = $this->getMac();
//    // send mac to api server, and get user register information: username, end_date, used_number, email
//    $data = '';
////    @trigger_error('Not registered', E_CORE_ERROR);
//
//    // 2. post token到www.airuidata.com上验证授权
//    // 2.1 根据服务器IP进行查找，返回所有相同服务器IP的数据
//    // 3. 获取用户注册信息;包括授权截止日期
//  }

  public function on403(GetResponseForExceptionEvent $event) {
    $x = 'a';
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

  protected function getHandledFormats() {
    return ['html'];
  }

}
