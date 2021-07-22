<?php

namespace Drupal\spiders\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SpiderRequest implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onKernelRequest'];
    return $events;
  }

  public function onKernelRequest(RequestEvent $event) {
    \Drupal::service('spiders.user_agent')->determineSpider(\Drupal::request());
  }
}
