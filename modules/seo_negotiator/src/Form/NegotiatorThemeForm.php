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
    ];
    foreach ($type_themes as $name => $type_theme) {
      $form['themes_container'][$name] = [
        'type' => [
          '#type' => 'item',
          '#markup' => $type_theme->info['seo_theme'],
        ],
        'machine_name' => [
          '#type' => 'item',
          '#markup' => $name,
        ],
        'machine_label' => [
          '#type' => 'item',
          '#markup' => $type_theme->info['name'],
        ],
        'screen' => [
          '#type' => 'responsive_image',
          '#uri' => $type_theme->getPath() . '/' . $type_theme->info['screenshot'],
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

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
