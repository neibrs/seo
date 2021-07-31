<?php

namespace Drupal\seo_station_address\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class AuthorizeForm extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'seo_station_address_authorize_form';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['code'] = [
      '#type' => 'textarea',
      '#title' => '授权码',
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => '验证授权',
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $code = $form_state->getValue('code');
    \Drupal::state()->set('all_user_authorize_code', $code);
  }

}
