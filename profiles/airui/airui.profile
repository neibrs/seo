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

function airui_preprocess_install_page(&$variables) {
  $variables['site_version'] = '1.0';
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
  $tasks['install_import_translations']['function'] = 'airui_install_import_translation';
  $tasks['install_configure_form']['display_name'] = '站点设置';

  // 取消翻译文件下载,跳过此步骤.
  //  $tasks['install_download_translation']['run'] = INSTALL_TASK_SKIP;
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
//  $sql_path = drupal_get_path('profile', 'airui') . '/airui.sql';
//  $connection = \Drupal::database();
//  $options = $connection->getConnectionOptions();
//  $sql_bash = 'mysql -h ' . $options['host'] . ' -P' . $options['port']
//    . ' -u' . $options['username'] . ' -p' . $options['password']
//    . ' ' . $options['database'] . ' < ' . $sql_path;
//
//  exec($sql_bash);

  $operations = [];
  $batch = [
    'operations' => $operations,
  // t('安装@drupal', ['@drupal' => drupal_install_profile_distribution_name()]),
    'title' => '初始化软件环境',
  // t('The installation has encountered an error.'),
    'error_message' => '安装过程出错',
  ];
  return $batch;
}

/**
 * Implements callback_batch_operation().
 *
 * Performs batch installation of modules.
 */
function _airui_install_module_batch($module, $module_name, &$context) {
  \Drupal::service('module_installer')->install([$module], FALSE);
  $context['results'][] = $module;
  $context['message'] = t('已安装 %module 模块.', ['%module' => $module_name]);
}

// Fixed, 翻译文件安装过程，屏蔽下载功能.
function airui_install_import_translation(&$install_state) {
  \Drupal::moduleHandler()->loadInclude('locale', 'translation.inc');

  // If there is more than one language or the single one is not English, we
  // should import translations.
  $operations = airui_install_download_additional_translations_operations($install_state);
  $languages = \Drupal::languageManager()->getLanguages();
  if (count($languages) > 1 || !isset($languages['en'])) {
    //    $operations[] = ['_install_prepare_import', [array_keys($languages), $install_state['server_pattern']]];
    //
    //    // Set up a batch to import translations for drupal core. Translation import
    //    // for contrib modules happens in install_import_translations_remaining.
    //    foreach ($languages as $language) {
    //      if (locale_translation_use_remote_source()) {
    //        $operations[] = ['locale_translation_batch_fetch_download', ['drupal', $language->getId()]];
    //      }
    //      $operations[] = ['locale_translation_batch_fetch_import', ['drupal', $language->getId(), []]];
    //    }

    module_load_include('fetch.inc', 'locale');
    $batch = [
      'operations' => $operations,
      'title' => t('Updating translations.'),
      'progress_message' => '',
      'error_message' => t('Error importing translation files'),
      'finished' => 'locale_translation_batch_fetch_finished',
      'file' => drupal_get_path('module', 'locale') . '/locale.batch.inc',
    ];
    return $batch;
  }
}

function airui_install_download_additional_translations_operations(&$install_state) {
  \Drupal::moduleHandler()->loadInclude('locale', 'bulk.inc');

  $langcode = $install_state['parameters']['langcode'];
  if (!($language = ConfigurableLanguage::load($langcode))) {
    // Create the language if not already shipped with a profile.
    $language = ConfigurableLanguage::createFromLangcode($langcode);
  }
  $language->save();

  // If a non-English language was selected, change the default language and
  // remove English.
  if ($langcode != 'en') {
    \Drupal::configFactory()->getEditable('system.site')
      ->set('langcode', $langcode)
      ->set('default_langcode', $langcode)
      ->save();
    \Drupal::service('language.default')->set($language);
    if (empty($install_state['profile_info']['keep_english'])) {
      if ($lang = ConfigurableLanguage::load('en')) {
        $lang->delete();
      }
    }
  }

  // If there is more than one language or the single one is not English, we
  // should download/import translations.
  $languages = \Drupal::languageManager()->getLanguages();
  $operations = [];
  //  foreach ($languages as $langcode => $language) {
  //    // The installer language was already downloaded. Available translations are
  //    // stored in $install_state. Check downloads for the other languages if any.
  //    // Ignore any download errors here, since we are in the middle of an install
  //    // process and there is no way back. We will not import what we cannot
  //    // download.
  //    if (!isset($install_state['translations'][$langcode])) {
  //      $operations[] = ['install_check_translations', [$langcode, $install_state['server_pattern']]];
  //    }
  //  }
  return $operations;
}
