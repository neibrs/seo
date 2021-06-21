<?php

namespace Drupal\seo_station;

interface TokenManagerInterface {

  /**
   * 替换link里面的占位符.
   * @param $link
   *
   * @return mixed
   */
  public function generate($link);

}
