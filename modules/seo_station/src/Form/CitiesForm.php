<?php

namespace Drupal\seo_station\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class CitiesForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['seo_station.cities'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'seo_station_cities';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['cities'] = [
      '#type' => 'text_format',
      '#format' => 'plain_text',
      '#rows' => 15,
      '#default_value' => $this->config('seo_station.cities')->get('cities'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('seo_station.cities')
      ->set('cities', $form_state->getValue('cities'))
      ->save();

    parent::submitForm($form, $form_state); // TODO: Change the autogenerated stub
  }

}
