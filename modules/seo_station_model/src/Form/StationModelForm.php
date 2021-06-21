<?php

namespace Drupal\seo_station_model\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for Station model edit forms.
 *
 * @ingroup seo_station_model
 */
class StationModelForm extends ContentEntityForm {

  /**
   * The current user account.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    $instance = parent::create($container);
    $instance->account = $container->get('current_user');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var \Drupal\seo_station_model\Entity\StationModel $entity */
    $form = parent::buildForm($form, $form_state);

    // placeholder

    $form['main']['widget'][0]['value']['#default_value'] = 'index,list,show';
    $form['main']['widget'][0]['value']['#disabled'] = TRUE;

    $form['base'] = [
      '#type' => 'details',
      '#title' => '基本设置',
      '#open' => TRUE,
    ];
    $base_fields = [
      'name',
      'config_dir',
      'main',
      'secondary',
    ];
    foreach (Element::children($form) as $child) {
      if (in_array($child, $base_fields)) {
        $form['base'][$child] = $form[$child];
        unset($form[$child]);
      }
    }
    $form['advanced'] = [
      '#type' => 'details',
      '#title' => '模板URL规则列表 (<span class="description-red">先保存，再设置url规则</span> )',
      '#open' => TRUE,
    ];
    $form['advanced']['rule_url_help'] = [
      '#type' => 'item',
      '#markup' => '<span class="description-red">随机模式：即生成的url地址里包含有随机参数，生成的url就是无限模式</span><br/>
                    <span class="description-red">固定模式：生成的url地址随内容库的条数所定，例如：文章库的txt有999行，则内容页url最多为999条。</span><br/>
                    <span class="description-red">注：修改url规则后，需清除url规则缓存才生效</span>',
      '#weight' => -10,
      '#attributes' => [
        'class' => [
          'xxx--yy',
        ],
      ],
    ];
    $form['rule_url']['#content']['seo_model'] = $form_state->getFormObject()->getEntity();
    $form['rule_url']['#theme'] = 'seo_station_model_url_item_list';
    $form['advanced']['rule_url'] = $form['rule_url'];
    unset($form['rule_url']);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Station model.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Station model.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.seo_station_model.collection');
  }

}
