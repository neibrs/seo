<?php

namespace Drupal\spiders\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SpidersTypeForm.
 */
class SpidersTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $spiders_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $spiders_type->label(),
      '#description' => $this->t("Label for the Spiders type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $spiders_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\spiders\Entity\SpidersType::load',
      ],
      '#disabled' => !$spiders_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $spiders_type = $this->entity;
    $status = $spiders_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Spiders type.', [
          '%label' => $spiders_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Spiders type.', [
          '%label' => $spiders_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($spiders_type->toUrl('collection'));
  }

}