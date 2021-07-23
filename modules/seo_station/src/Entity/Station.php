<?php

namespace Drupal\seo_station\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Defines the Station entity.
 *
 * @ingroup seo_station
 *
 * @ContentEntityType(
 *   id = "seo_station",
 *   label = @Translation("网站管理"),
 *   label_collection = @Translation("网站管理"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\seo_station\StationListBuilder",
 *     "views_data" = "Drupal\seo_station\Entity\StationViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\seo_station\Form\StationForm",
 *       "add" = "Drupal\seo_station\Form\StationForm",
 *       "edit" = "Drupal\seo_station\Form\StationForm",
 *       "delete" = "Drupal\seo_station\Form\StationDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\seo_station\StationHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\seo_station\StationAccessControlHandler",
 *   },
 *   base_table = "seo_station",
 *   translatable = FALSE,
 *   admin_permission = "administer station entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/seo_station/{seo_station}",
 *     "add-form" = "/admin/seo_station/add",
 *     "edit-form" = "/admin/seo_station/{seo_station}/edit",
 *     "delete-form" = "/admin/seo_station/{seo_station}/delete",
 *     "collection" = "/admin/seo_station",
 *   },
 *   field_ui_base_route = "seo_station.settings"
 * )
 */
class Station extends ContentEntityBase implements StationInterface {

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
      ->setLabel('分组名称')
      ->setDescription('给本分组起个名字')
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

//    域名设置:
//    =========
//    分组名称(name)
    //所属模型(model)
    $fields['model'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('所属模型')
      ->setSetting('target_type', 'seo_station_model')
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 0,
      ]);

    //域名(domain)
    $fields['domain'] = BaseFieldDefinition::create('text_long')
      ->setLabel('域名')
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
//配置文件夹(不可重复)
    $fields['config_dir'] = BaseFieldDefinition::create('string')
      ->setLabel('配置文件夹')
      ->setDescription('<span class="description-red">填字母数字</span>，分组的关键词和外链将放在此文件夹')
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
//
//站点模式:
//=========
//网站模式(站点模式)(site_mode)
    $fields['site_mode'] = BaseFieldDefinition::create('boolean')
      ->setLabel('站点模式')
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', '泛域名')
      ->setSetting('off_label', '单域名')
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 15,
      ]);

    //---------
//301重定向到www(redirect301type)
    $fields['redirect301type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('301重定向到www')
      ->setSetting('target_type', 'lookup')
      ->setSetting('handler_settings', [
        'target_bundles' => ['redirect301type' => 'redirect301type'],
      ]);
//      ->setDisplayOptions('view', [
//        'type' => 'entity_reference_label',
//        'weight' => 0,
//        'label' => 'inline',
//      ])
//      ->setDisplayOptions('form', [
//        'type' => 'options_buttons_with_none',
//        'weight' => 15,
//      ])
//      ->setDisplayConfigurable('form', TRUE)
//      ->setDisplayConfigurable('view', TRUE);

    //泛域名前缀
    $fields['prefix_multi'] = BaseFieldDefinition::create('boolean')
      ->setLabel('泛域名前缀')
      ->setDefaultValue(FALSE)
      ->setSetting('on_label', '自定义（推荐）')
      ->setSetting('off_label', '自动生成')
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 15,
      ]);

    //自动生成前缀(auto_prefix)
    $fields['auto_prefix'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('自动生成前缀')
      ->setSetting('target_type', 'seo_autoprefix');

    //屏蔽非自定义泛前缀访问(dis_custom_prefix_multi)
    $fields['dis_custom_prefix_multi'] = BaseFieldDefinition::create('boolean')
      ->setLabel('屏蔽非自定义泛前缀访问')
      ->setDescription('屏蔽非自定义泛前缀的域名访问')
      ->setDefaultValue(FALSE);
//      ->setDisplayOptions('form', [
//        'type' => 'options_buttons_with_none',
//        'weight' => 15,
//      ]);

    //    启用城市泛域名(city_prefix_multi)
    $fields['city_prefix_multi'] = BaseFieldDefinition::create('boolean')
      ->setLabel('启用城市泛域名')
      ->setDefaultValue(FALSE)
      ->setDescription('<span class="description-red">开启后自动将城市拼音加入泛域名前缀列表</span>');
//      ->setDisplayOptions('form', [
//        'type' => 'options_buttons_with_none',
//        'weight' => 15,
//      ]);

    //自定义泛域名前缀(custom_prefix_multi)
    $fields['custom_prefix_multi'] = BaseFieldDefinition::create('text_long')
      ->setLabel('自定义泛域名前缀')
      ->setDescription('<span class="description-blue">注：如果前缀使用了标签并且超过50个，不要屏蔽，否则会卡</span><br/><span class="description-red">支持标签：{数字}、{字母}、{数字字母}<br/>标签后面加数字是位数，{数字8}表示8个数字、{数字1-8}示随机1-8个数字</span>');
//      ->setDisplayOptions('view', [
//        'type' => 'text_default',
//        'weight' => 10,
//      ])
//      ->setDisplayConfigurable('view', TRUE)
//      ->setDisplayOptions('form', [
//        'type' => 'text_textarea',
//        'weight' => 10,
//      ])
//      ->setDisplayConfigurable('form', TRUE)
//      ->setDisplayConfigurable('view', TRUE);
//
//内容模式
//=========
//内容类型(content_type)
    $fields['content_type'] = BaseFieldDefinition::create('boolean')
      ->setLabel('内容类型')
      ->setDescription('<span class="description-red"> 1. 选择文章库内容时，标题、缩略图等从文章库内容中提取<br />
2. 选择句子拼凑时，标题是从标题库中提取<br/>
3. 句子拼凑的格式可在</span> TKDB调用模板->内容模板中设置')
      ->setDefaultValue(FALSE)
//      ->setDisplayOptions('form', [
//        'type' => 'options_buttons_with_none',
//        'weight' => 10,
//      ])
      ->setSetting('on_label', '句子拼凑')
      ->setSetting('off_label', '文章库内容');
//---------
//导航缓存(navigate_cache)
    $fields['navigate_cache'] = BaseFieldDefinition::create('boolean')
      ->setLabel('导航缓存')
      ->setDescription('开启后可实现当前域名固定导航')
      ->setDefaultValue(FALSE);
//      ->setDisplayOptions('form', [
//        'type' => 'options_buttons_with_none',
//        'weight' => 10,
//      ]);
//网站名称库(site_name)
    $fields['site_name'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('网站名称库')
      ->setDescription('即网站名称，不填则随机取自网站名称库')
      ->setSetting('target_type', 'seo_textdata')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'webname' => 'webname',
        ],
      ])
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    //自定义栏目库(site_column)
    $fields['site_column'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('自定义栏目库')
      ->setDescription('即网站栏目，不填则随机取自栏目库')
      ->setSetting('target_type', 'seo_textdata')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'typename' => 'typename',
        ],
      ])
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//自定义标题库(site_title)
    $fields['site_title'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('自定义标题库')
      ->setDescription('内容类型为<span class="description-blue">句子拼凑</span>时使用，不填则随机取自标题库')
      ->setSetting('target_type', 'seo_textdata')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'title' => 'title',
        ],
      ])
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//自定义句子库(site_sentence)
    $fields['site_sentence'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('自定义句子库')
      ->setDescription('不填则随机取自句子库')
      ->setSetting('target_type', 'seo_textdata')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'diy' => 'diy',
        ],
      ])
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 0,
        'label' => 'inline',
      ])
//      ->setDisplayOptions('form', [
//        'type' => 'options_select',
//        'weight' => 0,
//      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//自定义图片库(site_pics)
    $fields['site_pics'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('自定义图片库')
      ->setDescription('不填则随机取自图片库，一般句子模式时使用到')
      ->setSetting('target_type', 'seo_textdata')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'pic' => 'pic',
        ],
      ])
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 0,
        'label' => 'inline',
      ])
//      ->setDisplayOptions('form', [
//        'type' => 'options_select',
//        'weight' => 0,
//      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//自定义文章库(site_node)
    $fields['site_node'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('自定义文章库')
      ->setDescription('内容类型为<span class="description-blue">文章库</span>时使用，不填则随机取自文章库')
      ->setSetting('target_type', 'seo_textdata')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'article' => 'article',
        ],
      ])
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 0,
        'label' => 'inline',
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
//企业简介句子(site_intro)
    $fields['site_intro'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('企业简介句子')
      ->setDescription('模型为<span class="description-blue">企业</span>时使用，不填则使用默认的')
      ->setSetting('target_type', 'seo_textdata')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'content' => 'content',
        ],
      ])
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 0,
        'label' => 'inline',
      ])
//      ->setDisplayOptions('form', [
//        'type' => 'options_select',
//        'weight' => 0,
//      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

//内容句子调用条数(number)
    $fields['number'] = BaseFieldDefinition::create('integer')
      ->setLabel('内容句子调用条数')
      ->setDescription('(<span class="description-blue">句子拼凑</span>时)内容模板句子的<span class="description-red">可调用条数</span>，默认60条，<span class="description-red">建议100以下，否则影响性能</span>')
      ->setRequired(TRUE)
      ->setDefaultValue(60)
      ->setSetting('unsigned', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'number_integer',
        'weight' => 0,
        'label' => 'inline',
      ])
//      ->setDisplayOptions('form', [
//        'type' => 'number',
//        'weight' => 0,
//      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['tags'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('行业')
      ->setSetting('target_type', 'taxonomy_term')
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'textdata_tags' => 'textdata_tags',
        ],
        'sort' => [
          'field' => '_none',
        ],
        'auto_create' => TRUE,
      ])
//      ->setDisplayOptions('view', [
//        'type' => 'entity_reference_label',
//        'weight' => 0,
//        'label' => 'inline',
//      ])
//      ->setDisplayOptions('form', [
//        'type' => 'entity_reference_autocomplete_tags',
//        'weight' => 0,
//      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // use official textdata. 是否使用官网数据
    $fields['use_official'] = BaseFieldDefinition::create('boolean')
      ->setLabel('是否使用官网数据')
      ->setDescription('如果选用官网数据，则本地内容设置无效')
      ->setDefaultValue(FALSE)
//      ->setDisplayOptions('view', [
//        'type' => 'boolean',
//        'weight' => 0,
//        'label' => 'inline',
//      ])
//      ->setDisplayOptions('form', [
//        'type' => 'boolean_checkbox',
//        'weight' => 0,
//      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    //
//URL设置
//========
//URL模式(url_mode)
    $fields['url_mode'] = BaseFieldDefinition::create('boolean')
      ->setLabel('URL模式')
      ->setDefaultValue(0)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 10,
      ])
      ->setSetting('on_label', '固定模式')
      ->setSetting('off_label', '随机模式');
//---------
//URL缓存开关(url_cache)
    $fields['url_cache'] = BaseFieldDefinition::create('boolean')
      ->setLabel('URL缓存开关')
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 10,
      ]);
//URL路径(url_type)
    $fields['url_type'] = BaseFieldDefinition::create('boolean')
      ->setLabel('URL路径')
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 10,
      ])
      ->setSetting('on_label', '绝对路径')
      ->setSetting('off_label', '相对路径');
//屏蔽非URL规则地址(new)(dis_url_rule)
    $fields['dis_url_rule'] = BaseFieldDefinition::create('boolean')
      ->setLabel('屏蔽非URL规则地址')
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 10,
      ]);
//图片URL规则(url_rule)
    $fields['url_rule'] = BaseFieldDefinition::create('string')
      ->setLabel('图片URL规则')
      ->setDescription('开启图片url本地化时（<span class="description-red">句子模式</span>下）有效，必须带上<span class="description-red">{id}</span>且不支持标签')
      ->setRequired(TRUE)
      ->setDefaultValue('uploads/images/{id}.jpg')
      ->setDisplayOptions('view', [
        'type' => 'string',
        'weight' => -20,
        'label' => 'inline',
        'settings' => [
          'link_to_entity' => TRUE,
        ],
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    //
//手机版设置
//========
//手机版(mobile)
    $fields['mobile'] = BaseFieldDefinition::create('boolean')
      ->setLabel('手机版')
      ->setDescription('是否开启手机版')
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 10,
      ]);
//地址跳转(mobile_redirect)
    $fields['mobile_redirect'] = BaseFieldDefinition::create('boolean')
      ->setLabel('地址跳转')
      ->setDescription('手机访问时自动转到手机版地址')
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 10,
      ]);
//手机域名前缀(mobile_prefix)
    $fields['mobile_prefix'] = BaseFieldDefinition::create('string')
      ->setLabel('手机域名前缀')
      ->setDescription('手机版域名前缀，如：m 或者 wap，<span class="description-red">留空则全部打开都是手机版</span>')
      ->setSettings([
        'max_length' => 5,
        'text_processing' => 0,
      ])
      ->setDefaultValue('m')
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

    //
//MIP模块设置
//========
//MIP站点(mip_site)
    $fields['mip_site'] = BaseFieldDefinition::create('boolean')
      ->setLabel('MIP站点')
      ->setDescription('当前站点是否设为MIP站点，MIP站点使用MIP模板')
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 10,
      ]);
//mip域名前缀(mip_site_prefix)
    $fields['mip_site_prefix'] = BaseFieldDefinition::create('string')
      ->setLabel('mip域名前缀')
      ->setDescription('mip域名前缀，如：mip，当域名前缀为该设置时设为MIP站点<br/><span class="description-red">mip域名前缀设置为空时，本分组下的全部域名都视为MIP站点（请关闭手机版)</span>')
      ->setSettings([
        'max_length' => 10,
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

    //无视MIP效验(ignore_mip_verify)
    $fields['ignore_mip_verify'] = BaseFieldDefinition::create('boolean')
      ->setLabel('无视MIP效验')
      ->setDescription('<span class="description-red">关闭后，广告、统计将失效，以便通过MIP效验</span>')
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 10,
      ]);
//
//`MIP统计设置`
//-------
//百度统计token(token_baidu)
    $fields['token_baidu'] = BaseFieldDefinition::create('string')
      ->setLabel('百度统计token')
      ->setDescription('百度统计代码里的token的值，一般是32位的字符')
      ->setSettings([
        'max_length' => 32,
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

    //cnzz统计token(token_cnzz)
    $fields['token_cnzz'] = BaseFieldDefinition::create('string')
      ->setLabel('cnzz统计token')
      ->setDescription('cnzz友盟统计的token，一般是一串数字')
      ->setSettings([
        'max_length' => 32,
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

    //
//域名
//关键词
//外链
//域名前缀
//修改时间
//
//其他设置
//-------------
//其他模板内容标签调用(other_tag)
    $fields['other_tag'] = BaseFieldDefinition::create('boolean')
      ->setLabel('其他模板内容标签调用')
      ->setDescription('开启后非show模板，也可以使用内容页show的标签，如 $body')
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 10,
      ]);
//sitemap开关(other_sitemap)
    $fields['other_sitemap'] = BaseFieldDefinition::create('boolean')
      ->setLabel('sitemap开关')
      ->setDescription('关闭后，打开sitemap地图将显示404')
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons_with_none',
        'weight' => 10,
      ]);
//
//独立设置
//------------
//    独立屏蔽游客访问(dis_annoy_access)
    $fields['dis_annoy_access'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('独立屏蔽游客访问')
      ->setSetting('target_type', 'lookup')
      ->setSetting('handler_settings', [
        'target_bundles' => ['independent_access' => 'independent_access'],
      ])
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

//独立游客访问跳转(dis_annoy_redirect)
    $fields['dis_annoy_redirect'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('独立游客访问跳转')
      ->setSetting('target_type', 'lookup')
      ->setSetting('handler_settings', [
        'target_bundles' => ['independent_access' => 'independent_access'],
      ])
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

//独立游客跳转地址(annoy_redirect_url)
    $fields['annoy_redirect_url'] = BaseFieldDefinition::create('string')
      ->setLabel('独立游客跳转地址')
      ->setDefaultValue('http://')
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

    //独立流量统计代码(stream_statistic)
    $fields['stream_statistic'] = BaseFieldDefinition::create('text')
      ->setLabel('独立流量统计代码')
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

    $fields['status']->setDefaultValue(TRUE);

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

    // 保存域名数量到模型里面.
    $stations = $this->entityTypeManager()->getStorage('seo_station')->loadByProperties([
      'model' => $this->model->target_id,
    ]);
    $number = 0;
    foreach ($stations as $station) {
      $domain = array_unique(explode(',', str_replace("\r\n",",", $station->domain->value)));
      $domain = array_filter($domain, function ($item) {
        if (!empty($item)) {
          return $item;
        }
      });
      $number += count($domain);
    }
    $this->model->entity->domains->value = $number;
    $this->model->entity->save();
  }

}
