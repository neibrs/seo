<?php

namespace Drupal\seo_station\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ExtractLinksForm extends FormBase {

  public function getFormId() {
    return 'seo_station_extract_links_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['number'] = [
      '#type' => 'number',
      '#title' => '每个域名提取数量',
      '#description' => '每个域名提取数量大于10个时，请生成数据后再访问.提取文章<span class="description-red">总数量=域名数*每个域名提取数量</span>',
      '#default_value' => 10,
    ];
    $form['actions'] = [
      '#tree' => FALSE,
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => '提取数据',
      '#button_type' => 'primary',
    ];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    return $form_state->setRedirect('seo_station.extract_data', ['number' => $form_state->getValue('number')]);
  }

}
