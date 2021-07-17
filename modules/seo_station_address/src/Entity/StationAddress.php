<?php

namespace Drupal\seo_station_address\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\xmlsitemap\Entity\XmlSitemap;

/**
 * Defines the Station address entity.
 *
 * @ingroup seo_station_address
 *
 * @ContentEntityType(
 *   id = "seo_station_address",
 *   label = @Translation("提取地址"),
 *   label_collection = @Translation("提取地址"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station_address\StationAddressListBuilder",
 *     "views_data" = "Drupal\seo_station_address\Entity\StationAddressViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_station_address\Form\StationAddressForm",
 *       "add" = "Drupal\seo_station_address\Form\StationAddressForm",
 *       "edit" = "Drupal\seo_station_address\Form\StationAddressForm",
 *       "delete" = "Drupal\seo_station_address\Form\StationAddressDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station_address\StationAddressHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_station_address\StationAddressAccessControlHandler",
 *   },
 *   base_table = "seo_station_address",
 *   translatable = FALSE,
 *   admin_permission = "administer station address entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_station_address/{seo_station_address}",
 *     "add-form" = "/admin/seo_station_address/add",
 *     "edit-form" = "/admin/seo_station_address/{seo_station_address}/edit",
 *     "delete-form" = "/admin/seo_station_address/{seo_station_address}/delete",
 *     "collection" = "/admin/seo_station_address",
 *   },
 *   field_ui_base_route = "seo_station_address.settings"
 * )
 */
class StationAddress extends ContentEntityBase implements StationAddressInterface {

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
      ->setDescription(t('The name of the Station address entity.'))
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
    $fields['domain'] = BaseFieldDefinition::create('string')
      ->setLabel('Domain')
      ->setSettings([
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

    $fields['webname'] = BaseFieldDefinition::create('string')
      ->setLabel('Webname')
      ->setSettings([
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

    $fields['status']->setDescription(t('A boolean indicating whether the Station address is published.'))
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

    // Append data for xmlsitemap.
    $domains = explode('/', $this->label());
    $values = [
      'context' => [
        'language' => 'zh-hans',
        'domain' => $domains[0],
      ],
      'label' => 'http://' . $domains[0],
    ];
    $queue = \Drupal::queue('station_address_xmlsitemap');
    $queue->createItem($values);
  }

}
