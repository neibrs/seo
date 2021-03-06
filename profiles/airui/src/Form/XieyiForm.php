<?php

namespace Drupal\airui\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class XieyiForm extends FormBase {

  public function getFormId() {
    return 'airui_xieyi_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = ['#type' => 'submit', '#value' => t('Save'), '#button_type' => 'primary'];
    return $form;
  }

  /**
   * TODO: Implement submitForm() method.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
