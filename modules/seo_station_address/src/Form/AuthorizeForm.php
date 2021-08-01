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
    $form['authenticate_username'] = [
      '#type' => 'textfield',
      '#title' => '用户名',
      '#description' => '客户姓名',
    ];
    $form['authenticate_password'] = [
      '#type' => 'textfield',
      '#title' => '密码',
      '#description' => '密码',
    ];
    $form['authenticate_code'] = [
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
    $username = $form_state->getValue('authenticate_username');
    $password = $form_state->getValue('authenticate_password');
    $code = $form_state->getValue('authenticate_code');
    \Drupal::state()->set('authenticate_username' , $username);
    \Drupal::state()->set('authenticate_password' , $password);
    \Drupal::state()->set('authenticate_code', $code);

    // TODO, check on remote server.
    $data = [
      'name' => $username,
      'pass' => $code,
    ];
    $api_connection = \Drupal::getContainer()->get('plugin.manager.rest_api_connection');
    $instance = $api_connection->createInstance('airui_authenticate');
    $response = $instance->authentication($data);

    $check = FALSE;
    if (!$check) {
      $this->messenger()->addError('授权验证失败, 请重新输入注册信息.');
      $form_state->setRedirect('seo_station_address.set_authorize');
    }
  }

}
