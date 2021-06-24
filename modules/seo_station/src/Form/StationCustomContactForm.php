<?php

namespace Drupal\seo_station\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class StationCustomContactForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['seo_station.custom_contact'];
  }

  public function getFormId() {
    return 'seo_station_custom_contact_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['site_group_container'] = [
      '#type' => 'container',
      '#title' => '基本设置',
    ];
    $stations = \Drupal::entityTypeManager()->getStorage('seo_station')->loadMultiple();
    $stations = array_map(function ($station) {
      return $station->label();
    }, $stations);

    $form['site_group_container']['site_group'] = [
      '#type' => 'select',
      '#options' => ['' => '请选择']+$stations,
      '#title' => '选择网站分组',
      '#description' => '<span class="description-red">选择开启此功能的网站分组</span>',
    ];

    $form['contact_group'] = [
      '#type' => 'container',
      '#title' => '联系方式',
    ];
    $form['contact_group']['phone'] = [
      '#type' => 'text_format',
      '#format' => 'plain_text',
      '#title' => '联系电话',
      '#rows' => 5,
      '#default_value' => $this->config('seo_station.custom_contact')->get('phone'),
      '#description' => '<span class="description-red">每行一个电话号码，系统会随机调用</span>',
    ];

    $form['contact_group']['address'] = [
      '#type' => 'text_format',
      '#format' => 'plain_text',
      '#title' => '联系地址',
      '#rows' => 5,
      '#default_value' => $this->config('seo_station.custom_contact')->get('address'),
      '#description' => '<span class="description-red">每行一个联系地址，系统会随机调用</span>',
    ];
    $form['contact_group']['email'] = [
      '#type' => 'text_format',
      '#format' => 'plain_text',
      '#title' => '联系邮箱',
      '#rows' => 5,
      '#default_value' => $this->config('seo_station.custom_contact')->get('email'),
      '#description' => '<span class="description-red">每行一个邮箱地址，系统会随机调用</span>',
    ];
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('seo_station.custom_contact')
      ->set('site_group', $form_state->getValue('site_group'))
      ->set('phone', $form_state->getValue('phone'))
      ->set('address', $form_state->getValue('address'))
      ->set('email', $form_state->getValue('email'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
