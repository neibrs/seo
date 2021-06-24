<?php

namespace Drupal\seo_station\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class StationHttpsForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['seo_station.https'];
  }

  public function getFormId() {
    return 'seo_station_https';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('seo_station.https');
    $form['https'] = [
      '#type' => 'text_format',
      '#format' => 'plain_text',
      '#rows' => 15,
      '#default_value' => $config->get('https'),
    ];
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('seo_station.https')
      ->set('https', $form_state->getValue('https'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
