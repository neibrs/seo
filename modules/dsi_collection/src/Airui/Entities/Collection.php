<?php

namespace Drupal\dsi_collection\Airui\Entities;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\dsi_collection\Entity\CollectionInterface;

class Collection extends ContentEntityBase implements CollectionInterface {

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

    $fields['type']->setLabel('规则类型');

    //名称
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Collection entity.'))
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
//所属模型
    $fields['model'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('所属模型')
      ->setDescription('(可按住 CTRL 多选)')
      ->setSetting('target_type', 'seo_station_model')
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

//采集上限
    $fields['top'] = BaseFieldDefinition::create('integer')
      ->setLabel('采集上限')
      ->setDefaultValue(0)
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_integer',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayConfigurable('view', TRUE);
//每日采集次数上限
    $fields['times'] = BaseFieldDefinition::create('integer')
      ->setLabel('采集上限')
      ->setDefaultValue(0)
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_integer',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayConfigurable('view', TRUE);

//User-Agent
    $fields['user_agent'] = BaseFieldDefinition::create('string')
      ->setLabel('User-Agent')
      ->setDescription('（浏览器标识符，可模拟蜘蛛）')
      ->setSettings([
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
//内容语言
    $fields['content_language'] = BaseFieldDefinition::create('list_string')
      ->setLabel('内容语言')
      ->setDescription('如果是英文的请选择英文')
      ->setSetting('allowed_values_function', 'dsi_collection_content_language_options_allowed_values')
      ->setDefaultValue('en')
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'list_default',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_buttons',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // TODO
//伪原创.API
    // 不同类型下有不同的字段.
    // 1. 文章标题
    // 2. 子页标题
    // 3. 图片地址
    // 4. 句子
    // 5. 整篇内容
// 内容和标题都有字段.
    //列表页面配置(匹配网址)
    $fields['preg_address'] = BaseFieldDefinition::create('text_long')
      ->setLabel('匹配网址')
      //todo 内容和标题的描述不一样

      // 标题
      ->setDescription('(一行填写一条)
<span class="description-blue">http://xxfseo.com/a/list_{p,1,5,1}.html
{p,1,5,1} 表示分页，参数：p后面的数字分别代表开始、结束、递增/减值，即 {p,开始,结束,递增/减值}
</span>')
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    $fields['preg_rules'] = BaseFieldDefinition::create('text_long')
      ->setLabel('正则过滤')
      ->setDescription('正则过滤，如：<span class="description-blue"><script[^>]+>(.*)</script></span>一行一条规则')
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

//自动采集
    $fields['automate'] = BaseFieldDefinition::create('boolean')
      ->setLabel('自动采集')
      ->setDefaultValue(FALSE);

    $fields['status']->setDescription(t('A boolean indicating whether the Collection is published.'))
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

  public  static function bundleFieldDefinitions(EntityTypeInterface $entity_type, $bundle, array $base_field_definitions) {
    $fields = parent::bundleFieldDefinitions($entity_type, $bundle, $base_field_definitions);

    switch ($bundle) {
      case 'full_content':
      case 'sentence':
        // 以下5个是内容的字段
        $fields += static::getFilterDefinition();
        // 内容类型
        $fields['preg_content_type'] = BaseFieldDefinition::create('list_string')
          ->setLabel('内容类型')
          ->setDescription('如果是英文的请选择英文')
          ->setSetting('allowed_values_function', 'dsi_collection_content_language_options_allowed_values')
          ->setDefaultValue('en')
          ->setDisplayOptions('view', [
            'label' => 'inline',
            'type' => 'list_default',
            'weight' => 0,
          ])
          ->setDisplayOptions('form', [
            'type' => 'options_buttons',
            'weight' => 0,
          ])
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);
        //每行句子数
        $fields['per_line'] = BaseFieldDefinition::create('integer')
          ->setLabel('每行句子数')
          ->setDescription('每行放多少条句子，默认为1 <span class="description-red">分割成句子时用</span>')
          ->setDefaultValue(1)
          ->setSetting('unsigned', TRUE)
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
        //内容获取(网址匹配)
        $fields['preg_content_address'] = BaseFieldDefinition::create('string')
          ->setLabel('网址匹配')
          ->setDescription('如：http://x.com/html/<span class="description-red">(d)</span>.html ，通配符号：<span class="description-red">(*)</span>（任意字符）、<span class="description-red">(w)</span>（数字字母下划线）、<span class="description-red">(d)</span>（数字）')
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
        //内容获取测试地址
        $fields['preg_content_test_address'] = BaseFieldDefinition::create('string')
          ->setLabel('测试地址')
          ->setDescription('(可填，不填则系统自动随机获取一条)')
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
        //内容获取内容截取原则
        $fields['preg_content_rules'] = BaseFieldDefinition::create('text_long')
          ->setLabel('内容截取原则')
          ->setDescription('正则截取，如：<div class="abc"[^>]+>(.*)</div>
<span class="description-red">一行一条规则，系统会取成功匹配的内容</span>')
          ->setDisplayOptions('view', [
            'type' => 'text_default',
            'weight' => 10,
          ])
          ->setDisplayConfigurable('view', TRUE)
          ->setDisplayOptions('form', [
            'type' => 'text_textarea',
            'weight' => 10,
          ])
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);

        $fields['preg_address']
          ->setDescription('<span class="description-blue">http://xxfseo.com/
          http://xxfseo.com/a/list_{p,1,5,1}.html
          http://xxfseo.com/a/list_{p,1,5,1}.html->http://xxfseo.com/html/(*).html</span>


          {p,1,5,1} 表示分页，参数：p后面的数字分别代表开始、结束、递增/减值，即 {p,开始,结束,递增/减值}

          匹配指定url的标题，则后面加->url规则，通配符号：(*)（任意字符）、(w)（数字字母下划线）、(d)（数字）
          ');
        break;

      case 'article_title':
        //保存类型 标题类型的
        $fields += static::getFilterDefinition();
        $fields['textdata_type'] = BaseFieldDefinition::create('entity_reference')
          ->setLabel('保存类型')
          ->setSetting('target_type', 'dsi_textdata_type')
          ->setDisplayOptions('view', [
            'type' => 'entity_reference_label',
            'weight' => 0,
            'label' => 'inline',
          ])
          ->setDisplayOptions('form', [
            'type' => 'options_select',
            'weight' => 15,
          ])
          ->setDisplayConfigurable('form', TRUE)
          ->setDisplayConfigurable('view', TRUE);
        break;
      case 'subpage_title':
        //一级网址匹配
        $fields['preg_subpage_first_rule'] = BaseFieldDefinition::create('string')
          ->setLabel('一级网址匹配')
          ->setDescription('如：http://x.com/html/<span class="description-red">(d)</span>.html ，通配符号：<span class="description-red">(*)</span>（任意字符）、<span class="description-red">(w)</span>（数字字母下划线）、<span class="description-red">(d)</span>（数字）')
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
        // 子页网址匹配
        $fields['preg_subpage_inner_rule'] = BaseFieldDefinition::create('string')
          ->setLabel('子页网址匹配')
          ->setDescription('如：http://x.com/html/<span class="description-red">(d)</span>.html ，通配符号：<span class="description-red">(*)</span>（任意字符）、<span class="description-red">(w)</span>（数字字母下划线）、<span class="description-red">(d)</span>（数字）')
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
        break;
      case 'pic_address':
        $fields['preg_address']
          ->setDescription('http://xxfseo.com/
http://xxfseo.com/a/list_{p,1,5,1}.html
http://xxfseo.com/a/list_{p,1,5,1}.html->http://xxfseo.com/uploads/images/(*).jpg


{p,1,5,1} 表示分页，参数：p后面的数字分别代表开始、结束、递增/减值，即 {p,开始,结束,递增/减值}

匹配指定url的图片，则后面加->url规则，通配符号：(*)（任意字符）、(w)（数字字母下划线）、(d)（数字）');
        break;
    }

    return $fields;
  }

  protected static function getFilterDefinition() {
    $fields = [];
    //1. 过滤配置(内容最小长度)
    $fields['min_length'] = BaseFieldDefinition::create('integer')
      ->setLabel('最小长度')
      ->setDescription('一个中文字的长度为2')
      ->setDefaultValue(0)
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_integer',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayConfigurable('view', TRUE);

    //2. 正则过滤
    $fields['preg_rules'] = BaseFieldDefinition::create('text_long')
      ->setLabel('正则过滤')
      ->setDescription('正则过滤，如：<span class="description-blue"><script[^>]+>(.*)</script></span>一行一条规则')
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    //3. 过滤词语
    $fields['preg_word'] = BaseFieldDefinition::create('text_long')
      ->setLabel('过滤词语')
      ->setDescription('<span class="description-red">包含词语的内容自动过滤，如果只是删除则前面每个词加*号</span>一行填写一个')
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    return $fields;
  }
}
