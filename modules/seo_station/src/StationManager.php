<?php

namespace Drupal\seo_station;

use Drupal\Component\Utility\Random;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;

class StationManager implements StationManagerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * StationManager constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  public function setAutoPrefix2Station(array $form, FormStateInterface $form_state) {
    $entity = $form_state->getFormObject()->getEntity();

    $values_conditions = [
      'station' => $entity->id(),
      'name' => $entity->label(),
    ];
    $autoprefix_storage = $this->entityTypeManager->getStorage('seo_autoprefix');
    $autoprefixes = $this->loadAutoPrefixByStation($form_state);

    $values_auto_conditions = [
      'step' => $form_state->getValue('step'),
      'start' => $form_state->getValue('start'),
      'end' => $form_state->getValue('end'),
    ];

    $autoprefix = reset($autoprefixes);
    if (empty($autoprefixes)) {
      // create
      if (!empty($values_conditions['station'])) {
        $autoprefix = $autoprefix_storage->create($values_conditions + $values_auto_conditions);
        $autoprefix->save();
      }
    }
    else {
      // update
      foreach ($values_auto_conditions as $key => $values_auto_condition) {
        $autoprefix->set($key, $values_auto_condition);
      }
      $autoprefix->save();
    }

    $entity->set('auto_prefix', $autoprefix->id());
    $entity->save();
  }

  public function loadAutoPrefixByStation(FormStateInterface $form_state) {
    $entity = $form_state->getFormObject()->getEntity();

    $values_conditions = [
      'station' => $entity->id(),
      'name' => $entity->label(),
    ];

    if (empty($entity)) {
      return [];
    }
    $storage = $this->entityTypeManager->getStorage('seo_autoprefix');
    $query = $storage->getQuery();
    foreach ($values_conditions as $key => $values_condition) {
      $query->condition($key, $values_condition);
    }
    $ids = $query->execute();
    if (empty($ids)) {
      return [];
    }
    else {
      return $storage->loadMultiple($ids);
    }
  }

  // 根据Station泛域名生成规则返回所有域名
  public function getMultiDomainByStation($domains = [], $station = NULL, $number = 1) {
    $doms = [];
    $domains = reset($domains);
    if (empty($station)) {
      return [];
    }

    // 自动生成
    if (!$station->prefix_multi->value) {
      $autoPrefix = $station->auto_prefix->entity;
      $step = $autoPrefix->step->value;
      $start = $autoPrefix->start->value;
      $end = $autoPrefix->end->value;

      do {
        foreach ($domains as $key => $domain) {
          $domain = str_replace("0\n","", $domain);
          if ($domain === '0' || empty($domain)) {
            continue;
          }
          $rds = [];
          for($i=0; $i< $step; $i++) {
            $rds[$i] = (new Random())->name(mt_rand($start, $end));
          }
          $rds = implode('.', $rds);

          $doms[$key][] = $rds . '.' . $domain;
        }
        $number--;
      }while($number > 0);

    }
    // 自定义（推荐）
    else {
      // TODO
    }
    return $doms;
  }
}
