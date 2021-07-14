<?php

namespace Drupal\seo_flink\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for Flink edit forms.
 *
 * @ingroup seo_flink
 */
class FlinkForm extends ContentEntityForm {

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
    /* @var \Drupal\seo_flink\Entity\Flink $entity */
    $form = parent::buildForm($form, $form_state);

    //$form['domain']['widget'][0]['#type'] = 'text_format';
    $form['domain']['widget'][0]['#format'] = 'plain_text';
    $form['domain']['widget'][0]['#placeholder'] = 'ceshi.cm
4.zhizhuchi.cm
5.zhizhuchi.cm
6.zhizhuchi.cm
7.zhizhuchi.cm
8.zhizhuchi.cm
9.zhizhuchi.cm
10.zhizhuchi.cm';

    //$form['links']['widget'][0]['#type'] = 'text_format';
    $form['links']['widget'][0]['#format'] = 'plain_text';
    $form['links']['widget'][0]['#placeholder'] = 'url地址#描文本#过期时间
如： http://a.com/#百度#2019-10-11
http://a.com/#百度#2019-10-12
http://a.com/#百度#2019-10-13';


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
        $this->messenger()->addMessage($this->t('Created the %label Flink.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Flink.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.seo_flink.canonical', ['seo_flink' => $entity->id()]);
  }

}
