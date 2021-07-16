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
 *     "storage" = "Drupal\seo_flink\FlinkStorage",
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
      ->setLabel('配置名称')
      ->setDescription('给本配置起个名字')
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

//友链开关
    $fields['mode'] = BaseFieldDefinition::create('boolean')
      ->setLabel('友链开关')
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

//页面显示
    $fields['page'] = BaseFieldDefinition::create('boolean')
      ->setLabel('页面显示')
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', '仅首页')
      ->setSetting('off_label', '首页和内页')
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//调用条数
    $fields['number'] = BaseFieldDefinition::create('integer')
      ->setLabel('调用条数')
      ->setSetting('unsigned', TRUE)
      ->setSetting('min', 3)
      ->setDisplayOptions('view', [
        'type' => 'number_integer',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//开启调用的域名
    $fields['domain'] = BaseFieldDefinition::create('text_long')
      ->setLabel('开启调用的域名')
      ->setDescription('开启调用的域名：<br/>一行一个，支持泛域名<br/><span class="description-red">a.com<br/>*.a.com</span>')
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'text_default',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//友链URL
    $fields['links'] = BaseFieldDefinition::create('text_long')
      ->setLabel('友链URL')
      ->setDescription('url地址#描文本#过期时间<br/>如： http://a.com/#百度#2019-10-11<br/><span class="description-red">http://a.com/#百度#2019-10-12</span><br/>http://a.com/<span class="description-red">#</span>百度<span class="description-red">#</span>2019-10-13')
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'text_default',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'))
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
