<?php

namespace Drupal\seo_station_tkdb\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StationTkdbForm extends FormBase {

  protected $model;
  protected $station;
  protected $wild;
  protected $entityTypeManager;
  protected $tkdb_storage;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->tkdb_storage = $this->entityTypeManager->getStorage('seo_station_tkdb');
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  public function getFormId() {
		return 'seo_station_tkdb_setting_form';
	}

	public function buildForm(array $form, FormStateInterface $form_state, $seo_station_model = NULL, $seo_station = NULL, $wild = NULL) {
    $this->model = $seo_station_model;
    $this->station = $seo_station;
    $this->wild = $wild;

		$form['tabs'] = [
			'#type' => 'horizontal_tabs',
			'#default_tab' => 'edit-default',
		];

		$types = \Drupal::entityTypeManager()->getStorage('seo_station_tkdb_type')->loadMultiple();
		foreach($types as $type) {
			$form[$type->id().'_settings'] = [
				'#type' => 'details',
				'#title' => $type->label() . '模板',
				'#group' => 'tabs',
			];
			if ($type->id() == 'title') {
				$form[$type->id() . '_settings']['#weight'] = 0;
				$form[$type->id().'_settings'][$type->id().'_tips'] = [
					'#type' => 'item',
					'#markup' => '<p><span class="description-red">
	支持使用模板标签，如：文章标题->{$title}、网站名称->{$web_name} 等，参考模板标签指南《模板调用标签说明文档》<br/>
	随机关键词->{$randkws}、{$randkws1}、{$randkws2}...(注：前提是设置了关键词插入)、泛域名城市名称->{$city}</span></p>
	<p><span class="description-blue">在前台模板文件中调用的标签为: {$toptitle}</span></p>',
				];
			}
      elseif ($type->id() == 'keywords') {
        $form[$type->id() . '_settings']['#weight'] = 5;
        $form[$type->id().'_settings'][$type->id().'_tips'] = [
          '#type' => 'item',
          '#markup' => '<p><span class="description-red">
	支持使用模板标签，如：文章标题->{$title}、网站名称->{$web_name} 等<br/>
	随机关键词->{$randkws}、{$randkws1}、{$randkws2}...(注：前提是设置了关键词插入)、泛域名城市名称->{$city}</span></p>
	<p><span class="description-blue">在前台模板文件中调用的标签为: {$toptitle}</span></p>',
        ];
      }
      elseif ($type->id() == 'description') {
        $form[$type->id() . '_settings']['#weight'] = 10;
        $form[$type->id().'_settings'][$type->id().'_tips'] = [
          '#type' => 'item',
          '#markup' => '<p><span class="description-red">
	支持使用模板标签，如：文章标题->{$title}、网站名称->{$web_name} 等<br/>
	随机关键词->{$randkws}、{$randkws1}、{$randkws2}...(注：前提是设置了关键词插入)、泛域名城市名称->{$city}</span></p>
	<p><span class="description-blue">在前台模板文件中调用的标签为: {$toptitle}</span></p>',
        ];
      }
			elseif ($type->id() == 'content') {
        $form[$type->id() . '_settings']['#weight'] = 15;
        $form[$type->id().'_settings'][$type->id().'_tips'] = [
          '#type' => 'item',
          '#markup' => '<p><span class="description-red">
系统将随机调用，仅用于内容页show.html
模板标签，如：内容句子1至20条：{$content}...{$content20}、图片地址：{$pic1}...{$pic20}、视频地址：{$video1}...{$video20}等
随机关键词->{$randkws}、{$randkws1}、{$randkws2}...(注：前提是设置了关键词插入)、泛域名城市名称->{$city}
在前台模板文件show.html中调用的标签为: {$body}，如果网站的内容模式选的是文章库内容则不会用这里</span></p>',
        ];
      }
			else {
				$form[$type->id() . '_settings']['#weight'] = 50;
			}

			// 统一设置表格
      $form[$type->id().'_settings'][$type->id().'_table'] = [
        '#type' => 'table',
        '#header' => ['id' => '模板', 'content' => '每行一条，系统将随机调用', 'template' => ''],
        '#empty' => '该模型还没有模板定义.',
      ];

			// 模型的模板定义数据
      $model = \Drupal::entityTypeManager()->getStorage('seo_station_model')->load($seo_station_model);
      $templates = explode(',', implode(',',[$model->main->value,$model->secondary->value]));
      $i = 0;
      foreach ($templates as $template) {
        if (empty($template)) {
          continue;
        }
        $form[$type->id().'_settings'][$type->id().'_table'][$i] = [
          'id' => [
            '#type' => 'item',
            '#markup' => $template,
          ],
          'content' => [
            '#type' => 'textarea',
            '#rows' => 3,
          ],
          'template' => [
            '#type' => 'hidden',
            '#value' => $template,
          ],
        ];
        $values = [
          'name' => $template,
          'type' => $type->id(),
          'template' => $template,
          'model' => $this->model,
          'group' => $this->station,
        ];
        // 泛域名设置
        if ($this->wild) {
          $values['wild'] = $this->station;
        }
        $ids = \Drupal::service('seo_station_tkdb.manager')->getTkdb($values);
        $tkdb = NULL;
        if (!empty($ids)) {
          $tkdb = $this->tkdb_storage->load(reset($ids));
        }

        $form[$type->id().'_settings'][$type->id().'_table'][$i]['content']['#default_value'] = \Drupal::service('seo_station_tkdb.manager')->getDefaultContent($tkdb, $type->id(), $template);
        $i++;
      }

		}

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => '保存设置',
    ];
		return $form;
	}

	public function submitForm(array &$form, FormStateInterface $form_state) {
	  foreach ($form_state->getValues() as $key => $val) {
      if ($key == 'title_table') {
        $this->updateOrCreateTkdb($val, 'title');
      }
      if ($key == 'keywords_table') {
        $this->updateOrCreateTkdb($val, 'keywords');
      }
      if ($key == 'description_table') {
        $this->updateOrCreateTkdb($val, 'description');
      }
      if ($key == 'content_table') {
        $this->updateOrCreateTkdb($val, 'content');
      }
    }
	  $this->messenger()->addMessage('保存成功！');
	}

	protected function updateOrCreateTkdb($val, $type) {
    foreach ($val as $k => $v) {
      // 创建tkdb实体
      $keywords = [
        'name' => $v['template'],
        'type' => $type,
        'template' => $v['template'],
        'model' => $this->model,
        'group' => $this->station,
      ];
      $values = [
        'content' => $v['content'],
      ];
      if ($this->wild) {
        $keywords['wild'] = $this->station;
      }
      // 创建？
      $ids = \Drupal::service('seo_station_tkdb.manager')->getTkdb($keywords);
      // 更新？
      if (!empty($ids)) {
        $tkdb = $this->tkdb_storage->load(reset($ids));
        foreach ($keywords + $values as $k => $v) {
          $tkdb->set($k, $v);
        }
        $tkdb->save();
      }
      else {
        $this->tkdb_storage
          ->create($keywords + $values)
          ->save();
      }
    }
  }

}
