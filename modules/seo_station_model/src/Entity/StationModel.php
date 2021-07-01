<?php

namespace Drupal\seo_station_model\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Station model entity.
 *
 * @ingroup seo_station_model
 *
 * @ContentEntityType(
 *   id = "seo_station_model",
 *   label = @Translation("站群模型"),
 *   label_collection = @Translation("站群模型"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station_model\StationModelListBuilder",
 *     "views_data" = "Drupal\seo_station_model\Entity\StationModelViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_station_model\Form\StationModelForm",
 *       "add" = "Drupal\seo_station_model\Form\StationModelForm",
 *       "edit" = "Drupal\seo_station_model\Form\StationModelForm",
 *       "delete" = "Drupal\seo_station_model\Form\StationModelDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station_model\StationModelHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_station_model\StationModelAccessControlHandler",
 *   },
 *   base_table = "seo_station_model",
 *   translatable = FALSE,
 *   admin_permission = "administer station model entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_station_model/{seo_station_model}",
 *     "add-form" = "/admin/seo_station_model/add",
 *     "edit-form" = "/admin/seo_station_model/{seo_station_model}/edit",
 *     "delete-form" = "/admin/seo_station_model/{seo_station_model}/delete",
 *     "collection" = "/admin/seo_station_model",
 *   },
 *   field_ui_base_route = "seo_station_model.settings"
 * )
 */
class StationModel extends ContentEntityBase implements StationModelInterface {

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

//name: 模型名称
//config_dir: 配置文件夹
    $fields['config_dir'] = BaseFieldDefinition::create('string')
      ->setLabel('配置文件夹')
      ->setDescription('<span class="description-blue">填写文件夹名称，数字和字母，不要有中文和符号，不要有重复</span>,<br/> <span class="description-red">添加后最好不修改，内容库以及模板等设置会以此为命名基础</span>')
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
//main: 主模板文件
    $fields['main'] = BaseFieldDefinition::create('string')
      ->setLabel('主模板文件')
      ->setDescription(t('系统默认的模板 <span class="description-red">首页：index、列表：list、内容：show</span>'))
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
//secondary: 其他模板文件
    $fields['secondary'] = BaseFieldDefinition::create('string')
      ->setLabel('其他模板文件')
      ->setDescription('不用加.html，多个用,分开。<span class="description-red">先保存，才可设置url规则</span>')
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
//rule_url: 模板规则(随机模式，固定模式)
    $fields['rule_url'] = BaseFieldDefinition::create('boolean')
      ->setLabel('url规则(每行一条)')
      ->setDefaultValue(FALSE)
      ->setSetting('on_label', '随机模式(缓存)')
      // Off = processed, or not queued yet.
      ->setSetting('off_label', '固定模式(缓存)')
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => -4,
      ]);

    //rule_url_id: 模板规则@see seo_station_model_url.module

//domains: 域名数量
    $fields['domains'] = BaseFieldDefinition::create('integer')
      ->setLabel('域名数量')
      ->setDefaultValue(0)
      ->setSetting('unsigned', TRUE);
//templates: 模板数量
    $fields['templates'] = BaseFieldDefinition::create('integer')
      ->setLabel('模板数量')
      ->setDefaultValue(0)
      ->setSetting('unsigned', TRUE);
//rules: 采集规则数量
    $fields['rules'] = BaseFieldDefinition::create('integer')
      ->setLabel('采集规则数量')
      ->setDefaultValue(0)
      ->setSetting('unsigned', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
