<?php

/**
 * @file
 * Enables modules and site configuration for a airui site installation.
 */

use Drupal\Core\File\FileSystemInterface;
use Drupal\contact\Entity\ContactForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\Url;
use Drupal\language\Entity\ConfigurableLanguage;

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function airui_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
  $form['#title'] = '站点设置';

  $form['site_information'] = [
    '#type' => 'container',
  ];
  $form['site_information']['site_name'] = [
    '#type' => 'value',
    '#value' => '艾瑞SEO',
  ];
  $form['site_information']['site_mail'] = [
    '#type' => 'value',
    '#value' => 'admin@airui.com',
  ];
  $form['admin_account']['#title'] = '管理员账号设置';
  $form['admin_account']['account']['name']['#title'] = '用户名';
  $form['admin_account']['account']['name']['#default_value'] = 'admin';
  unset($form['admin_account']['account']['name']['#description']);

  $form['admin_account']['account']['pass'] = [
    '#type' => 'textfield',
    '#title' => '密码',
    '#default_value' => 'admin',
  ];

  $form['admin_account']['account']['mail']['#title'] = '邮箱';
  $form['admin_account']['account']['mail']['#default_value'] = 'admin@airui.com';

  $form['regional_settings']['site_default_country']['#default_value'] = 'CN';
  $form['regional_settings']['date_default_timezone']['#default_value'] = 'Asia/Shanghai';

  unset($form['regional_settings']);
  unset($form['update_notifications']);

  $form['actions']['submit']['#value'] = '下一步';
  $form['#submit'][] = 'airui_form_install_configure_submit';
}

/**
 * Submission handler to sync the contact.form.feedback recipient.
 */
function airui_form_install_configure_submit($form, FormStateInterface $form_state) {
  $site_mail = $form_state->getValue('site_mail');
  ContactForm::load('feedback')->setRecipients([$site_mail])->trustData()->save();
}

/**
 * Implements hook_form_FORM_ID_alter() for install_select_language_form.
 */
function airui_form_install_select_language_form_alter(&$form, FormStateInterface $form_state, $form_id) {
  $form['#title'] = '选择语言环境';
  $link_to_english = install_full_redirect_url(['parameters' => ['langcode' => 'en']]);
  $form['help']['#markup'] = '<p>从<a href="http://localize.drupal.org">Drupal翻译站点</a>下载翻译文件. 如果您想安装英文环境，选择<a href="' . $link_to_english . '">English</a>.</p>';

  $site_path = \Drupal::getContainer()->getParameter('site.path');
  //  $files_directory = $site_path . '/files';
  $normal_directory = $site_path . '/files/normal';

  $source = drupal_get_path('profile', 'airui') . '/软件协议.txt';
  $file_system = Drupal::service('file_system');
  $file_system->prepareDirectory($normal_directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
  $file_system->copy($source, PublicStream::basePath() . '/normal');

  $xieyi = file_get_contents($source);
  $form['xieyi'] = [
    '#required' => TRUE,
    '#type' => 'checkbox',
    '#title' => '请先同意协议',
    '#title_display' => 'invisible',
    '#field_suffix' => [
      '#markup' => Link::fromTextAndUrl('使用本程序请遵守国家法律法规、并遵守本软件使用协议', Url::fromRoute('<front>'))
        ->toString(),
    ],
    '#default_value' => 1,
    '#disabled' => 1,
  ];

  $form['actions']['submit']['#value'] = '开始安装';
}

/**
 * Implements hook_form_FORM_ID_alter() for install_settings_form.
 */
function airui_form_install_settings_form_alter(&$form, FormStateInterface $form_state, $form_id) {
  $form['#title'] = '数据库配置';
  $form['driver']['#title'] = '数据库类型';

  unset($form['driver']['#options']['sqlite']);
  unset($form['driver']['#options']['pgsql']);

  $form['settings']['mysql']['database']['#title'] = '数据库名';
  $form['settings']['mysql']['database']['#default_value'] = 'airui';
  $form['settings']['mysql']['username']['#title'] = '用户名';
  $form['settings']['mysql']['username']['#default_value'] = 'root';
  $form['settings']['mysql']['password'] = [
    '#type' => 'textfield',
    '#title' => '密码',
    '#default_value' => '',
  ];
  $form['settings']['mysql']['advanced_options']['#title'] = '高级选项';
  $form['settings']['mysql']['advanced_options']['prefix']['#title'] = '表前缀';
  $form['settings']['mysql']['advanced_options']['prefix']['#description'] = '如果是共享数据，每个应用最好加一个独特的表前缀, 避免表结构冲突.';
  $form['settings']['mysql']['advanced_options']['host']['#title'] = '主机';
  $form['settings']['mysql']['advanced_options']['port']['#title'] = '端口';

  unset($form['settings']['sqlite']);
  unset($form['settings']['pgsql']);

  $form['actions']['save']['#value'] = '下一步';
}

/**
 * Implements hook_install_tasks_alter().
 */
function airui_install_tasks_alter(&$tasks, $install_state) {
  $tasks['install_select_language']['display_name'] = '选择语言';
  $tasks['install_verify_requirements']['display_name'] = '验证安装环境';
  $tasks['install_settings_form']['display_name'] = '安装数据库';
  $tasks['install_profile_modules']['display_name'] = '初始化软件环境';
  $tasks['install_profile_modules']['function'] = 'airui_install_profile_modules';
  $tasks['install_profile_themes']['run'] = INSTALL_TASK_SKIP;
  $tasks['install_install_profile']['run'] = INSTALL_TASK_SKIP;
  $tasks['install_import_translations']['run'] = INSTALL_TASK_SKIP;
  $tasks['install_configure_form']['display_name'] = '站点设置';
  $tasks['install_finish_translations']['run'] = INSTALL_TASK_SKIP;
}

/**
 * Installs required modules via a batch process.
 *
 * @param $install_state
 *   An array of information about the current installation state.
 *
 * @return
 *   The batch definition.
 */
function airui_install_profile_modules(&$install_state) {
  $sql_path = drupal_get_path('profile', 'airui') . '/airui.sql';
  $connection = \Drupal::database();
  $options = $connection->getConnectionOptions();
  $sql_bash = 'mysql -h ' . $options['host'] . ' -P' . $options['port']
    . ' -u' . $options['username'] . ' -p' . $options['password']
    . ' ' . $options['database'];
  $sql_bash .= ' < ' . $sql_path;

  $output = $result = '';
  if (!exec($sql_bash, $output, $result)) {
    // TODO
  }
  return [];
}
