<?php

namespace Drupal\spiders\Form;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Class SpidersSettingsForm.
 *
 * @ingroup spiders
 */
class SpidersSettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'spiders_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::configFactory()->getEditable('spiders.settings');
    $config->set('switch', $form_state->getValue('switch'));
    $config->set('item', $form_state->getValue('item'));
    $config->save();
    $this->messenger()->addMessage('保存成功!');
  }

  /**
   * Defines the settings form for Spiders entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = \Drupal::config('spiders.settings');
    $form['basic'] = [
      '#type' => 'details',
      '#title' => '基本设置',
      '#attributes' => [
        'class' => ['container-inline'],
      ],
      '#open' => TRUE,
    ];
    $form['basic']['switch'] = [
      '#type' => 'radios',
      '#title' => '蜘蛛访问记录开关',
      '#options' => [
        1 => '开启',
        0 => '关闭',
      ],
      '#default_value' => $config->get('switch'),
    ];

    $form['basic']['item'] = [
      '#type' => 'radios',
      '#title' => '访问css、js、图片的蜘蛛',
      '#options' => [
        'no process' => '不处理',
        'no record' => '不记录',
        'defense' => '直接屏蔽',
      ],
      '#default_value' => $config->get('item'),
    ];

    $form['basic']['agents'] = [
      '#type' => 'details',
      '#title' => '蜘蛛(UserAgent)识别方式(可自定义)',
    ];

    $form['basic']['agents']['user_agents'] = [
      '#type' => 'table',
      '#header' => [
        '蜘蛛名称', '操作',
      ],
    ];
    $spider_types = \Drupal::entityTypeManager()->getStorage('spiders_type')->loadMultiple();
    foreach ($spider_types as $id => $spider_type) {
      $form['basic']['agents']['user_agents'][$id]['name'] = ['#markup' => $spider_type->label()];
      $form['basic']['agents']['user_agents'][$id]['operation'] = [
        'edit' => [
          '#type' => 'link',
          '#title' => '编辑',
          '#url' => Url::fromRoute('entity.spiders_type.edit_form', ['spiders_type' => $spider_type->id()], [
            'query' => \Drupal::destination()->getAsArray(),
          ]),
          '#attributes' => [
            'class' => 'use-ajax',
            'data-dialog-type' => 'modal',
            'data-dialog-options' => Json::encode(['width' => '60%']),
          ],
        ],
        'delete' => [
          '#type' => 'link',
          '#title' => '删除',
          '#url' => Url::fromRoute('entity.spiders_type.delete_form', ['spiders_type' => $spider_type->id()])
        ],
      ];
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => '保存',
    ];

    return $form;
  }

}
