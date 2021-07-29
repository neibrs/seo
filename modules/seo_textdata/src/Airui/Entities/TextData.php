<?php

namespace Drupal\seo_textdata\Airui\Entities;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\seo_textdata\Entity\TextDataInterface;

class TextData extends ContentEntityBase implements TextDataInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

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
      ->setDescription(t('The name of the Text data entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'inline',
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

//文件
    $fields['attachment'] = BaseFieldDefinition::create('file')
      ->setLabel('文件')
      ->setSetting('file_extensions', 'txt')
      ->setDisplayOptions('view', [
        'type' => 'file_default',
        'weight' => 110,
        'label' => 'inline',
      ])
      ->setDisplayOptions('form', [
        'type' => 'file_generic',
        'weight' => 110,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    //所属分类
    $fields['model'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('所属分类')
      ->setSetting('target_type', 'seo_station_model')
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 0,
        'label' => 'inline',
      ]);

    //行数
    $fields['number'] = BaseFieldDefinition::create('integer')
      ->setLabel('行数')
      ->setDefaultValue(0)
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_integer',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayConfigurable('view', TRUE);
//文件大小
    $fields['size'] = BaseFieldDefinition::create('string')
      ->setLabel('文件大小')
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
      ->setDisplayConfigurable('view', TRUE);
//本地(local)用来区别本地或官方的数据
    $fields['local'] = BaseFieldDefinition::create('boolean')
      ->setLabel('本地')
      ->setDefaultValue(TRUE);
//内容库(只在文章库才显示)
    $fields['has'] = BaseFieldDefinition::create('boolean')
      ->setLabel('内容库')
      ->setDescription('是否有内容')
      ->setDefaultValue(FALSE)
      ->setSetting('on_label', '有')
      ->setSetting('off_label', '无');

    $fields['tags'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('行业')
      ->setSetting('target_type', 'taxonomy_term')
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'textdata_tags' => 'textdata_tags',
        ],
        'sort' => [
          'field' => '_none',
        ],
        'auto_create' => TRUE,
      ])
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete_tags',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    $fields['status']
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
    parent::postSave($storage, $update);
    if ($this->bundle() == 'typename') {
      $queue = \Drupal::queue('typename_process');
      $queue->createItem(['textdata' => $this]);
    }
  }

}
