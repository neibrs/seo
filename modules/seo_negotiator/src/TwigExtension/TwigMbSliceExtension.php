<?php

namespace Drupal\seo_negotiator\TwigExtension;

use Twig\Extension\AbstractExtension;

class TwigMbSliceExtension extends AbstractExtension {

  public function getName() {
    return 'twig_mb_slice_extension.twig_extension';
  }

  public function getFilters() {
    return [
      new \Twig_SimpleFilter('mb_slice', [$this, 'mbSlice']),
    ];
  }

  public function mbSlice($context) {
    $args = func_get_args();
    unset($args[0]);
    if (empty($args[1])) {
      $args[1] = 0;
    }
    if (empty($args[2])) {
      $args[2] = 4;
    }
    return mb_substr(mb_ereg_replace('^(　| )+','', strip_tags($context)), $args[1], $args[2]);
  }
}
