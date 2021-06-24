<?php

namespace Drupal\seo_station\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class StationCustomDomainTkdForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['seo_station.custom_domain_tkd'];
  }

  public function getFormId() {
    return 'seo_station_custom_domain_tkd_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('seo_station.custom_domain_tkd');
    $form['custom_domain_tkd'] = [
      '#type' => 'textarea',
      '#rows' => 15,
      '#default_value' => $config->get('custom_domain_tkd'),
    ];
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('seo_station.custom_domain_tkd')
      ->set('custom_domain_tkd', $form_state->getValue('custom_domain_tkd'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
