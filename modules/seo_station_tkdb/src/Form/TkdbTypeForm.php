<?php

namespace Drupal\seo_station_tkdb\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class TkdbTypeForm.
 */
class TkdbTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $seo_station_tkdb_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $seo_station_tkdb_type->label(),
      '#description' => $this->t("Label for the Tkdb type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $seo_station_tkdb_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\seo_station_tkdb\Entity\TkdbType::load',
      ],
      '#disabled' => !$seo_station_tkdb_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $seo_station_tkdb_type = $this->entity;
    $status = $seo_station_tkdb_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Tkdb type.', [
          '%label' => $seo_station_tkdb_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Tkdb type.', [
          '%label' => $seo_station_tkdb_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($seo_station_tkdb_type->toUrl('collection'));
  }

}
