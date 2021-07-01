<?php

namespace Drupal\seo_grant\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Seogrant entity.
 *
 * @ingroup seo_grant
 *
 * @ContentEntityType(
 *   id = "seo_grant",
 *   label = @Translation("授权码"),
 *   label_collection = @Translation("授权码"),
 *   bundle_label = @Translation("授权码类型"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_grant\SeograntListBuilder",
 *     "views_data" = "Drupal\seo_grant\Entity\SeograntViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_grant\Form\SeograntForm",
 *       "add" = "Drupal\seo_grant\Form\SeograntForm",
 *       "edit" = "Drupal\seo_grant\Form\SeograntForm",
 *       "delete" = "Drupal\seo_grant\Form\SeograntDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_grant\SeograntHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_grant\SeograntAccessControlHandler",
 *   },
 *   base_table = "seo_grant",
 *   translatable = FALSE,
 *   admin_permission = "administer seogrant entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_grant/{seo_grant}",
 *     "add-page" = "/admin/seo_grant/add",
 *     "add-form" = "/admin/seo_grant/add/{seo_grant_type}",
 *     "edit-form" = "/admin/seo_grant/{seo_grant}/edit",
 *     "delete-form" = "/admin/seo_grant/{seo_grant}/delete",
 *     "collection" = "/admin/seo_grant",
 *   },
 *   bundle_entity_type = "seo_grant_type",
 *   field_ui_base_route = "entity.seo_grant_type.edit_form"
 * )
 */
class Seogrant extends ContentEntityBase implements SeograntInterface {

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
      ->setDescription(t('The name of the Seogrant entity.'))
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

    //    授权用户:
    $fields['grant_user'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('用户')
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      // Default EntityTest entities to have the root user as the owner, to
      // simplify testing.
      ->setDefaultValue([0 => ['target_id' => 1]])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => -1,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ],
      ]);
    //    IP:
    $fields['ip'] = BaseFieldDefinition::create('string')
      ->setLabel('IP')
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
    //    域名:
    $fields['domain'] = BaseFieldDefinition::create('string')
      ->setLabel('域名')
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

    //    授权时间:
    $fields['grant_time'] = BaseFieldDefinition::create('timestamp')
      ->setLabel('授权时间')
      ->setDefaultValue(0)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'timestamp_ago',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('view', TRUE);
    //    授权码:
    $fields['grant_code'] = BaseFieldDefinition::create('text_long')
      ->setLabel('授权码')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'text_default',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'text_textfield',
        'weight' => 0,
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
