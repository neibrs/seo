<?php

namespace Drupal\seoer\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\layout_builder\Section;
use Drupal\system\SystemManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SeoerController extends ControllerBase {

  /**
   * System Manager Service.
   *
   * @var \Drupal\system\SystemManager
   */
  protected $systemManager;

  public function __construct(SystemManager $systemManager) {
    $this->systemManager = $systemManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('system.manager')
    );
  }

  public function getWorkbench() {
    $section = new Section('layout_twocol_bricks');
    $build = $section->toRenderArray();
//    $build['middle']['system_info'] = \Drupal::service('plugin.manager.block')->createInstance('system_info_block')->build();

    $build['#theme'] = 'seoer_workbench';
    $build['#attached']['library'] = 'seoer/workbench';
    drupal_flush_all_caches();
    return $build;
  }

}
