<?php

namespace Drupal\seo_station_tkdb\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StationTkdbDeleteForm extends ConfirmFormBase {

  protected $model;
  protected $station;
  protected $entityTypeManager;
  protected $tkdb_storage;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->tkdb_storage = $this->entityTypeManager->getStorage('seo_station_tkdb');
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  public function getFormId() {
    return 'seo_station_tkdb_delete_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $seo_station_model = NULL, $seo_station = NULL) {
    $this->model = $seo_station_model;
    $this->station = $seo_station;

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => '清除配置',
    ];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $keywords = [
      'model' => $this->model,
      'group' => $this->station,
    ];
    // 创建？
    $ids = \Drupal::service('seo_station_tkdb.manager')->getTkdb($keywords);
    $tkdb_storage = $this->entityTypeManager->getStorage('seo_station_tkdb');
    $tkdb_storage->delete($tkdb_storage->loadMultiple($ids));

    $this->messenger()->addStatus('删除成功！');
  }

  public function getQuestion() {
    return $this->t('Are you sure you want to delete the recent logs?');
  }

  public function getCancelUrl() {
    return new Url('seo_station_tkdb.overview');
  }

}
