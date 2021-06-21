<?php

namespace Drupal\seo_textdata\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class TextDataTypeForm.
 */
class TextDataTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $seo_textdata_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $seo_textdata_type->label(),
      '#description' => $this->t("Label for the Text data type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $seo_textdata_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\seo_textdata\Entity\TextDataType::load',
      ],
      '#disabled' => !$seo_textdata_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $seo_textdata_type = $this->entity;
    $status = $seo_textdata_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Text data type.', [
          '%label' => $seo_textdata_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Text data type.', [
          '%label' => $seo_textdata_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($seo_textdata_type->toUrl('collection'));
  }

}
