<?php

namespace Drupal\seo_station\Airui\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

class StationSlideshowBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'items' => 6,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['items'] = [
      '#type' => 'number',
      '#title' => '文章个数',
      '#default_value' => $this->configuration['items'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['items'] = $form_state->getValue('items');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties([
      'type' => 'article',
    ]);

    $pend_nodes = array_rand($nodes, $this->configuration['items']);
    foreach ($pend_nodes as $node) {
      $build['items'][] = \Drupal::service('seo_station.manager')->getNode($nodes[$node]);
    }

    return $build;
  }

}
