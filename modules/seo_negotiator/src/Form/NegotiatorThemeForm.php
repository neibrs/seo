<?php

namespace Drupal\seo_negotiator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class NegotiatorThemeForm extends FormBase {

  public function getFormId() {
    return 'negotiator_theme_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = \Drupal::config('system.theme');

    // Get all avaiable themes.
    $themes = \Drupal::service('theme_handler')->rebuildThemeData();
    uasort($themes, 'system_sort_modules_by_info_name');
    $type_themes = array_filter($themes, function ($theme) {
      if (isset($theme->info['seo_theme'])) {
        return $theme;
      }
    });

    $form['themes_container'] = [
      '#type' => 'container',
      '#title' => '所有模板',
      '#tree' => TRUE,
    ];
    $models = \Drupal::entityTypeManager()->getStorage('seo_station_model')->loadMultiple();
    $types = [];
    foreach ($models as $model) {
      $types[$model->config_dir->value] = $model->label();
    }
    foreach ($type_themes as $name => $type_theme) {
      $form['themes_container'][$name] = [
        'machine_name' => [
          '#type' => 'item',
          '#markup' => $name,
        ],
        'machine_label' => [
          '#type' => 'item',
          '#markup' => $type_theme->info['name'] . '(' . $types[$type_theme->info['seo_theme']] . ')',
        ],
        'screen' => [
          '#type' => 'item',
          '#markup' => '<img class="w-100" src=/' . $type_theme->info['screenshot'] . '>',
        ],
        'domains' => [
          '#type' => 'textarea',
          '#title' => '绑定域名(一行一个)：',
          '#rows' => 5,
        ],
        'type_theme_action' => [
          '#type' => 'submit',
          '#value' => '保存',
        ],
      ];
    }

    $form['#attached']['library'][] = 'seo_negotiator/theme';

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
