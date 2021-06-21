<?php

namespace Drupal\dsi_collection\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CollectionTypeForm.
 */
class CollectionTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $dsi_collection_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $dsi_collection_type->label(),
      '#description' => $this->t("Label for the Collection type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $dsi_collection_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\dsi_collection\Entity\CollectionType::load',
      ],
      '#disabled' => !$dsi_collection_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $dsi_collection_type = $this->entity;
    $status = $dsi_collection_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Collection type.', [
          '%label' => $dsi_collection_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Collection type.', [
          '%label' => $dsi_collection_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($dsi_collection_type->toUrl('collection'));
  }

}
