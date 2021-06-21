<?php

namespace Drupal\dsi_pseudo_api\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Pseudo api entity.
 *
 * @ingroup dsi_pseudo_api
 *
 * @ContentEntityType(
 *   id = "dsi_pseudo_api",
 *   label = @Translation("Pseudo api"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dsi_pseudo_api\PseudoApiListBuilder",
 *     "views_data" = "Drupal\dsi_pseudo_api\Entity\PseudoApiViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dsi_pseudo_api\Form\PseudoApiForm",
 *       "add" = "Drupal\dsi_pseudo_api\Form\PseudoApiForm",
 *       "edit" = "Drupal\dsi_pseudo_api\Form\PseudoApiForm",
 *       "delete" = "Drupal\dsi_pseudo_api\Form\PseudoApiDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\dsi_pseudo_api\PseudoApiHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\dsi_pseudo_api\PseudoApiAccessControlHandler",
 *   },
 *   base_table = "dsi_pseudo_api",
 *   translatable = FALSE,
 *   admin_permission = "administer pseudo api entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/dsi_pseudo_api/{dsi_pseudo_api}",
 *     "add-form" = "/dsi_pseudo_api/add",
 *     "edit-form" = "/dsi_pseudo_api/{dsi_pseudo_api}/edit",
 *     "delete-form" = "/dsi_pseudo_api/{dsi_pseudo_api}/delete",
 *     "collection" = "/dsi_pseudo_api",
 *   },
 *   field_ui_base_route = "dsi_pseudo_api.settings"
 * )
 */
class PseudoApi extends ContentEntityBase implements PseudoApiInterface {

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
      ->setLabel('API名称')
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

    $fields['api'] = BaseFieldDefinition::create('string')
      ->setLabel('API地址')
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
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['format'] = BaseFieldDefinition::create('string')
      ->setLabel('post格式')
      ->setDescription('post格式，{word}表示要伪原创的内容，如：appkey=123456&content={word}')
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
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['authorization'] = BaseFieldDefinition::create('string')
      ->setLabel('post格式')
      ->setDescription('个别接口需要设置这个，如5118这里就放apikey')
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

    $fields['success'] = BaseFieldDefinition::create('string')
      ->setLabel('成功标志')
      ->setDescription('格式：键名=键值，如：result=1，没有则留空')
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

    $fields['error'] = BaseFieldDefinition::create('string')
      ->setLabel('返回错误的字段')
      ->setDescription('返回具体错误的字段，如：message')
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

    $fields['content'] = BaseFieldDefinition::create('string')
      ->setLabel('伪原创后的内容字段')
      ->setDescription('返回伪原创后的内容字段，如：content')
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

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
