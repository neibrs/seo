<?php

namespace Drupal\spiders\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Spiders entity.
 *
 * @ingroup spiders
 *
 * @ContentEntityType(
 *   id = "spiders",
 *   label = @Translation("蜘蛛记录"),
 *   label_collection = @Translation("蜘蛛记录"),
 *   bundle_label = @Translation("蜘蛛名称"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\spiders\SpidersListBuilder",
 *     "views_data" = "Drupal\spiders\Entity\SpidersViewsData",
 *     "translation" = "Drupal\spiders\SpidersTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\spiders\Form\SpidersForm",
 *       "add" = "Drupal\spiders\Form\SpidersForm",
 *       "edit" = "Drupal\spiders\Form\SpidersForm",
 *       "delete" = "Drupal\spiders\Form\SpidersDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\spiders\SpidersHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\spiders\SpidersAccessControlHandler",
 *   },
 *   base_table = "spiders",
 *   data_table = "spiders_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer spiders entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/spiders/{spiders}",
 *     "add-page" = "/admin/spiders/add",
 *     "add-form" = "/admin/spiders/add/{spiders_type}",
 *     "edit-form" = "/admin/spiders/{spiders}/edit",
 *     "delete-form" = "/admin/spiders/{spiders}/delete",
 *     "collection" = "/admin/spiders",
 *   },
 *   bundle_entity_type = "spiders_type",
 *   field_ui_base_route = "entity.spiders_type.edit_form"
 * )
 */
class Spiders extends ContentEntityBase implements SpidersInterface {

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
      ->setDescription(t('The name of the Spiders entity.'))
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

//蜘蛛名称
    $fields['type']->setLabel('蜘蛛名称');
//IP地址
    $fields['ip'] = BaseFieldDefinition::create('string')
      ->setLabel('IP地址')
      ->setDescription('访问的来源地址.')
      ->setSettings([
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//国家/城市
    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel('国家/城市')
      ->setDescription('访问的来源国家/城市.')
      ->setSettings([
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//访问地址
    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel('访问地址')
      ->setSettings([
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//模型
    $fields['model'] = BaseFieldDefinition::create('string')
      ->setLabel('模型')
      ->setSettings([
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//访问时间
    $fields['access'] = BaseFieldDefinition::create('timestamp')
      ->setLabel('访问时间')
      ->setDefaultValue(0)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'timestamp_ago',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['status']
      ->setLabel('蜘蛛防火墙开关')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -5,
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
