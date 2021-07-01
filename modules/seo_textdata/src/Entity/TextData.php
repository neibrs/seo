<?php

namespace Drupal\seo_textdata\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Defines the Text data entity.
 *
 * @ingroup seo_textdata
 *
 * @ContentEntityType(
 *   id = "seo_textdata",
 *   label = @Translation("内容库"),
 *   label_collection = @Translation("内容库"),
 *   bundle_label = @Translation("内容库类型"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_textdata\TextDataListBuilder",
 *     "views_data" = "Drupal\seo_textdata\Entity\TextDataViewsData",
 *     "translation" = "Drupal\seo_textdata\TextDataTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_textdata\Form\TextDataForm",
 *       "add" = "Drupal\seo_textdata\Form\TextDataForm",
 *       "edit" = "Drupal\seo_textdata\Form\TextDataForm",
 *       "delete" = "Drupal\seo_textdata\Form\TextDataDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_textdata\TextDataHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_textdata\TextDataAccessControlHandler",
 *   },
 *   base_table = "seo_textdata",
 *   data_table = "seo_textdata_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer text data entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_textdata/{seo_textdata}",
 *     "add-page" = "/admin/seo_textdata/add",
 *     "add-form" = "/admin/seo_textdata/add/{seo_textdata_type}",
 *     "edit-form" = "/admin/seo_textdata/{seo_textdata}/edit",
 *     "delete-form" = "/admin/seo_textdata/{seo_textdata}/delete",
 *     "collection" = "/admin/seo_textdata",
 *   },
 *   bundle_entity_type = "seo_textdata_type",
 *   field_ui_base_route = "entity.seo_textdata_type.edit_form"
 * )
 */
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

}
