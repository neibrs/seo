<?php

namespace Drupal\seo_station\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for Station edit forms.
 *
 * @ingroup seo_station
 */
class StationForm extends ContentEntityForm {

  /**
   * The current user account.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    $instance = parent::create($container);
    $instance->account = $container->get('current_user');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var \Drupal\seo_station\Entity\Station $entity */
    $form = parent::buildForm($form, $form_state);

    // Placeholder
    $form['name']['widget'][0]['value']['#placeholder'] = '分组测试';
    $form['config_dir']['widget'][0]['value']['#placeholder'] = 'test';
    $form['domain']['widget'][0]['#type'] = 'text_format';
    $form['domain']['widget'][0]['#format'] = 'plain_text';
    $form['domain']['widget'][0]['#placeholder'] = 'ceshi.cm
4.zhizhuchi.cm
5.zhizhuchi.cm
6.zhizhuchi.cm
7.zhizhuchi.cm
8.zhizhuchi.cm
9.zhizhuchi.cm
10.zhizhuchi.cm';
    if (isset($form['custom_prefix_multi'])) {
      $form['custom_prefix_multi']['widget'][0]['#weight'] = 20;
      $form['custom_prefix_multi']['widget'][0]['#type'] = 'text_format';
      $form['custom_prefix_multi']['widget'][0]['#format'] = 'plain_text';
    }

    // Get station model list.
    if (empty($this->getEntity()->model->target_id)) {
      $models = $this->entityTypeManager->getStorage('seo_station_model')->loadMultiple();
      $model = reset($models);
      if (!empty($model)) {
        $form['model']['widget']['#default_value'] = $model->id();
      }
    }
    else {
      $form['model']['widget']['#default_value'] = $this->getEntity()->model->target_id;
    }

    $form['site_mode']['widget']['#default_value'] = $this->entity->site_mode->value;
    $form['content_type']['widget']['#default_value'] = 0;
    $form['prefix_multi']['widget']['#default_value'] = 0;

    $form['tabs'] = [
      '#type' => 'horizontal_tabs',
     ];

    $form['domain_settings'] = [
      '#type' => 'details',
      '#title' => '域名设置',
      '#group' => 'tabs',
    ];

    $form['site_models'] = [
      '#type' => 'details',
      '#title' => '站点模式',
      '#group' => 'tabs',
    ];

    $form['content_models'] = [
      '#type' => 'details',
      '#title' => '内容模式',
      '#group' => 'tabs',
    ];

    $form['url_settings'] = [
      '#type' => 'details',
      '#title' => 'URL设置',
      '#group' => 'tabs',
    ];

    $form['mip_settings'] = [
      '#type' => 'details',
      '#title' => 'MIP设置',
      '#group' => 'tabs',
    ];

    $form['mobile_settings'] = [
      '#type' => 'details',
      '#title' => '手机设置',
      '#group' => 'tabs',
    ];

    $form['other_settings'] = [
      '#type' => 'details',
      '#title' => '其他设置',
      '#group' => 'tabs',
    ];
    foreach (Element::children($form) as $child) {
      // 域名设置
      $domains = [
        'name',
        'model',
        'domain',
        'config_dir',
        'domains',
      ];
      if (in_array($child, $domains)) {
        $form['domain_settings'][$child] = $form[$child];
      }

      // 站点模式
      $site_mode = [
        'site_mode',
        '301_redirect_type',
        'prefix_multi',
//        'auto_prefix',
        'dis_custom_prefix_multi',
        'city_prefix_multi',
        'custom_prefix_multi',
      ];
      if (in_array($child, $site_mode)) {
        $form['site_models'][$child] = $form[$child];
        $form['site_models'][$child]['#weight'] = 0;
      }

      // 内容模式
      $content_models = [
        'content_type',
        'navigate_cache',
        'site_name',
        'site_column',
        'site_title',
        'site_sentence',
        'site_pics',
        'site_node',
        'site_intro',
        'number',
      ];
      if (in_array($child, $content_models)) {
        $form['content_models'][$child] = $form[$child];
        $form['content_models'][$child]['#weight'] = 0;
      }

      // URL设置
      $url_settings = [
        'url_mode',
        'url_cache',
        'url_type',
        'dis_url_rule',
        'url_rule',
      ];
      if (in_array($child, $url_settings)) {
        $form['url_settings'][$child] = $form[$child];
        $form['url_settings'][$child]['#weight'] = 0;
      }

      // MIP设置
      $mip_settings = [
        'mip_site',
        'mip_site_prefix',
        'ignore_mip_verify',
        'token_baidu',
        'token_cnzz',
      ];
      if (in_array($child, $mip_settings)) {
        $form['mip_settings'][$child] = $form[$child];
      }

      // 手机版设置
      $mobile_settings = [
        'mobile',
        'mobile_redirect',
        'mobile_prefix',
      ];
      if (in_array($child, $mobile_settings)) {
        $form['mobile_settings'][$child] = $form[$child];
      }

      // 其他设置
      $other_settings = [
        'other_tag',
        'other_sitemap',
        'dis_annoy_access',
        'dis_annoy_redirect',
        'annoy_redirect_url',
        'stream_statistic',
      ];
      if (in_array($child, $other_settings)) {
        $form['other_settings'][$child] = $form[$child];
        $form['other_settings'][$child]['#weight'] = 0;
      }
    }

    $delete_fiels = [
      'tabs',
      'domain_settings',
      'site_models',
      'content_models',
      'url_settings',
      'mip_settings',
      'mobile_settings',
      'other_settings',
      'langcode',
      'actions',
      'status',
    ];

    foreach (Element::children($form) as $child) {
      if (!in_array($child, $delete_fiels)) {
        unset($form[$child]);
      }
    }

    // weight
    $form['site_models']['site_mode']['#weight'] = 0;
    $form['content_models']['navigate_cache']['#weight'] = -10;
    $form['content_models']['content_type']['#weight'] = 20;
    $form['url_settings']['url_rule']['#weight'] = 0;

    $form['mip_settings']['mip_site']['#weight'] = 0;
    $form['mip_settings']['mip_site_prefix']['#weight'] = 5;
    $form['mip_settings']['ignore_mip_verify']['#weight'] = 10;
    $form['mip_settings']['token_baidu']['#weight'] = 15;
    $form['mip_settings']['token_cnzz']['#weight'] = 20;

    $form['mobile_settings']['mobile_prefix']['#weight'] = 30;
    $form['other_settings']['stream_statistic']['#title'] = '<span class="description-red">独立</span>流量统计代码';
    $form['other_settings']['stream_statistic']['#type'] = 'text_format';
    $form['other_settings']['stream_statistic']['#format'] = 'plain_text';


    // 泛域名前缀
    $form['site_models']['prefix_multi']['#states'] = [
      'visible' => [
        'input[name="site_mode"]' => [
          'value' => 1,
        ],
      ],
    ];
//    $form['site_models']['prefix_multi']['widget']['#default_value'] = 0;
    // 泛域名前缀勾选时，动态切换部分字段.

    if (isset($form['site_models']['dis_custom_prefix_multi'])) {
      $form['site_models']['dis_custom_prefix_multi']['#states'] = [
        'visible' => [
          'input[name="prefix_multi"]' => [
            'value' => 1,
          ],
          'input[name="site_mode"]' => [
            'value' => 1,
          ],
        ],
      ];
    }

    if (isset($form['site_models']['city_prefix_multi'])) {
      $form['site_models']['city_prefix_multi']['#states'] = [
        'visible' => [
          'input[name="prefix_multi"]' => [
            'value' => 1,
          ],
          'input[name="site_mode"]' => [
            'value' => 1,
          ],
        ],
      ];
    }

    if (isset($form['site_models']['custom_prefix_multi'])) {
      $form['site_models']['custom_prefix_multi']['#states'] = [
        'visible' => [
          'input[name="prefix_multi"]' => [
            'value' => 1,
          ],
          'input[name="site_mode"]' => [
            'value' => 1,
          ],
        ],
      ];
    }

    // 重新定义auto_prefix的输出方式
    $autoPrefix = \Drupal::service('seo_station.manager')->loadAutoPrefixByStation($form_state);
    $autoPrefix = reset($autoPrefix);
    $form['site_models']['auto_prefix'] = [
      '#type' => 'item',
      '#markup' => '<span class="description-legend">自动生成前缀</span>',
      '#states' => [
        'visible' => [
          'input[name="prefix_multi"]' => [
            'value' => 0,
          ],
          'input[name="site_mode"]' => [
            'value' => 1,
          ],
        ],
      ],
    ];
    // 自定义(推荐)
    $form['site_models']['auto_prefix']['step'] = [
      '#type' => 'textfield',
      '#title' => '前缀级数',
      '#title_display' => 'invisible',
      '#size' => 5,
      '#maxlength' => 5,
      '#min' => 1,
      '#max' => 5,
      '#placeholder' => '1',
      '#default_value' => !empty($autoPrefix->step->value) ? $autoPrefix->step->value : 1 ,
      '#field_prefix' => '生成级数:',
      '#prefix' => '<div class="d-inline-flex">',
      '#states' => [
        'visible' => [
          'input[name="prefix_multi"]' => [
            'value' => 0,
          ],
        ],
      ],
    ];

    $form['site_models']['auto_prefix']['start'] = [
      '#title' => '开始',
      '#title_display' => 'invisible',
      '#type' => 'textfield',
      '#size' => 5,
      '#maxlength' => 5,
      '#min' => 1,
      '#max' => 5,
      '#placeholder' => '1',
      '#default_value' => !empty($autoPrefix->start->value) ? $autoPrefix->start->value : 4 ,
      '#field_prefix' => '级, 随机范围: ',
      '#states' => [
        'visible' => [
          'input[name="prefix_multi"]' => [
            'value' => 0,
          ],
        ],
      ],
    ];
    $form['site_models']['auto_prefix']['end'] = [
      '#title' => '结束',
      '#title_display' => 'invisible',
      '#type' => 'textfield',
      '#size' => 5,
      '#maxlength' => 5,
      '#min' => 1,
      '#max' => 5,
      '#placeholder' => '1',
      '#default_value' => !empty($autoPrefix->end->value) ? $autoPrefix->end->value : 5 ,
      '#field_prefix' => '至',
      '#field_suffix' => '位数',
      '#suffix' => '</div>',
      '#states' => [
        'visible' => [
          'input[name="prefix_multi"]' => [
            'value' => 0,
          ],
        ],
      ],
    ];
    $form['site_models']['multi_help'] = [
      '#type' => 'item',
      '#markup' => '<p><strong>单域名</strong></p>
<p><span class="description-red">单域名最终生成链接数: <strong>每个域名提取数量 * 域名数 </strong></span> </p>
<p><strong>泛域名</strong></p>
<p><span class="description-red">1. 自动生成时: 泛域名最终生成的链接数: <strong>域名数 * 生成级数 * 每个域名提取数量<sup>2</sup></strong></span></p>
<p>2. 自定义（推荐）<strong>待更新</strong></p>',
    ];

    // 删除暂时未实现的功能
    unset($form['url_settings']);
    unset($form['mip_settings']);
    unset($form['mobile_settings']);
    unset($form['other_settings']);

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $domain = $form_state->getValue('domain');
    if (empty($domain[0]['value'])) {
      $form_state->setError($form['domain_settings']['domain'], '域名不能为空.');
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $x = 'a';
    parent::submitForm($form, $form_state); // TODO: Change the autogenerated stub
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    \Drupal::service('seo_station.manager')->setAutoPrefix2Station($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('已创建网站%label.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('已创建网站%label.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.seo_station.canonical', ['seo_station' => $entity->id()]);
  }
}
