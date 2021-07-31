<?php

namespace Drupal\seo_station_address\EventSubscriber;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Cache\CacheableRedirectResponse;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Path\PathMatcherInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\seo_station_address\Event\StationAddressEvent;
use Drupal\serialization\Encoder\JsonEncoder as HALJsonEncoder;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 */
class StationAddressRedirectSubscriber implements EventSubscriberInterface {


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

  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onKernelRequest'];
    return $events;
  }

  public function onKernelRequest(RequestEvent $event) {
    // TODO, check local grant code.
    $x = 'a';
    // 1. 获取本地的机器码,生成机器码|config_sync目录名字，服务器IP信息
    $server_mac = \Drupal::service('seo_station_address.manager')->getMac();
    // send mac to api server, and get user register information: username, end_date, used_number, email
    $data = '';

    $authenticate_url = Url::fromRoute('seo_grant.authorize')
      ->setRouteParameter('_format', 'json')
      ->setAbsolute();
    $encoders = [new JsonEncoder(), new XmlEncoder(), new HALJsonEncoder()];
    $this->serializer = new Serializer([], $encoders);
    $request_body = [
      'server_mac' => $server_mac,
    ];
//    $result = \Drupal::httpClient()->post('http://192.168.1.69:8081/erel/authorize', [
////    $result = \Drupal::httpClient()->post($authenticate_url->toString(), [
//      'body' => $this->serializer->encode($request_body, 'json'),
//      'headers' => [
//        'Accept' => "application/json",
//      ],
//      'http_errors' => FALSE,
//      'cookies' => new CookieJar(),
//    ]);

//    $x = 'a';
    //    @trigger_error('Not registered', E_CORE_ERROR);

    // 2. post token到www.airuidata.com上验证授权
    // 2.1 根据服务器IP进行查找，返回所有相同服务器IP的数据
    // 3. 获取用户注册信息;包括授权截止日期

    $check = FALSE;
    if (!$check) {
//      $redirect_path = '/admin/seo_station_address/authorize/code';
//      $externalRedirect = UrlHelper::isExternal($redirect_path);
//      // Determine the url options.
//      $options = [
//        'absolute' => TRUE,
//      ];
//      $redirectEvent = new StationAddressEvent($redirect_path, $options);
//      $this->eventDispatcher->dispatch(StationAddressEvent::EVENT_NAME, $redirectEvent);
//
//      $response = new CacheableRedirectResponse($redirect_path, 301);
//      $event->setResponse($response);
    }

  }

}
