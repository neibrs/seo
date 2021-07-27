<?php

namespace Drupal\seo_logo\Form;

use Drupal\Component\Utility\Environment;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

class LogoImportForm extends FormBase {

  protected $files;

  public function getFormId() {
    return 'logo_import_form';
  }


  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $validators = [
      'file_validate_extensions' => ['png gif jpg txt'],
      'file_validate_size' => [Environment::getUploadMaxSize()],
    ];
    $form['files'] = [
      '#type' => 'file',
      '#title' => '文件',
      '#multiple' => TRUE,
      '#description' => [
        '#theme' => 'file_upload_help',
        '#description' => $this->t('可以上传多个Logo图片，LOGO图标地址的文本(txt)文件;'),
        '#upload_validators' => $validators,
      ],
      '#size' => 50,
      '#upload_validators' => $validators,
      '#upload_location' => 'public://logos',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => '上传',
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $this->files = _file_save_upload_from_form($form['files'], $form_state);

    // Ensure we have the file uploaded.
    if (!$this->files) {
      $form_state->setErrorByName('files', $this->t('File to import not found.'));
    }
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $logo_storage = \Drupal::entityTypeManager()->getStorage('seo_logo');
    foreach ($this->files as $file) {
      /** @var \Drupal\file\FileInterface $file */
      $values = [
        'name' => $file->getFileName(),
        'file' => [
          $file->id(),
        ],
      ];
      $logo_storage->create($values)->save();
    }

    $form_state->setRedirectUrl(Url::fromRoute('entity.seo_logo.collection'));
  }
}
