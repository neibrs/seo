<?php

namespace Drupal\spiders\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SpidersGroupSettingsForm.
 *
 * @ingroup spiders
 */
class SpidersGroupSettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'spidersgroup_settings';
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
    $config = \Drupal::configFactory()->get('spiders.settings');
    $config->set('switch', $form_state->getValue('switch'));
    $config->set('item', $form_state->getValue('item'));
    $config->save();

    $this->messenger()->addMessage('保存成功!');
  }

  /**
   * Defines the settings form for Spiders group entities.
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
    $form['switch'] = [
      '#type' => 'radios',
      '#options' => [
        1 => '开启',
        0 => '关闭',
      ],
      '#default_value' => $config->get('switch'),
    ];

    $form['item'] = [
      '#type' => 'radios',
      '#options' => [
        'no process' => '不处理',
        'no record' => '不记录',
        'defense' => '直接屏蔽',
      ],
      '#default_value' => $config->get('item'),
    ];
    return $form;
  }

}
