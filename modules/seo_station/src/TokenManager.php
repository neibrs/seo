<?php

namespace Drupal\seo_station;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class TokenManager implements TokenManagerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;
  /**
   * StationManager constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  // 用来生成以下链接样式的真实node数据.
  //http://0eshi.com/show/'{id}_'{aid}.html
  public function generate($link) {
    $tokens = $this->getToken();

    $link = $this->replaceToken($link, $tokens);

    \Drupal::moduleHandler()->alter('link_rule_data', $link);
    return $link;
  }

  protected function replaceToken($link, $tokens) {
    foreach ($tokens as $token) {
      switch ($token) {
        case '{数字1}':
        case '{数字}':
        case '{aid}':
        case '{id}':
        case '{数字2}':
        case '{数字3}':
        case '{数字4}':
        case '{数字5}':
        case '{数字6}':
        case '{数字7}':
        case '{数字8}':
        case '{数字1-8}':
          $link = $this->replace1_8($link, $token);
          break;
        case '字母':
        case '大写字母':
        case '大小写字母':
        case '大写字母数字':
        case '大小写字母数字':
        case '数字字母':
        case '随机字母':
          $link = $this->replaceAlphaNumber($link, $token);
          break;
      }
    }
    return $link;
  }

  public function getToken() {
    $token = [
      '{数字}',
      '{字母}','{大写字母}','{大小写字母}',
      '{大写字母数字}','{大小写字母数字}','{数字字母}',
      '{日期}','{年}','{月}','{日}',
      '{时}','{分}','{秒}',
      '{随机字符}',
      '{数字1-8}',
      '{数字1}',
      '{数字2}',
      '{数字3}',
      '{数字4}',
      '{数字5}',
      '{数字6}',
      '{数字7}',
      '{数字8}',
      '{id}',
      '{aid}',
    ];

    return $token;
  }

  protected function replace1_8($link, $token) {
    switch ($token) {
      case '{数字1}':
      case '{数字}':
      case '{aid}':
      case '{id}':
        $link = str_replace($token, rand(1,9), $link);
        break;
      case '{数字2}':
        $link = str_replace($token, rand(10,99), $link);
        break;
      case '{数字3}':
        $link = str_replace($token, rand(100,999), $link);
        break;
      case '{数字4}':
        $link = str_replace($token, rand(1000,9999), $link);
        break;
      case '{数字5}':
        $link = str_replace($token, rand(10000,99999), $link);
        break;
      case '{数字6}':
        $link = str_replace($token, rand(100000,999999), $link);
        break;
      case '{数字7}':
        $link = str_replace($token, rand(1000000,9999999), $link);
        break;
      case '{数字8}':
        $link = str_replace($token, rand(1000000,99999999), $link);
        break;
      case '{数字1-8}':
        $link = str_replace($token, rand(1,99999999), $link);
        break;
    }
    return $link;
  }

  protected function replaceAlphaNumber($link, $token) {
    $alpha = [
      'a','b','c','d','e','f','g','h','i','j','k','l','m','n',
      'o','p','q','r','s','t','u','v','w','x','y','z',
    ];
    $cap = [
      'A','B','C','D','E','F','G','H','I','J','K','L','M','N',
      'O','P','Q','R','S','T','U','V','W','X','Y','Z',
    ];
    $num = [
      '0','1','2','3','4','5','6','7','8','9',
    ];
    //字母
    $als = array_merge($alpha, $cap);
    // 所有
    $all = array_merge($alpha, $cap, $num);
    //大写字母数字
    $cap_num = array_merge($cap, $num);
    switch ($token) {
      case '字母':
      case '随机字母':
      case '大小写字母':
      case '数字字母':
        $link = str_replace($token, $als[rand(1,count($als))], $link);
        break;
      case '大写字母':
        $link = str_replace($token, $cap[rand(1,count($cap))], $link);
        break;
      case '大写字母数字':
        $link = str_replace($token, $cap_num[rand(1,count($cap_num))], $link);
        break;
      case '大小写字母数字':
        $link = str_replace($token, $all[rand(1,count($all))], $link);
        break;
    }
    return $link;
  }


}
