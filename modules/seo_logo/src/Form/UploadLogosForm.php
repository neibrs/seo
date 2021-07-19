<?php

namespace Drupal\seo_logo\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class UploadLogosForm extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'upload_logo_form';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['files'] = [
      '#type' => 'file',
      '#title' => '文件',
      '#multiple' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => '上传',
    ];

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $logo_storage = \Drupal::entityTypeManager()->getStorage('seo_logo');
    $files = file_managed_file_save_upload($form['files'], $form_state);
    foreach ($files as $file) {
      $values = [
        'name' => $file->getFileName(),
        'file' => [
          $file->id(),
        ],
      ];
      $logo_storage->create($values)->save();
      $file->setPermanent();
    }
  }

}
