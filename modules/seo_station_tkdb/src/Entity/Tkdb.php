<?php

namespace Drupal\seo_station_tkdb\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Tkdb entity.
 *
 * @ingroup seo_station_tkdb
 *
 * @ContentEntityType(
 *   id = "seo_station_tkdb",
 *   label = @Translation("Tkdb"),
 *   label_collection = @Translation("Tkdb"),
 *   bundle_label = @Translation("Tkdb type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station_tkdb\TkdbListBuilder",
 *     "views_data" = "Drupal\seo_station_tkdb\Entity\TkdbViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_station_tkdb\Form\TkdbForm",
 *       "add" = "Drupal\seo_station_tkdb\Form\TkdbForm",
 *       "edit" = "Drupal\seo_station_tkdb\Form\TkdbForm",
 *       "delete" = "Drupal\seo_station_tkdb\Form\TkdbDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station_tkdb\TkdbHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_station_tkdb\TkdbAccessControlHandler",
 *   },
 *   base_table = "seo_station_tkdb",
 *   translatable = FALSE,
 *   admin_permission = "administer tkdb entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/seo_station_tkdb/{seo_station_tkdb}",
 *     "add-page" = "/seo_station_tkdb/add",
 *     "add-form" = "/seo_station_tkdb/add/{seo_station_tkdb_type}",
 *     "edit-form" = "/seo_station_tkdb/{seo_station_tkdb}/edit",
 *     "delete-form" = "/seo_station_tkdb/{seo_station_tkdb}/delete",
 *     "collection" = "/seo_station_tkdb",
 *   },
 *   bundle_entity_type = "seo_station_tkdb_type",
 *   field_ui_base_route = "entity.seo_station_tkdb_type.edit_form"
 * )
 */
class Tkdb extends ContentEntityBase implements TkdbInterface {

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
      ->setDescription(t('The name of the Tkdb entity.'))
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

    //    - model 网站模型
    $fields['model'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('模型ID')
      ->setSetting('target_type', 'seo_station_model');

    //    - group 网站分组
    $fields['group'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('网站ID')
      ->setSetting('target_type', 'seo_station');

    // Station Tkdb设置中区分是否泛域名设置.
    $fields['wild'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('泛域名设置')
      ->setSetting('target_type', 'seo_station');


    //    - template 模板文件，index, list, show, ...
    $fields['template'] = BaseFieldDefinition::create('string')
      ->setLabel('模板文件')
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

    //- content 调用模板内容
    $fields['content'] = BaseFieldDefinition::create('text_long')
      ->setLabel('内容')
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the Tkdb is published.'))
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
