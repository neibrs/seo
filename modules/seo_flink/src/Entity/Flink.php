<?php

namespace Drupal\seo_flink\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Flink entity.
 *
 * @ingroup seo_flink
 *
 * @ContentEntityType(
 *   id = "seo_flink",
 *   label = @Translation("Flink"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_flink\FlinkListBuilder",
 *     "views_data" = "Drupal\seo_flink\Entity\FlinkViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_flink\Form\FlinkForm",
 *       "add" = "Drupal\seo_flink\Form\FlinkForm",
 *       "edit" = "Drupal\seo_flink\Form\FlinkForm",
 *       "delete" = "Drupal\seo_flink\Form\FlinkDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_flink\FlinkHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_flink\FlinkAccessControlHandler",
 *   },
 *   base_table = "seo_flink",
 *   translatable = FALSE,
 *   admin_permission = "administer flink entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_flink/{seo_flink}",
 *     "add-form" = "/admin/seo_flink/add",
 *     "edit-form" = "/admin/seo_flink/{seo_flink}/edit",
 *     "delete-form" = "/admin/seo_flink/{seo_flink}/delete",
 *     "collection" = "/admin/seo_flink",
 *   },
 *   field_ui_base_route = "seo_flink.settings"
 * )
 */
class Flink extends ContentEntityBase implements FlinkInterface {

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
      ->setDescription(t('The name of the Flink entity.'))
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

    $fields['status']->setDescription(t('A boolean indicating whether the Flink is published.'))
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
