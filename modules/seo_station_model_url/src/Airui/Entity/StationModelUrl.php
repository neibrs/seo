<?php

namespace Drupal\seo_station_model_url\Airui\Entity;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\seo_station_model_url\Entity\StationModelUrlInterface;

class StationModelUrl extends ContentEntityBase implements StationModelUrlInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritDoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    if (empty($this->getName())) {
      $this->name->value = 'Auto';
    }
    parent::preSave($storage);
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Station model url entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
//model: ??????ID
    $fields['model'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('??????ID')
      ->setSetting('target_type', 'seo_station_model');
//template: ????????????
    $fields['template'] = BaseFieldDefinition::create('string')
      ->setLabel('????????????')
      ->setDescription(t('The name of the Station model url entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

//rule_url_content: ??????
    $fields['rule_url_content'] = BaseFieldDefinition::create('text')
      ->setLabel('??????')
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'text_textfield',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the Station model url is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    $query = $this->entityTypeManager()->getStorage('seo_station_model_url')->getQuery();
    $query->condition('model', $this->model->target_id);
    $ids = $query->execute();

    // ??????model??????????????? TODO,?????????,?????????????????????station_model_url???????????????
    // TODO ??????????????????????????????????????????????????????????????????????????????????????????????????????????????????.
    $model = $this->entityTypeManager()->getStorage('seo_station_model')->load($this->model->target_id);
    $model->set('templates', count($ids));

    // ??????$ids?????????url??????
    $data = $this->getModelRules();
    $model->set('rules', count($data));

    $model->save();
  }

  public function getRulesById($url) {
    return array_unique(explode(',', str_replace("\r\n",",",$url->rule_url_content->value)));
  }

  public function getModelRules() {
    $data = [];

    $model = $this->model->target_id;
    $urls = $this->entityTypeManager()->getStorage('seo_station_model_url')->loadByProperties([
      'model' => $model,
    ]);
    foreach ($urls as $url) {
      $data[] = $this->getRulesById($url);
    }
    // Flatten
    $data = array_unique(iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($data)), FALSE));

    return $data;
  }
}
