<?php

namespace Drupal\spiders\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Spiders type entity.
 *
 * @ConfigEntityType(
 *   id = "spiders_type",
 *   label = @Translation("蜘蛛名称"),
 *   label_collection = @Translation("蜘蛛名称"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\spiders\SpidersTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\spiders\Form\SpidersTypeForm",
 *       "edit" = "Drupal\spiders\Form\SpidersTypeForm",
 *       "delete" = "Drupal\spiders\Form\SpidersTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\spiders\SpidersTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "spiders_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "spiders",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/spiders_type/{spiders_type}",
 *     "add-form" = "/admin/spiders_type/add",
 *     "edit-form" = "/admin/spiders_type/{spiders_type}/edit",
 *     "delete-form" = "/admin/spiders_type/{spiders_type}/delete",
 *     "collection" = "/admin/spiders_type"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "source",
 *     "user_agent",
 *   }
 * )
 */
class SpidersType extends ConfigEntityBundleBase implements SpidersTypeInterface {

  /**
   * The Spiders type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Spiders type label.
   *
   * @var string
   */
  protected $label;

  /**
   * @var 存爬虫来源IP，即IP白名单.
   * TODO, 考虑单独存放白名单IP。
   */
  protected $source;

  /**
   * @var 储存User-Agent的数据，使用\r\n分隔
   */
  protected $user_agent;

  public function getSource() {
    return $this->source;
  }

  public function getUserAgent() {
    return $this->user_agent;
  }

  public function getUserAgentArray() {
    return array_filter(explode("\r\n", $this->user_agent), function ($item) {
      if (!empty($item)) {
        return $item;
      }
    });
  }

  public function getSourceArray() {
    return array_filter(explode("\r\n", $this->source), function ($item) {
      if (!empty($item)) {
        return $item;
      }
    });
  }
}
