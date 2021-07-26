<?php

namespace Drupal\seo_station\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 * @Block(
 *   id = "multi_column_title_block",
 *   admin_label = @Translation("多栏目标题区块"),
 * )
 */
class MultiColumnTitleBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
      'column' => 4,
      'items' => 6,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['column'] = [
      '#type' => 'number',
      '#title' => '栏目个数',
      '#default_value' => $this->configuration['column'],
    ];
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
    $this->configuration['column'] = $form_state->getValue('column');
    $this->configuration['items'] = $form_state->getValue('items');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    // Find column
    $taxonomy_query = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->getQuery();
    $taxonomy_query->condition('vid', 'typename');
    $taxonomy_query->range(0, $this->configuration['column']);
    $taxonomy_ids = $taxonomy_query->execute();

    $data = [];
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    if (!empty($taxonomy_ids)) {
      $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadMultiple($taxonomy_ids);
      foreach ($terms as $id => $term) {
        // Find nodes.
        $query = $node_storage->getQuery();
        $query->condition('type', 'article');
        $ids = $query->execute();

        $rand_ids = array_rand($ids, $this->configuration['items']);
        $new_ids = [];
        foreach ($rand_ids as $id) {
          if (in_array($id, array_keys($ids))) {
            $new_ids[] = $ids[$id];
          }
          continue;
        }

        $nodes = $node_storage->loadMultiple($new_ids);

        $data[$term->id()] = [
          'name' => $term->label(),
          'link' => $term->toUrl(),
        ];
        foreach ($nodes as $node) {
          $data[$term->id()]['nodes'][] = \Drupal::service('seo_station.manager')->getNode($node);
        }
      }
    }
    $build['data'] = $data;

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
