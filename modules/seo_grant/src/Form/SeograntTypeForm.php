<?php

namespace Drupal\seo_grant\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SeograntTypeForm.
 */
class SeograntTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $seo_grant_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $seo_grant_type->label(),
      '#description' => $this->t("Label for the Seogrant type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $seo_grant_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\seo_grant\Entity\SeograntType::load',
      ],
      '#disabled' => !$seo_grant_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $seo_grant_type = $this->entity;
    $status = $seo_grant_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Seogrant type.', [
          '%label' => $seo_grant_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Seogrant type.', [
          '%label' => $seo_grant_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($seo_grant_type->toUrl('collection'));
  }

}
