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

    $title = $this->getTitle();
    $body = $this->getBody();
    if (empty($title) || empty($body)) {
      return;
    }
    try {
      $values = [
        'type' => 'article',
        'title' => mb_substr($title[0], 0, 60),
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
  protected function getTitle() {
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
