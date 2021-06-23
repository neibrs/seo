<?php

namespace Drupal\seo_station_tkdb;


use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
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

}
