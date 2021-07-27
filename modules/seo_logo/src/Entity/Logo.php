<?php

namespace Drupal\seo_logo\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Logo entity.
 *
 * @ingroup seo_logo
 *
 * @ContentEntityType(
 *   id = "seo_logo",
 *   label = @Translation("Logo"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_logo\LogoListBuilder",
 *     "views_data" = "Drupal\seo_logo\Entity\LogoViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_logo\Form\LogoForm",
 *       "add" = "Drupal\seo_logo\Form\LogoForm",
 *       "edit" = "Drupal\seo_logo\Form\LogoForm",
 *       "delete" = "Drupal\seo_logo\Form\LogoDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_logo\LogoHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_logo\LogoAccessControlHandler",
 *   },
 *   base_table = "seo_logo",
 *   translatable = FALSE,
 *   admin_permission = "administer logo entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_logo/{seo_logo}",
 *     "add-form" = "/admin/seo_logo/add",
 *     "edit-form" = "/admin/seo_logo/{seo_logo}/edit",
 *     "delete-form" = "/admin/seo_logo/{seo_logo}/delete",
 *     "collection" = "/admin/seo_logo",
 *   },
 *   field_ui_base_route = "seo_logo.settings"
 * )
 */
class Logo extends ContentEntityBase implements LogoInterface {

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
      ->setDefaultValue('Logo');

    //文件
    $fields['file'] = BaseFieldDefinition::create('image')
      ->setLabel('文件')
      ->setSetting('uri_scheme', 'public://logos/')
      ->setDisplayOptions('view', [
        'type' => 'image',
        'label' => 'hidden',
        'settings' => [
          'image_style' => 'logo',
        ],
      ])
      ->setDisplayOptions('form', [
        'type' => 'image_image',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
