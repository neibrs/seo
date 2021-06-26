<?php

namespace Drupal\seo_station_tkdb;


use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TkdbManager extends TkdbManagerInterface implements ContainerInjectionInterface {

  protected $tkdb_storage;

  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->tkdb_storage = $entity_type_manager->getStorage('seo_station_tkdb');
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * @param $keywords
   *
   * $keywords = [
   *  'name' => $v['template'],
   *  'type' => $type,
   *  'model' => $this->model,
   *  'group' => $this->station,
   *  ];
   * @return mixed
   */
  public function getTkdb($keywords) {
    $query = $this->tkdb_storage->getQuery();
    foreach ($keywords as $k => $v) {
      if (empty($v)) {
        $query->condition($k, $v, 'IS NULL');
      }
      else {
        $query->condition($k, $v);
      }
    }
    $ids = $query->execute();

    return $ids;
  }

  public function getDefaultContent($tkdb, $type, $template) {
    $content = '';

    if (!empty($tkdb)) {
      $content = $tkdb->get('content')->value;

      return $content;
    }
    switch ($type) {
      case 'title':
        switch ($template) {
          case 'index':
            $content = '{网站名称}';
            break;
          case 'list':
            $content = '{$title}主题-{网站名称}';
            break;
          case 'show':
            $content = '{$title}-{网站名称}';
            break;
        }
        break;
      case 'keywords':
        switch ($template) {
          case 'index':
            $content = '{网站名称}';
            break;
          case 'list':
          case 'show':
            $content = '{$title}';
            break;
        }
        break;
      case 'description':
        switch ($template) {
          case 'index':
            $content = '{网站名称}';
            break;
          case 'list':
          case 'show':
            $content = '{$content}';
            break;
        }
        break;
      case 'content':
        $content = '<p>{$content}</p>
<p>{$content1}{$content2}</p>
<p><img src="{$pic}" alt="{$title}" /></p>
<p>{$content3}{$content4}{$content5}</p>
<p><img src="{$pic1}" alt="{$title}" /></p>
<p>{$content6}{$content7}</p>
<p><img src="{$pic2}" alt="{$title}" /></p>
<p>{$content8}</p>
<p>{$content9}{$content10}</p>
<p>{$content11}{$content12}</p>
<p>{$content13}{$content14}</p>';
        break;
    }

    return $content;
  }

  public function updateTkdbSubmitForm(array &$form, FormStateInterface $form_state) {
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
  }

  // 和StationTkdbFormg一样的代码
  protected function updateOrCreateTkdb($val, $type) {
    foreach ($val as $k => $v) {
      // 创建tkdb实体
      $keywords = [
        'name' => $v['template'],
        'type' => $type,
        'template' => $v['template'],
        'model' => NULL,
        'group' => NULL,
      ];
      $values = [
        'content' => $v['content'],
      ];
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

  /**
   * 获取一条TKDB规则
   * $arr = [
   *  'station' => $station,
   *  'domain' => $d,
   *  'replacement' => '***',
   *  ];
   * @param $data
   * 获取规则：
   *  网站分组下存在泛域名设置规则
   *  网站分组下存在主配置规则
   *  网站模型下存在规则
   *  获取默认规则
   */
  public function getTkdbRule($data) {
    $tkdb_manager = \Drupal::service('seo_station_tkdb.manager');
    $tkdb_storage = \Drupal::entityTypeManager()->getStorage('seo_station_tkdb');

    $model = '';
    if ($data['station']) {
      $model = \Drupal::entityTypeManager()->getStorage('seo_station')->load($data['station'])->model->target_id;
    }
    //    网站分组下存在泛域名设置规则
    $keywords = [
      'model' => $model,
      'group' => $data['station'],
      'wild' => $data['station'],
    ];
    $ids = $tkdb_manager->getTkdb($keywords);
    if (!empty($ids)) {
      return $ids;
    }

    //    网站分组下存在主配置规则
    $keywords['wild'] = NULL;
    $ids = $tkdb_manager->getTkdb($keywords);
    if (!empty($ids)) {
      return $ids;
    }
    //    网站模型下存在规则
    $keywords['group'] = NULL;
    $ids = $tkdb_manager->getTkdb($keywords);
    if (!empty($ids)) {
      return $ids;
    }

    //    获取默认规则
    $keywords['model'] = NULL;
    $ids = $tkdb_manager->getTkdb($keywords);
    if (!empty($ids)) {
      return $ids;
    }

  }

  public function getTkdbShowRule($data) {
    $tkdb_storage = \Drupal::entityTypeManager()->getStorage('seo_station_tkdb');
    $ids = $this->getTkdbRule($data);
    $query = $tkdb_storage->getQuery();
    if (!empty($ids)) {
      $query->condition('id', $ids, 'IN');
    }
    $query->condition('template', 'show');
    $xids = $query->execute();

    return $tkdb_storage->loadMultiple($xids);
  }
}
