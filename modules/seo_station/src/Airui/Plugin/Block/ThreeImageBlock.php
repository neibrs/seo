<?php

namespace Drupal\seo_station\Airui\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ThreeImageBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Stores the configuration factory.
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManger;

  /**
   * Creates a SystemBrandingBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManger = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

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

    $nodes_ids = array_map(function ($node) {
      return $node->id();
    }, $nodes);
    $rand_ids = [];
    if (count($nodes_ids) > $this->configuration['items']) {
      $rand_ids = array_rand($nodes_ids, $this->configuration['items']);
    }
    else {
      $rand_ids = $nodes_ids;
    }
    if (empty($rand_ids)) {
      return $build;
    }
    foreach ($rand_ids as $node) {
      if (empty($nodes[$node])) {
        $x = 'a';
      }
      $build['items'][] = \Drupal::service('seo_station.manager')->getNode($nodes[$node]);
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(
      parent::getCacheTags(),
      \Drupal::entityTypeManager()->getDefinition('taxonomy_term')->getListCacheTags()
    );
  }

}
