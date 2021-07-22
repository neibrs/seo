<?php

namespace Drupal\spiders;

class SpidersUserAgentManager implements SpidersUserAgengMangerInterface {

  public function determineSpider($request) {
    $x = 'a';
    $user_agent = $request->headers->get('user_agent');

    $browser_agent = '';
    foreach ($this->getSpider() as $spider) {
      if (isset($spider['user_agent'])) {
        $user_agents = $spider['user_agent'];
        foreach ($user_agents as $agent) {
          $result = stripos($user_agent, $agent);
          if ($result !== FALSE) {
            $browser_agent = $spider;
            break;
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

      $browser_agent = 'other';
    }
  }

  public function getSpider() {
    $spiders = [
      'google' => [
        'user_agent' => [
          'Googlebot-Image/1.0',
          'Googlebot/2.1',
          'Feedfetcher-Google',
        ],
        'ip' => [
          '66.249.70.212',
          '209.85.238.7',
        ],
      ],
      'baidu' => [
        'user_agent' => [
          'Baiduspider+',
        ],
        'ip' => [
          '60.28.22.38',
        ],
      ],
      'baidu_xuanran' => [],
      'youdao' => [],
      'yahoo' => [],
      'toutiao' => [],
      'spider_360' => [],
      'sousou' => [],
      'sousou_image' => [],
      'souhu' => [],
      'sougou' => [],
      'shenma' => [],
      'lycos' => [],
      'jpspider' => [],
      'inktomi' => [],
      'iask' => [],
      'haosou' => [],
      'gigablast' => [],
      'exabot' => [],
      'bing' => [],
      'aol' => [],
      'altavista' => [],
      'alltheweb' => [],
      'alexa' => [],
    ];

    return $spiders;
  }
}
