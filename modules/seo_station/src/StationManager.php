<?php

namespace Drupal\seo_station;

use Drupal\Component\Utility\Random;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeInterface;

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

  // ??????Station???????????????????????????????????????
  public function getMultiDomainByStation($domains = [], $station = NULL, $number = 1) {
    $doms = [];
    $domains = reset($domains);
    if (empty($station)) {
      return [];
    }

    // ????????????
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
          for($i=0; $i< $step; $i++) { // TODO, ??????????????????????????????????????????
            $rds[$i] = (new Random())->word(mt_rand($start, $end));
          }
          $rds = implode('.', $rds);

          $doms[$key][] = $rds . '.' . $domain;
        }
        $number--;
      }while($number > 0);

    }
    // ?????????????????????
    else {
      // TODO
    }
    return $doms;
  }

  public function getNode(NodeInterface $node) {
    $field_tag = $this->getNodeOneTag($node);
    $file_uri = empty($node->field_image->referencedEntities()) ? file_create_url('public://node-default.jpg') : $node->field_image->entity->createFileUrl();

    $site_name = \Drupal::config('system.site')->get('name');
    return [
      'id' => $node->id(),
      'name' => $node->label(),
      'link' => $node->toUrl(),
      'image' => $file_uri,
      'description' => $node->get('body')->summary,
      'created' => $node->created->value,
      'field_tag' => empty($field_tags) ? '' : $field_tag->label(),
      'field_tag_link' => empty($field_tags) ? '' :  $field_tag->toUrl(),
      'wild_site_name' => $site_name,
    ];
  }

  protected function getNodeOneTag(NodeInterface $node) {
    $field_tags = $node->field_tags->referencedEntities();
    $field_tag = '';
    if (!empty($field_tags)) {
      $field_tag = reset($field_tags);
    }
    return $field_tag;
  }
}
