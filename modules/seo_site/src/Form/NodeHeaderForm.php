<?php

namespace Drupal\seo_site\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class NodeHeaderForm extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'seo_site_node_header';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $options = [
      0 => '关闭',
      1 => '开启',
    ];

    $form['tags'] = [
      '#type' => 'radios',
      '#title' => '主要关键词',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['author'] = [
      '#type' => 'radios',
      '#title' => '网站作者',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['copyright'] = [
      '#type' => 'radios',
      '#title' => '网站版权',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['source'] = [
      '#type' => 'radios',
      '#title' => '文章版权作者来源',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['icon'] = [
      '#type' => 'radios',
      '#title' => '版权图片',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['apple_touch_icon'] = [
      '#type' => 'radios',
      '#title' => '苹果版权图片',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['mask_icon'] = [
      '#type' => 'radios',
      '#title' => '苹果版权图片',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['msapplication'] = [
      '#type' => 'radios',
      '#title' => 'Win系统版权',
      '#options' => $options,
      '#default_value' => 1,
    ];

    $form['og'] = [
      '#type' => 'container',
      '#title' => 'OG',
    ];
    $form['og']['og_type'] = [
      '#type' => 'radios',
      '#title' => 'Og系列',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['og']['og_type'] = [
      '#type' => 'radios',
      '#title' => 'Og类型',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['og']['og_title'] = [
      '#type' => 'radios',
      '#title' => '富媒体标题',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['og']['og_description'] = [
      '#type' => 'radios',
      '#title' => '富媒体描述',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['og']['og_url'] = [
      '#type' => 'radios',
      '#title' => '富媒体地址',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['og']['og_image'] = [
      '#type' => 'radios',
      '#title' => '富媒体LOGO',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['article'] = [
      '#type' => 'container',
      '#title' => '文章',
    ];
    $form['article']['author'] = [
      '#type' => 'radios',
      '#title' => '文章作者',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['article']['published_time'] = [
      '#type' => 'radios',
      '#title' => '文章发布时间',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['twitter'] = [
      '#type' => 'container',
      '#title' => 'Twitter',
    ];
    $form['twitter']['card'] = [
      '#type' => 'radios',
      '#title' => '卡片类型',
    ];
    $form['twitter']['image'] = [
      '#type' => 'radios',
      '#title' => '版权图片',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['twitter']['title'] = [
      '#type' => 'radios',
      '#title' => 'Twitter标题',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['twitter']['creator'] = [
      '#type' => 'radios',
      '#title' => '创作者版权',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['twitter']['site'] = [
      '#type' => 'radios',
      '#title' => 'Twitter作者版权',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['twitter']['description'] = [
      '#type' => 'radios',
      '#title' => 'twitter描述',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['dns'] = [
      '#type' => 'textarea',
      '#title' => 'DNS',
    ];
    $form['alternate'] =[
      '#type' => 'radios',
      '#title' => 'PC版备选页面',
      '#options' => $options,
      '#default_value' => 1,
    ];
    $form['bar_style'] = [
      '#type' => 'radios',
      '#title' => '滚动条风格',
      '#options' => $options,
      '#default_values' => 1,
    ];

    $form['mobile_agent'] = [
      '#type' => 'textarea',
      '#title' => 'Mobile agent',
    ];

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
