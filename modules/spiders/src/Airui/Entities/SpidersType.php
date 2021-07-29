<?php

namespace Drupal\spiders\Airui\Entities;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\spiders\Entity\SpidersTypeInterface;

class SpidersType extends ConfigEntityBundleBase implements SpidersTypeInterface {

  /**
   * The Spiders type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Spiders type label.
   *
   * @var string
   */
  protected $label;

  /**
   * @var 存爬虫来源IP，即IP白名单.
   * TODO, 考虑单独存放白名单IP。
   */
  protected $source;

  /**
   * @var 储存User-Agent的数据，使用\r\n分隔
   */
  protected $user_agent;

  public function getSource() {
    return $this->source;
  }

  public function getUserAgent() {
    return $this->user_agent;
  }

  public function getUserAgentArray() {
    return array_filter(explode("\r\n", $this->user_agent), function ($item) {
      if (!empty($item)) {
        return $item;
      }
    });
  }

  public function getSourceArray() {
    return array_filter(explode("\r\n", $this->source), function ($item) {
      if (!empty($item)) {
        return $item;
      }
    });
  }
}
