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
 *   id = "dynamic_navigation_block",
 *   admin_label = @Translation("动态导航菜单"),
 * )
 */
class DynamicNavigationBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    // Get the theme.

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    /** @var \Drupal\taxonomy\TermInterface [] $terms */
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties([
      'vid' => 'typename',
    ]);
    if (empty($terms)) {
      return $build;
    }
    $i = 0;
    foreach ($terms as $term) {
      if ($i > $this->configuration['items']) {
        break;
      }

//      $build['items'][$term->id()] = [
//        'id' => [
//          '#type' => 'item',
//          '#plain_text' => $term->id(),
//        ],
//        'name' => [
//          '#type' => 'markup',
//          '#markup' => $term->label(),
//        ],
//        'link' =>  [
//          '#type' => 'markup',
//          '#markup' => $term->toUrl()->toString(),
//        ],
//        'active' => [
//          '#type' => 'item',
//          '#plain_text' => FALSE,
//        ],
//      ];

      $build['items'][$term->id()] = [
        'id' => $term->id(),
        'name' => $term->label(),
        'link' => $term->toUrl(),
        'active' => FALSE,
      ];
      $parameters = \Drupal::routeMatch()->getParameters();
      if(isset($parameters->all()['taxonomy_term']) && $parameters->all()['taxonomy_term']->id() == $term->id()) {
        $build['items'][$term->id()]['active'] = TRUE;
      }
      $i++;
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
//  public function getCacheTags() {
//    return Cache::mergeTags(
//      parent::getCacheTags(),
//      \Drupal::entityTypeManager()->getDefinition('taxonomy_term')->getListCacheTags()
//    );
//  }
//  public function getCacheMaxAge(){
//    return 0;
//  }

  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['url']);
  }
}
