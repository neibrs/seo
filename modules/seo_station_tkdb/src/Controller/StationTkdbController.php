<?php
namespace Drupal\seo_station_tkdb\Controller;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class StationTkdbController extends ControllerBase {

  /**
   * Tkdb设置页面
   */
  public function getOverview() {
    $build = [];
    $build['#content']['tips'] = [
      '#type' => 'item',
      '#markup' => "<p><b>以下为《模型》的默认TKDB调用模板（<span class='description-red'>注：如果该模型未设置配置，则使用默认的调用配置</span>）</b></p>",
    ];

    $models = \Drupal::entityTypeManager()->getStorage('seo_station_model')->loadMultiple();
    $models = array_map(function ($model) {
      $keywords = [
        'model' => $model->id(),
      ];
      $ids = \Drupal::service('seo_station_tkdb.manager')->getTkdb($keywords);
      return [
        'name' => $model->label(),
        'config_dir' => $model->config_dir->value,
        'is_empty' => empty($ids),
        'link_empty' => empty($ids) ? Link::createFromRoute('未设置，点击设置', 'tkdb.model_link.edit_form', [
          'seo_station_model' => $model->id(),
        ], [
          'query' => \Drupal::destination()->getAsArray(),
          'attributes' => [
            'class' => ['use-ajax', 'action-link action-link--icon-checkmark action-link--small text-color-green'],
            'data-dialog-type' => 'modal',
            'data-dialog-options' => '{"width": "80%"}',
          ],
        ])->toString() : NULL,
        'edit' => Link::createFromRoute('修改', 'tkdb.model_link.edit_form', [
          'seo_station_model' => $model->id(),
        ], [
          'query' => \Drupal::destination()->getAsArray(),
          'attributes' => [
            'class' => ['use-ajax', 'action-link action-link--icon-checkmark action-link--small text-color-green'],
            'data-dialog-type' => 'modal',
            'data-dialog-options' => '{"width": "80%"}',
          ],
        ])->toString(),
        'delete' => Link::createFromRoute('清除', 'tkdb.model_link.delete_form', [
          'seo_station_model' => $model->id(),
        ], [
          'query' => \Drupal::destination()->getAsArray(),
          'attributes' => [
            'class' => ['use-ajax','action-link action-link--icon-checkmark action-link--small text-color-red'],
            'data-dialog-type' => 'modal',
            'data-dialog-options' => '{"width": "80%"}',
          ]
        ]),
      ];
    }, $models);

    // Default
    $default[] = [
      'name' => '默认配置',
      'config_dir' => 'default',
      'edit' => Link::createFromRoute('点击修改', 'tkdb.model_link.edit_form', [
        'seo_station_model' => 1,//$model->id(),
      ], [
        'query' => \Drupal::destination()->getAsArray(),
        'attributes' => [
          'class' => ['use-ajax', 'action-link action-link--icon-checkmark action-link--small text-color-yellow'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => '{"width": "80%"}',
        ],
      ])->toString(),
    ];
    $build['#content']['models'] = $default + $models;

    $build['#content']['second'] = [
      '#type' => 'item',
      '#markup' => "<p><b>以下为《网站分组》的独立TKDB调用模板</b></p>
<p><span class='description-red'>注1：如果网站分组未设置独立的TKDB模板，则使用对应模型的调用模板，如未设置泛域名配置，则使用主配置。</span></p>
<p><span class='description-red'> 注2：所有调用的优先级为：<u>独立网站分组配置</u> > <u>所属模型配置</u> > <u>默认调用配置</u></span></p>",
    ];

    $stations = \Drupal::entityTypeManager()->getStorage('seo_station')->loadMultiple();
    $stations = array_map(function ($station){
      return [
        'name' => $station->label(),
        'model' => !empty($station->model->target_id) ? $station->model->entity->label() : '无模型',
        'edit' => Link::createFromRoute('修改', 'tkdb.model_station.edit_form', [
          'seo_station_model' => $station->model->entity->id(),
          'seo_station' => $station->id(),
        ], [
          'query' => \Drupal::destination()->getAsArray(),
          'attributes' => [
            'class' => ['use-ajax'],
            'data-dialog-type' => 'modal',
            'data-dialog-options' => '{"width": "80%"}',
          ],
        ])->toString(),
        'delete' => Link::createFromRoute('清除', 'tkdb.model_station.delete_form', [
          'seo_station' => $station->id(),
        ],[
          'query' => \Drupal::destination()->getAsArray(),
          'attributes' => [
            'class' => ['use-ajax'],
            'data-dialog-type' => 'modal',
            'data-dialog-options' => '{"width": "80%"}',
          ],
        ]),
      ];
    }, $stations);

    $build['#content']['stations'] = $stations;
    $build['#theme'] = 'seo_station_tkdb_overview';
    $build['#cache']['tags'] = Cache::mergeTags(\Drupal::entityTypeManager()->getDefinition('seo_station_model')->getListCacheTags(), \Drupal::entityTypeManager()->getDefinition('seo_station_tkdb')->getListCacheTags(), \Drupal::entityTypeManager()->getDefinition('seo_station')->getListCacheTags());

    return $build;
  }
}
