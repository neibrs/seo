<?php

namespace Drupal\seo_station_address\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
/**
 */
class CheckRequest implements EventSubscriberInterface {


  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onKernelRequest'];
    return $events;
  }

  public function onKernelRequest(RequestEvent $event) {
    // TODO, check local grant code.
    $x = 'a';
    // 1. 获取本地的机器码,生成机器码|config_sync目录名字，服务器IP信息
    // 2. post token到www.airuidata.com上验证授权
    // 2.1 根据服务器IP进行查找，返回所有相同服务器IP的数据
    // 3. 获取用户注册信息;包括授权截止日期
  }

}