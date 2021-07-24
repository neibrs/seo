<?php

namespace Drupal\seo_textdata;

class TextdataManager implements TextdataManagerInterface {

  /**
   * @param $theme_info
   *
   * @return mixed|string
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getWebname($theme_info) {
    $sitename = '';
    $host = \Drupal::request()->getHost();
    $storage = \Drupal::entityTypeManager()->getStorage('seo_station_address');
    $query = $storage->getQuery();
    $query->condition('domain', $host); // 不能有重复的, 网站名称固化到完整域名上
    $ids = $query->execute(); // 不能有多个.
    if (!empty($ids)) {
      $entity = $storage->load(reset($ids));
      $sitename = $entity->webname->value;
    }
    else {
      // 网站名称
      $textdata_storage = \Drupal::entityTypeManager()->getStorage('seo_textdata');
      $textdata_query = $textdata_storage->getQuery();
      $textdata_query->condition('type', 'webname');
      $textdata_query->condition('model.entity.config_dir', $theme_info['seo_theme']);
      $ids = $textdata_query->execute();
      if (empty($ids)) {
        return '';
      }
      $textdata = $textdata_storage->load($ids[array_rand($ids, 1)]);

      if (!empty($textdata->attachment->target_id)) {
        $webnames = getTextdataArrayFromUri($textdata->attachment->entity->getFileUri());
        $sitename = $webnames[array_rand($webnames, 1)];
      }
    }

    return $sitename;
  }
}
