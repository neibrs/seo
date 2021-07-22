<?php

namespace Drupal\spiders;

use Drupal\spiders\Entity\Spiders;
use Drupal\spiders\Entity\SpidersType;

class SpidersUserAgentManager implements SpidersUserAgengMangerInterface {

  public function determineSpider($request) {
    $user_agent = $request->headers->get('user_agent');
    $browser_agent = 'other';
    $spiders = $this->getSpider();
    foreach ($spiders as $spider) {
      if (isset($spider['user_agent'])) {
        $user_agents = $spider['user_agent'];
        foreach ($user_agents as $agent) {
          if ($user_agent == $agent) {
            $browser_agent = $spider;
            break;
          }
          if ($result = stripos($user_agent, $agent)) {
            if ($result !== FALSE) {
              $browser_agent = $spider;
              break;
            }
          }
        }
      }
      if (!empty($browser_agent)) {
        break;
      }
      if (isset($spider['ip']) && empty($browser_agent)) {
        $user_agents_ips = $spider['ip'];
        foreach ($user_agents_ips as $ip) {
          if ($ip == $request->server->get('REMOTE_ADDR')) {
            $browser_agent = $spider;
            break;
          }
        }
      }
    }
    $this->insertSpider($request, $browser_agent);
  }

  public function insertSpider($request, $browser_agent) {
    $address = $request->server->get('REQUEST_SCHEME') . ':' . '//' . $request->server->get('SERVER_NAME');
    if ($request->server->get('SERVER_PORT') != 80) {
      $address .= ':' . $request->server->get('SERVER_PORT');
    }
    $address .= $request->getPathInfo();
    $spiders_type = SpidersType::load($browser_agent);

    // Check station address replacement.
    $storage =  \Drupal::entityTypeManager()->getStorage('seo_station_address');
    $query = $storage->getQuery();
    $query->condition('replacement', $request->getPathInfo());
    $query->condition('domain', $request->server->get('SERVER_NAME'));
    $ids = $query->execute();
    $station = NULL;
    if (!empty($ids)) {
      $id = reset($ids);
      $station = $storage->load($id);
    }

    $values = [
      'name' => !empty($spiders_type) ? $spiders_type->label() : '',
      'type' => $browser_agent,
      'ip' => $request->server->get('REMOTE_ADDR'),
      'address' => $address,
      'model' => !empty($station) ? $station->model->entity->label() : NULL, //新闻，企业
      'user_agent' => $request->headers->get('user_agent'),
      'access' => REQUEST_TIME,
    ];
    $spider = Spiders::create($values)
      ->save();
    $x = 'a';
  }

  public function getSpider() {
    $spiders = \Drupal::entityTypeManager()->getStorage('spiders_type')->loadMultiple();
    $spiders = array_map(function ($spider) {
      return [
        'user_agent' => $spider->getUserAgentArray(),
        'source' => $spider->getSourceArray(),
      ];
    }, $spiders);
//    $spiders = [
//      'google' => [
//        'user_agent' => [
//          'Googlebot-Image/1.0',
//          'Googlebot/2.1',
//          'Feedfetcher-Google',
//        ],
//        'ip' => [
//          '66.249.70.212',
//          '209.85.238.7',
//        ],
//      ],
//      'baidu' => [
//        'user_agent' => [
//          'Baiduspider+',
//        ],
//        'ip' => [
//          '60.28.22.38',
//        ],
//      ],
//      'baidu_xuanran' => [],
//      'youdao' => [],
//      'yahoo' => [],
//      'toutiao' => [],
//      'spider_360' => [],
//      'sousou' => [],
//      'sousou_image' => [],
//      'souhu' => [],
//      'sougou' => [],
//      'shenma' => [],
//      'lycos' => [],
//      'jpspider' => [],
//      'inktomi' => [],
//      'iask' => [],
//      'haosou' => [],
//      'gigablast' => [],
//      'exabot' => [],
//      'bing' => [],
//      'aol' => [],
//      'altavista' => [],
//      'alltheweb' => [],
//      'alexa' => [],
//    ];

    $spiders = array_filter($spiders, function ($item) {
      if (!empty($item['user_agent']) || !empty($item['source'])) {
        return $item;
      }
    });
    return $spiders;
  }
}
