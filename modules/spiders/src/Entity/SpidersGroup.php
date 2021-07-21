<?php

namespace Drupal\spiders\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Spiders group entity.
 *
 * @ingroup spiders
 *
 * @ContentEntityType(
 *   id = "spiders_group",
 *   label = @Translation("Spiders group"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\spiders\SpidersGroupListBuilder",
 *     "views_data" = "Drupal\spiders\Entity\SpidersGroupViewsData",
 *     "translation" = "Drupal\spiders\SpidersGroupTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\spiders\Form\SpidersGroupForm",
 *       "add" = "Drupal\spiders\Form\SpidersGroupForm",
 *       "edit" = "Drupal\spiders\Form\SpidersGroupForm",
 *       "delete" = "Drupal\spiders\Form\SpidersGroupDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\spiders\SpidersGroupHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\spiders\SpidersGroupAccessControlHandler",
 *   },
 *   base_table = "spiders_group",
 *   data_table = "spiders_group_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer spiders group entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/spiders_group/{spiders_group}",
 *     "add-form" = "/admin/spiders_group/add",
 *     "edit-form" = "/admin/spiders_group/{spiders_group}/edit",
 *     "delete-form" = "/admin/spiders_group/{spiders_group}/delete",
 *     "collection" = "/admin/spiders_group",
 *   },
 *   field_ui_base_route = "spiders_group.settings"
 * )
 */
class SpidersGroup extends ContentEntityBase implements SpidersGroupInterface {

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
      ->setDescription(t('The name of the Spiders group entity.'))
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

    $fields['status']
      ->setLabel('蜘蛛防火墙开关')
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
