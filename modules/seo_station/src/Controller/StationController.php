<?php

namespace Drupal\seo_station\Controller;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StationController extends ControllerBase implements ContainerInjectionInterface {

  /** @var \Drupal\Core\Entity\EntityTypeManagerInterface */
  protected $entityTypeManager;

  /** @var \Drupal\seo_textdata\TextdataManagerInterface */
  protected $textdataManager;
  /**
   * {@inheritDoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->textdataManager = \Drupal::service('seo_textdata.manager');
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  public function getExtract($number = 1) {
    $data = $this->getStationLinks($number);

    $build = [];
    $build['#theme'] = 'seo_station_extract';
    $build['#content'] = $data;
    $build['#cache']['max-age'] = 0;

    return $build;
  }

  protected function getStationLinks($number = 1) {
    $stations = \Drupal::entityTypeManager()->getStorage('seo_station')->loadMultiple();

    $data = [];
    foreach ($stations as $station) {
      [$domains, $rule] = $this->getWildDomains($station, $number);
      $data[$station->id()] = $this->getSingleDomainRule($station, $domains, $rule, $number);
    }

    return $data;
  }

  /**
   * 站点模式为单域名时
   *
   * @param string $station
   * @param array $domains
   * @param string $rule
   * @param int $number
   *
   * @return array
   */
  function getSingleDomainRule($station = '', $domains = [], $rule = '', $number = 1) {
    $links = [];
    // 单域名时
    foreach ($domains as $domain) {
      foreach ($domain as $d) {
        if ($d === '0' || empty($d)) {
          continue;
        }

        // 每个域名下只生成一条真实数据
        $real_data = \Drupal::service('seo_station.token.manager')->generate([$rule]);
        $replacement = reset($real_data);
        $links[] = $d . '/' . $replacement;

        // 生成真实的链接数据，并加入相应的队列.
        // 插入队列.
        $arr = [
          'station' => $station,
          'domain' => $d,
        ];

        $new_arr = $arr + ['replacement' => $replacement];
        // 方案一
        if ($number > 10) {
          \Drupal::moduleHandler()->alter('link_rule_data', $new_arr);
        }
        // 方案二
        else {
          $this->generateNode($new_arr);
        }

        // 所有生成的泛域名需要保存.
        unset($arr['station']);
        $arr['replacement'] = $replacement;
        $arr['name'] = $d . '/' . $replacement;
        // 方案一

        if ($number > 10) {
          \Drupal::moduleHandler()->alter('station_address_process', $new_arr);
        }
        else {
          // 方案二
          $storage = \Drupal::entityTypeManager()->getStorage('seo_station_address');
          $station_address = $storage->create($arr);
          $station_address->save();
        }
      }
    }

    return $links;
  }

  // 获取当前系统域名列表,包括Station里面定义的域名，以及Station里面定义的所有泛域名列表.
  public function getDomainIndex() {
    // 1. 获取所有泛域名
    $stations = $this->entityTypeManager
      ->getStorage('seo_station')
      ->loadMultiple();
    $data = [];
    foreach ($stations as $station) {
      try {
        [$domains,] = $this->getWildDomains($station);
      } catch (InvalidPluginDefinitionException | PluginNotFoundException $e) {
      }
      if (!empty($domains)) {
        $data = array_merge($data, $domains);
      }
    }

    $build = [];
    $build['#theme'] = 'seo_station_domain_index';
    $build['#content'] = $data;

    return $build;
  }

  /**
   * @param $station
   * @param int $number
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getWildDomains($station, $number = 1): array {
    $conditions = [
      'model' => $station->model->target_id,
      'type' => $station->url_mode->value == 0 ? 'dynamic' : 'static',
    ];
    // 这里待确定是否只用这一个模板。
    $template = [
      'template' => 'show',
    ];

    $conditions += $template;
    $url_rules = $this->entityTypeManager->getStorage('seo_station_model_url')->loadByProperties($conditions);
    $url_rules = reset($url_rules);

    if (empty($url_rules) || !$url_rules) {
      \Drupal::messenger()->addError('站群URL规则没有设置');
      return [];
    }

    // url 规则
    // 处理\r\n
    $rules = array_unique(explode(',', str_replace("\r\n",",", $url_rules->rule_url_content->value)));

    $rule = array_pop($rules);

    // 处理\n
    $rules = array_unique(explode(',', str_replace("\n",",", $rule)));
    $rule = array_pop($rules);

    // 提取所有域名
    $domains = [];
    $wild = $station->site_mode->value;
    // 单域名时
    $domains[] = array_unique(explode(',', str_replace("\r\n",",", $station->domain->value)));
    if ($wild) {
      // 单域名改泛域名时
      $domains = \Drupal::service('seo_station.manager')->getMultiDomainByStation($domains, $station, $number);
    }

    return [$domains, $rule];
  }

  /**
   * Execute cron for generate node data.
   */
  public function executeCron() {
    try {
      $cron_service = \Drupal::service('cron');
      $cron_service->run();
    }
    catch (\Exception $e) {
    }

    return ['#markup' => '成功生成数据'];
  }

  public function generateNode($data) {
    $station = $data['station'];
    // TODO， 暂时只做一个新闻类型的网站文章数据.
    $node_storage = $this->entityTypeManager->getStorage('node');

    $body = $this->textdataManager->getBody($station);
    if (empty($body)) {
      return;
    }
    // 找标题的原则是body的标题在title库里面找到了就用，没找到就使用自己的标题！如果自己的标题为空，则随机调用标题库里面的标题
//    $title = $this->getTitle($body[0], $station);
    $title = \Drupal::service('seo_textdata.manager')->getTitle($body[0], $station);
    if (empty($title)) {
      return;
    }
    try {
      // 初始化node的值, 及Site name.
      [$site_name, $tkdb_values] = $this->textdataManager->getTkdbValues($data, $station);

      // 这里会创建N多，但站群网站却只要几个，矛盾点，待优化
      $taxonomies = $this->textdataManager->getTaxonomyValues($station);

      // 提取文章分类到标题后缀
      // 构造一个tid的数组.
      $rand_tids = [];
      if (!empty($taxonomies)) {
        $rand_tids = array_rand($taxonomies, 5);
      }
      if (!is_array($rand_tids)) {
        $rand_tids[] = $rand_tids;
      }
      $rs_rand_tid = reset($rand_tids);
      $term = $taxonomies[$rs_rand_tid];
      $tkdb_values = $this->textdataManager->appendTaxonomy2Title($term, $tkdb_values, $site_name);

      $values = [
        'type' => 'article',
        'title' => is_array($title) ? mb_substr($title[0], 0, 60) : mb_substr($title, 0, 60),
        //        'field_image' => '',
        'body' => [
          'value' => $body[1],
          'summary' => mb_substr($body[1], 0, 100),
          'format' => 'basic_html',
        ],
        'path' => '/'.$data['replacement'],//Alias
        'field_metatag' => [
          'value' => serialize($tkdb_values),
        ],
        //TODO, Add taxonomy
        'field_tags' => $rand_tids, //$tids,
        'field_station' => $station->id(),
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
  }
}
