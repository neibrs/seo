<?php

namespace Drupal\seo_negotiator\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NegotiatorThemeForm extends FormBase {

  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

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
      $types[$model->config_dir->value] = $model->name->value;
    }
    $negotiator_storage = \Drupal::entityTypeManager()->getStorage('seo_negotiator');
    foreach ($type_themes as $name => $type_theme) {
      $negotiatories = $negotiator_storage->loadByProperties([
        'theme' => $name,
      ]);
      $default_domains = [];
      if (!empty($negotiatories)) {
        $default_domains = array_map(function ($negotiator) {
          return $negotiator->name->value;
        }, $negotiatories);
      }
      $form['themes_container'][$name] = [
        'type' => [
          '#type' => 'item',
          '#markup' => $types[$type_theme->info['seo_theme']],
        ],
        'machine_label' => [
          '#type' => 'item',
          '#markup' => '<span class="description-blue">' . $type_theme->info['name'] . '</span>(<span class="text-muted">' . $name . '</span>)',
        ],
        'screen' => [
          '#type' => 'item',
          '#markup' => '<img class="w-100" src=/' . $type_theme->info['screenshot'] . '>',
        ],
        'domains' => [
          '#type' => 'textarea',
          '#title' => '绑定域名(一行一个)：',
          '#rows' => 5,
          '#default_value' => !empty($default_domains) ? implode("\r\n", $default_domains) : '',
        ],
        'type_theme_action' => [
          '#type' => 'submit',
          '#value' => '保存',
        ],
      ];
    }

    $form['#attached']['library'][] = 'seo_negotiator/theme_page';

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $themes = $form_state->getValue('themes_container');
    $negotiator_storage = $this->entityTypeManager->getStorage('seo_negotiator');
    $negotiator_storage->delete($negotiator_storage->loadMultiple());
    foreach ($themes as $theme => $data) {
      $domains = array_unique(explode(',', str_replace("\r\n",",", $data['domains'])));

      foreach ($domains as $domain) {
        if (empty($domain)) {
          continue;
        }
        $negotiator_value = [
          'name' => trim($domain),
          'theme' => $theme,
        ];
        $negotiator_storage->create($negotiator_value)
          ->save();
      }

    }
  }

}
