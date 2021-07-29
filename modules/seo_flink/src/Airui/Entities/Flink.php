<?php

namespace Drupal\seo_flink\Airui\Entities;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\seo_flink\Entity\FlinkInterface;

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
      // TODO, 未处理该显示规则
//      ->setDisplayOptions('form', [
//        'type' => 'options_buttons_with_none',
//        'weight' => 0,
//      ])
//      ->setDisplayOptions('view', [
//        'label' => 'above',
//        'type' => 'string',
//        'weight' => 0,
//      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//调用条数
    $fields['number'] = BaseFieldDefinition::create('integer')
      ->setLabel('调用条数')
      ->setRequired(TRUE)
      ->setDefaultValue(5)
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
