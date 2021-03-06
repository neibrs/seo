<?php

namespace Drupal\seo_textdata\Form;

use Drupal\Component\Utility\Environment;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class TextDataImportForm extends FormBase {

  protected $textdata_type;

  public function getFormId() {
    return 'seo_textdata_import_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $seo_textdata_type = NULL) {
    $this->textdata_type = $seo_textdata_type;
    $models = \Drupal::entityTypeManager()->getStorage('seo_station_model')->loadMultiple();
    $models = array_map(function ($model) {
      return $model->label();
    }, $models);

    $text_data = \Drupal::entityTypeManager()->getStorage('seo_textdata_type')->load($seo_textdata_type);
    $form['container'] = [
      '#type' => 'fieldset',
      '#title' => '上传' . $text_data->label(),
      '#tree' => TRUE,
    ];
    $form['model'] = [
      '#type' => 'select',
      '#options' => $models,
      '#title' => '所属分组',
    ];
    $validators = [
      'file_validate_extensions' => ['txt'],
      'file_validate_size' => [Environment::getUploadMaxSize()],
    ];
    $form['files'] = [
      '#type' => 'file',
      '#multiple' => TRUE,
      '#title' => '上传文件',
      '#description' => [
        '#theme' => 'file_upload_help',
        '#description' => '<span class="description-green">上传栏目库txt，支持多选，不建议使用中文文件名</span>',
        '#upload_validators' => $validators,
      ],
      '#size' => 50,
      '#upload_validators' => $validators,
      '#upload_location' => 'public://textdata',
      '#prefix' => '<div id="file-wrapper">',
      '#suffix' => '</div>',
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      $textdata_storage = \Drupal::entityTypeManager()->getStorage('seo_textdata');
      $files = file_save_upload('files', [], 'public://textdata/' . $this->textdata_type . '/', NULL);

      $model = $form_state->getValue('model');
      foreach ($files as $file) {
        $file->save();
        $values = [
          'type' => $this->textdata_type,
          'model' => $model,
          'attachment' => $file->id(),
          'name' => $file->getFilename(),
          'number' => 0,
          'size' => $file->getSize(),
          'has' => FALSE, //TODO
        ];

        $entity = $textdata_storage->create($values);
        $entity->save();
      }

      $this->messenger()->addStatus('数据导入成功!');
    }
    catch (\Exception $e) {
      $this->messenger()->addError($e);
    }
  }

}
