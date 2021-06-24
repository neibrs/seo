<?php

namespace Drupal\seo_station\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;

/**
 * @QueueWorker(
 *   id = "link_rule_process",
 *   title = @Translation("Link Rule"),
 *   cron = {"time" = 60}
 * )
 */
class LinkRuleProcess extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    // TODO， 暂时只做一个新闻类型的网站文章数据.
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');

    $body = $this->getBody();
    $title = $this->getTitle($body[0]);
    if (empty($title)) {
      return;
    }
    try {
      // TODO
      if (\Drupal::moduleHandler()->moduleExists('seo_station_tkdb')) {
        // 这里添加tkdb规则,用以对每个node进行定义。
        $tkdb_manager = \Drupal::service('seo_station_tkdb.manager');
        $tkdb_rules = $tkdb_manager->getTkdbShowRule($data);
        // 根据规则，寻找可替换的TKDB. 这里寻找的是show规则.

        // 设置title, keywords, description, content. metatag.

      }

      $values = [
        'type' => 'article',
        'title' => is_array($title) ? mb_substr($title[0], 0, 60) : mb_substr($title, 0, 60),
        'field_image' => '',
        'body' => [
          'value' => $body[1],
          'summary' => mb_substr($body[1], 0, 100),
          'format' => 'basic_html',
        ],
      ];

      // 创建该别名的文章数据.
      $node = $node_storage->create(['type' => 'article']);
      foreach ($values as $key => $val) {
        $node->set($key, $val);
      }
      $node->save();
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addWarning($e);
    }

    $path_alias_storage = \Drupal::entityTypeManager()->getStorage('path_alias');
    $path_alias = $path_alias_storage->create();
    $path_alias->set('path', '/node/' . $node->id())
      ->set('alias', $data['path'])
      ->save();
  }

  // 随机获取title类型的标题库的文件一份
  protected function getTitle($body_title = NULL) {
    $storage = \Drupal::entityTypeManager()->getStorage('seo_textdata');
    $title = $storage->loadByProperties([
      'type' => 'title',
    ]);
    $title = reset($title);

    if (empty($title)) {
      return [];
    }

    $uri = $title->get('attachment')->entity->getFileUri();

    // 随机模式
    $data = seo_textdata_auto_read($uri);
    $ds = array_unique(explode('-||-', str_replace("\r\n","-||-", $data)));
    if ($sub_data = str_replace("\n","-||-", $data)) {
      $ds = array_unique(explode('-||-', $sub_data));
    }
    if (in_array($body_title, $ds)) {
      return $body_title;
    }
    $dst = $ds[mt_rand(0, count($ds))];
    return explode('******', $dst);
  }

  protected function getBody() {
    $storage = \Drupal::entityTypeManager()->getStorage('seo_textdata');
    $body = $storage->loadByProperties([
      'type' => 'article',
    ]);
    $title = reset($body);

    $uri = $title->get('attachment')->entity->getFileUri();

    // 随机模式
    $data = seo_textdata_auto_read($uri);
    $ds = array_unique(explode('-||-', str_replace("\r\n","-||-", $data)));
    $dst = $ds[mt_rand(0, count($ds))];
    return explode('******', $dst);
  }
}
