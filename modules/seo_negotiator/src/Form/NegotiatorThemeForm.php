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

    $twig_themes = [];
    foreach ($type_themes as &$type_theme) {
      $twig_themes[] = [
        'name' => $type_theme->info['name'],
        'description' => $type_theme->info['description'],
        'seo_theme' => $type_theme->info['seo_theme'],
      ];
    }

    $form['items'] = [
      '#type' => 'item',
      '#items' => $twig_themes,
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
