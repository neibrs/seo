<?php

namespace Drupal\seo_station_address\EventSubscriber;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\CacheableRedirectResponse;
use Drupal\Core\Url;
use Drupal\r4032login\Event\RedirectEvent;
use Drupal\seo_station_address\Event\StationAddressEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 */
class StationAddressRedirectSubscriber implements EventSubscriberInterface {


  /**
   * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
   *
   * @return \Symfony\Component\HttpKernel\Event\ResponseEvent
   */
  public function onRespond(ResponseEvent $event) {
    if (\Drupal::currentUser()->isAnonymous()) {
      return $event;
    }
    $status = \Drupal::state()->get('authorize_token_key');
    if ($status) {
      return $event;
    }
    $routes = [
      'entity.seo_station.collection',
    ];
    if (in_array(\Drupal::routeMatch()->getRouteName(), $routes)) {
      $request = $event->getRequest();
      $currentPath = $request->getPathInfo();
      $destination = substr($currentPath, 1);
      if ($queryString = $request->getQueryString()) {
        $destination .= '?' . $queryString;
      }
      $redirectPath = '/admin/seo_station_address/authorize/code';
//      // Remove the destination parameter to allow redirection.
//      $request->query->remove('destination');
//      // Allow to alter the url or options before to redirect.
//      $redirectEvent = new StationAddressEvent($redirectPath, []);
//      \Drupal::service('event_dispatcher')->dispatch(StationAddressEvent::EVENT_NAME, $redirectEvent);
//      $redirectPath = $redirectEvent->getUrl();
//      $options = $redirectEvent->getOptions();
//      $url = Url::fromUserInput($redirectPath, $options)->toString();
      $response = new CacheableRedirectResponse($redirectPath, 302);
      // Add caching dependencies so the cache of the redirection will be
      // updated when necessary.
      $event->setResponse($response);
    }

  }
  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onRespond', 100];
    return $events;
  }
}
