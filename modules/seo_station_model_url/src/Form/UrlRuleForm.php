<?php

namespace Drupal\seo_station_model_url\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class UrlRuleForm extends FormBase {

  protected $seo_station_model;
  protected $seo_station_model_url_type;

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'seo_station_model_url_rule_edit_form';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $seo_station_model = NULL, $seo_station_model_url_type = NULL) {
    $form['seo_station_model'] = [
      '#type' => 'hidden',
      '#value' => $seo_station_model,
    ];
    $form['seo_station_model_url_type'] = [
      '#type' => 'hidden',
      '#value' => $seo_station_model_url_type,
    ];
   $form['rule_title'] = [
      '#type' => 'inline_template',
      '#template' => '<p><svg t="1621308611688" class="icon" style="width: 1em;height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="993" data-spm-anchor-id="a313x.7781069.0.i1"><path d="M990.12564 391.307264l-79.271936 46.15168c4.446208 24.23808 7.36256 48.9984 7.36256 74.5472 0 25.544704-2.916352 50.305024-7.360512 74.539008l79.271936 46.147584c32.380928 18.849792 43.476992 60.604416 24.7808 93.253632l-67.700736 118.243328c-18.694144 32.647168-60.102656 43.833344-92.485632 24.987648l-80.084992-46.626816c-37.419008 32.118784-79.95392 58.368-127.229952 75.24352l0 57.93792c0 37.70368-30.312448 68.268032-67.702784 68.268032l-135.405568 0c-37.390336 0-67.702784-30.5664-67.702784-68.268032l0-57.93792c-47.276032-16.877568-89.810944-43.126784-127.229952-75.24352l-80.084992 46.626816c-32.382976 18.847744-73.78944 7.661568-92.481536-24.987648L9.090632 725.94432c-18.694144-32.647168-7.600128-74.401792 24.7808-93.253632l79.271936-46.147584c-4.44416-24.231936-7.360512-48.994304-7.360512-74.539008 0-25.5488 2.916352-50.30912 7.360512-74.549248l-79.271936-46.147584c-32.380928-18.849792-43.474944-60.604416-24.7808-93.253632L76.797512 179.8144c18.692096-32.653312 60.09856-43.843584 92.481536-24.989696l80.08704 46.62272c37.419008-32.120832 79.95392-58.368 127.227904-75.24352L376.593992 68.27008C376.593992 30.5664 406.90644 0 444.296776 0l135.405568 0c37.390336 0 67.702784 30.5664 67.702784 68.27008l0 57.933824c47.273984 16.877568 89.808896 43.122688 127.227904 75.24352l80.08704-46.62272c32.382976-18.853888 73.791488-7.663616 92.485632 24.989696l67.700736 118.239232C1033.602632 330.7008 1022.506568 372.457472 990.12564 391.307264zM939.351624 302.628864l-33.851392-59.123712c-9.347072-16.32256-30.052352-21.919744-46.241792-12.496896l-94.0544 54.759424c-47.740928-54.300672-112.257024-93.253632-185.499648-108.249088L579.704392 102.395904c0-18.849792-15.1552-34.125824-33.851392-34.125824l-67.704832 0c-18.696192 0-33.851392 15.276032-33.851392 34.125824l0 75.122688c-73.242624 14.995456-137.75872 53.948416-185.499648 108.249088l-94.0544-54.759424c-16.18944-9.422848-36.892672-3.825664-46.241792 12.496896l-33.851392 59.123712c-9.347072 16.32256-3.79904 37.20192 12.3904 46.626816l94.431232 54.972416c-11.249664 33.957888-17.985536 69.996544-17.985536 107.778048 0 37.773312 6.733824 73.811968 17.983488 107.767808l-94.431232 54.976512c-16.18944 9.426944-21.737472 30.302208-12.3904 46.626816l33.851392 59.117568c9.34912 16.330752 30.052352 21.92384 46.241792 12.4928l94.050304-54.755328c47.742976 54.300672 112.259072 93.253632 185.503744 108.244992l0 75.128832c0 18.847744 15.1552 34.125824 33.851392 34.125824l67.704832 0c18.696192 0 33.851392-15.280128 33.851392-34.125824l0-75.128832c73.244672-14.993408 137.760768-53.946368 185.501696-108.244992l94.052352 54.755328c16.18944 9.428992 36.89472 3.837952 46.241792-12.4928l33.851392-59.117568c9.347072-16.324608 3.79904-37.199872-12.3904-46.626816l-94.431232-54.976512c11.249664-33.953792 17.983488-69.994496 17.983488-107.767808 0-37.779456-6.733824-73.816064-17.983488-107.773952l94.431232-54.974464C943.150664 339.830784 948.696648 318.951424 939.351624 302.628864zM512.001608 682.661888c-93.478912 0-169.25696-76.408832-169.25696-170.657792 0-94.2592 75.778048-170.674176 169.25696-170.674176 93.476864 0 169.25696 76.414976 169.25696 170.674176C681.25652 606.255104 605.478472 682.661888 512.001608 682.661888zM512.001608 409.602048c-56.088576 0-101.554176 45.84448-101.554176 102.402048 0 56.549376 45.467648 102.38976 101.554176 102.38976 56.08448 0 101.552128-45.838336 101.552128-102.38976C613.553736 455.446528 568.086088 409.602048 512.001608 409.602048z" p-id="994" fill="#515151" data-spm-anchor-id="a313x.7781069.0.i0"></path></svg>&nbsp;&nbsp;<strong>模板URL规则列表</strong> ( <span class="description-red">注：系统默认的模板为 首页：index、列表：list、内容：show，多条url规则时系统会随机取其中一条 )</span></p>',
      '#context' => [],
    ];
    $form['rule_mode'] = [
      '#type' => 'inline_template',
      '#template' => '<p><strong>当前URL模式：</strong>	<span class="description-blue">{{ type }}</span></p>',
    ];
    $form['rule_description'] = [
      '#type' => 'item',
      '#markup' => '
<div class="bg-xgin-yellow-light block-help">
<p><span class="description-red">URL规则设置说明：</span></p>
<p><span class="description-red">- url规则标签： {数字}、{字母}、{大写字母}、{大小写字母}、{大写字母数字}、{大小写字母数字}、{数字字母}、</span></p>
<p><span class="description-red">{日期}、{年}、{月}、{日}、{时}、{分}、{秒}、{随机字符}</span></p>
<p><span class="description-red">- 非时间的标签后面加数字是位数，如： {数字8}表示8个随机数字、{数字1-8}表示随机1-8个数字</span></p>
<p><span class="description-red">- 随机模式时，规则里必须包含标签：{id}</span></p>
<p><span class="description-red">- 固定模式时，规则里必须包含标签：{aid}、{id}</span></p>
<p><span class="description-blue">注：2个标签之间最好使用一个分隔符，如：{id}-{数字8}，避免各规则冲突</span></p>
</div>
      ',
    ];

    $form['rules'] = [
      '#type' => 'table',
      '#header' => ['ID', '模板文件', 'url规则(每行一条)', '操作'],
    ];
    if (empty($seo_station_model)) {
      $form['rules']['#empty'] = '没有选择模型，请重新选择';
      return $form;
    }
    $this->seo_station_model = $seo_station_model;
    $this->seo_station_model_url_type = $seo_station_model_url_type;
    $this->entity = \Drupal::entityTypeManager()->getStorage('seo_station_model')->load($this->seo_station_model);

    $form['rule_mode']['#context'] = [
      'type' => \Drupal::entityTypeManager()->getStorage('seo_station_model_url_type')->load($seo_station_model_url_type)->label(),
    ];



    $main = $this->entity->main->value;
    $secondary = $this->entity->secondary->value;
    $data = implode(',', [$main, $secondary]);
    $data = explode(',', $data);
    $i = 1;
    for ($j=0; $j<= count($data); $j++) {
      if (empty($data[$j])) {
        break;
      }
      $form['rules'][$j]['#attributes'] = ['class' => ['xxx']];
      $form['rules'][$j]['id'] = ['#markup' => $i];
      $form['rules'][$j]['template'] = [
        '#type' => 'textfield',
        '#default_value' => $data[$j],
        '#size' => 10,
        '#maxlength' => 128,
      ];
      $form['rules'][$j]['template']['#disabled'] = TRUE;
      if ($j == 0) {
        $form['rules'][$j]['url'] = [
          '#type' => 'textfield',
          '#default_value' => '/',
          '#disabled' => TRUE,
        ];
      }
      else {
        $form['rules'][$j]['url'] = [
          '#type' => 'text_format',
          '#format' => 'plain_text',
        ];
      }
      // 随机模式
      switch ($j) {
        case 1:
          if ($seo_station_model_url_type == 'dynamic') {
            $form['rules'][$j]['url']['#default_value'] = 'list/{数字1}{id}{数字2}/
newslist/{数字1}{id}{数字2}/';
          }
          else {
            $form['rules'][$j]['url']['#default_value'] = 'list/{id}-{aid}/
cate/{id}_{aid}.html';
          }
          break;
        case 2:
          if ($seo_station_model_url_type == 'static') {
            $form['rules'][$j]['url']['#default_value'] = 'html/{日期}/{数字1}{id}{数字3}.html
news/{数字3}{id}{数字2}.html
show/{id}{数字5}.html';
          }
          else {
            $form['rules'][$j]['url']['#default_value'] = 'html/{id}_{aid}.html
show/{id}_{aid}.html';
          }
          break;
      }
      // 首先查找url里面是否有相应template的规则
      $storage = \Drupal::entityTypeManager()->getStorage('seo_station_model_url');
      $query = $storage->getQuery();
      $query->condition('model', $seo_station_model);
      $query->condition('type', $seo_station_model_url_type);
      $query->condition('template', $data[$j]);
      $ids = $query->execute();
      if (!empty($ids)) {
        $url_default_value = $storage->load(reset($ids));
        $form['rules'][$j]['url']['#default_value'] = $url_default_value->get('rule_url_content')->value;
      }

      if ($j < 3) {
        $form['rules'][$j]['operation'] = ['#markup' => '<span class="description-red">系统</span>'];
      }
      else {
        $form['rules'][$j]['operation'] = ['#markup' => '<span class="description-blue">自定义</span>'];
      }
      $i++;
    }

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => 'Save',
    ];
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();

    // 检查是创建还是更新seo_station_model_url
    // 检查seo_station_model_url
    foreach ($form_values['rules'] as $rule) {
      $storage = \Drupal::entityTypeManager()->getStorage('seo_station_model_url');
      $query = $storage->getQuery();

      $query->condition('model', $form_values['seo_station_model']);
      $query->condition('type', $form_values['seo_station_model_url_type']);
      $query->condition('template', $rule['template']);

      $ids = $query->execute();
      if ($ids) {
        // 更新
        $module_url = $storage->load(reset($ids));
        $module_url->set('rule_url_content', $rule['url'])
          ->save();
      }
      else {
        // 创建
        $module_url = $storage->create([
          'name' => \Drupal::entityTypeManager()->getStorage('seo_station_model')->load($form_values['seo_station_model'])->label(),
          'model' => $form_values['seo_station_model'],
          'type' => $form_values['seo_station_model_url_type'],
          'template' => $rule['template'],
          'rule_url_content' => $rule['url'],
        ]);
        $module_url->save();
      }
    }


    // TODO, 切换到entity.seo_station_model.edit_form表单.
    $form_state->setRedirect('entity.seo_station_model.collection');
  }

}
