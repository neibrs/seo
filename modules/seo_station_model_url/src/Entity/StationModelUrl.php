<?php

namespace Drupal\seo_station_model_url\Entity;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Station model url entity.
 *
 * @ingroup seo_station_model_url
 *
 * @ContentEntityType(
 *   id = "seo_station_model_url",
 *   label = @Translation("站群模型Url规则"),
 *   label_collection = @Translation("站群模型Url规则"),
 *   handlers = {
 *     "storage" = "Drupal\seo_station_model_url\StationModelUrlStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station_model_url\StationModelUrlListBuilder",
 *     "views_data" = "Drupal\seo_station_model_url\Entity\StationModelUrlViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_station_model_url\Form\StationModelUrlForm",
 *       "add" = "Drupal\seo_station_model_url\Form\StationModelUrlForm",
 *       "edit" = "Drupal\seo_station_model_url\Form\StationModelUrlForm",
 *       "delete" = "Drupal\seo_station_model_url\Form\StationModelUrlDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station_model_url\StationModelUrlHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_station_model_url\StationModelUrlAccessControlHandler",
 *   },
 *   base_table = "seo_station_model_url",
 *   translatable = FALSE,
 *   admin_permission = "administer station model url entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_station_model_url/{seo_station_model_url}",
 *     "add-page" = "/admin/seo_station_model_url/add",
 *     "add-form" = "/admin/seo_station_model_url/add/{seo_station_model_url_type}",
 *     "edit-form" = "/admin/seo_station_model_url/{seo_station_model_url}/edit",
 *     "delete-form" = "/admin/seo_station_model_url/{seo_station_model_url}/delete",
 *     "collection" = "/admin/seo_station_model_url",
 *   },
 *   bundle_entity_type = "seo_station_model_url_type",
 *   field_ui_base_route = "entity.seo_station_model_url_type.edit_form",
 * )
 */
class StationModelUrl extends ContentEntityBase implements StationModelUrlInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritDoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    if (empty($this->getName())) {
      $this->name->value = 'Auto';
    }
    parent::preSave($storage);
  }

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
      ->setDescription(t('The name of the Station model url entity.'))
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
//model: 模型ID
    $fields['model'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('模型ID')
      ->setSetting('target_type', 'seo_station_model');
//template: 模板文件
    $fields['template'] = BaseFieldDefinition::create('string')
      ->setLabel('模板文件')
      ->setDescription(t('The name of the Station model url entity.'))
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

//rule_url_content: 内容
    $fields['rule_url_content'] = BaseFieldDefinition::create('text')
      ->setLabel('内容')
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'text_textfield',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the Station model url is published.'))
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
    $query = $this->entityTypeManager()->getStorage('seo_station_model_url')->getQuery();
    $query->condition('model', $this->model->target_id);
    $ids = $query->execute();

    // 保存model的模板数量 TODO,待优化,这里每更新一次station_model_url都会处理，
    // TODO 想个办法最后一起更新。目前，模板数据较少时，性能无影响，多了就会存在性能瓶颈.
    $model = $this->entityTypeManager()->getStorage('seo_station_model')->load($this->model->target_id);
    $model->set('templates', count($ids));

    // 循环$ids里面的url规则
    $data = $this->getModelRules();
    $model->set('rules', count($data));

    $model->save();
  }

  public function getRulesById($url) {
    return array_unique(explode(',', str_replace("\r\n",",",$url->rule_url_content->value)));
  }

  public function getModelRules() {
    $data = [];

    $model = $this->model->target_id;
    $urls = $this->entityTypeManager()->getStorage('seo_station_model_url')->loadByProperties([
      'model' => $model,
    ]);
    foreach ($urls as $url) {
      $data[] = $this->getRulesById($url);
    }
    // Flatten
    $data = array_unique(iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($data)), FALSE));

    return $data;
  }
}
