<?php

namespace Drupal\seo_station\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Auto prefix entity.
 *
 * @ingroup seo_station
 *
 * @ContentEntityType(
 *   id = "seo_autoprefix",
 *   label = @Translation("Auto prefix"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station\AutoPrefixListBuilder",
 *     "views_data" = "Drupal\seo_station\Entity\AutoPrefixViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_station\Form\AutoPrefixForm",
 *       "add" = "Drupal\seo_station\Form\AutoPrefixForm",
 *       "edit" = "Drupal\seo_station\Form\AutoPrefixForm",
 *       "delete" = "Drupal\seo_station\Form\AutoPrefixDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station\AutoPrefixHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_station\AutoPrefixAccessControlHandler",
 *   },
 *   base_table = "seo_autoprefix",
 *   translatable = FALSE,
 *   admin_permission = "administer auto prefix entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/seo_autoprefix/{seo_autoprefix}",
 *     "add-form" = "/seo_autoprefix/add",
 *     "edit-form" = "/seo_autoprefix/{seo_autoprefix}/edit",
 *     "delete-form" = "/seo_autoprefix/{seo_autoprefix}/delete",
 *     "collection" = "/seo_autoprefix",
 *   },
 *   field_ui_base_route = "seo_autoprefix.settings"
 * )
 */
class AutoPrefix extends ContentEntityBase implements AutoPrefixInterface {

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

    // step
    $fields['step'] = BaseFieldDefinition::create('integer')
      ->setLabel('step')
      ->setDefaultValue(1)
      ->setSetting('unsigned', TRUE);
    // start
    $fields['start'] = BaseFieldDefinition::create('integer')
      ->setLabel('start')
      ->setDefaultValue(1)
      ->setSetting('unsigned', TRUE);
    // end
    $fields['end'] = BaseFieldDefinition::create('integer')
      ->setLabel('end')
      ->setDefaultValue(1)
      ->setSetting('unsigned', TRUE);
    // station
    $fields['station'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('Station')
      ->setSetting('target_type', 'seo_station')
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 0,
      ]);

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
