<?php
/**
 * Представление "Отображения сообщений администратору"
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap4\ActiveForm
 * @var $model \backend\models\MessageForm
 * @var $modelAction \backend\models\MessageAction
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\bootstrap4\ButtonGroup;
use yii\bootstrap4\LinkPager;
use yii\bootstrap4\ActiveForm;
use common\models\db\TabMessage;

$this->title = 'Сообщения администратору';
$this->params['breadcrumbs'][] = $this->title;
$this->params['form'] = '';
$this->params['modelAction'] = $modelAction;

//Подключение файла JS скриптов страницы
$this->registerJsFile(
    '@web/js/message.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
?>
<div class="site-message">
  <div class="row mb-2">
    <div class="col">
      <h1><?= Html::encode($this->title) ?></h1>
    </div>
  </div>
  <div class="row mb-2">
    <div class="col-auto">
      <div class="d-inline-block">
        Фильтр по статусу:
      </div>
      <div class="d-inline-block">
        <div class="btn-toolbar justify-content-end" role="toolbar" aria-label="Toolbar with button groups">
          <?php
          $buttons = [[
            'tagName' => 'a',
            'label' => 'Активное',
            'options' => [
              'href' => Url::current(['filter_msg' => null]),
              'class' => 'btn btn-outline-primary',
              'role' => 'button',
            ],
          ]];
          if(!isset($model->filter_msg)) {
            $buttons[0]['options']['class'] .= ' active';
            $buttons[0]['options']['aria-pressed'] = 'true';
          }
          $nameStatus = TabMessage::nameStatus();
          for ($i = 0; $i < count($nameStatus); $i++) {
            $options = [];
            $options['tagName'] = 'a';
            $options['label'] = $nameStatus[$i];
            $options['options'] = ['href' => Url::current(['filter_msg' => $i])];
            $options['options']['class'] = 'btn btn-outline-primary';
            if(isset($model->filter_msg) && $model->filter_msg == $i) {
              $options['options']['class'] .= ' active';
              $options['options']['aria-pressed'] = 'true';
            }
            $options['options']['role'] = 'button';
            $buttons[] = $options;
          }
          $arr = [ArrayHelper::remove($buttons, 1)];
          $buttons = array_merge($buttons, $arr);
          echo ButtonGroup::widget([
            'buttons' => $buttons,
            'options' => [
              'class' => 'btn-group',
              'role' => 'group',
              'aria-label' => 'Фильтр сообщений по их статусу',
            ],
          ]);
          unset($buttons,$options,$nameStatus,$arr);
          ?>

        </div>
      </div>
    </div>
    <div class="col-auto ml-auto">
      <div class="d-inline-block">
        Показывать по:
      </div>
      <div class="d-inline-block">
        <div class="btn-toolbar justify-content-end" role="toolbar" aria-label="Toolbar with button groups">
          <?php
          //Создание и заполнение массива настроек виджета "ButtonGroup"
          $buttons = [];
          for ($i = 1; $i < 6; $i++) {
            $limit = $i * 10;
            $options = [];
            $options['tagName'] = 'a';
            $options['label'] = $limit;
            //$options['options'] = ['href' => Url::current(['per-page' => $limit])];
            $options['options'] = ['href' => Url::current([$dataProvider->getPagination()->pageSizeParam => $limit])];
            $options['options']['class'] = 'btn btn-outline-primary';
            if($dataProvider->getPagination()->getPageSize() == $limit) {
              $options['options']['class'] .= ' active';
          //    $options['visible'] = false;
              $options['options']['aria-pressed'] = 'true';
            }
            $options['options']['role'] = 'button';
            $buttons[] = $options;
          }
          echo ButtonGroup::widget([
            'buttons' => $buttons,
            'options' => [
              'class' => 'btn-group',
              'role' => 'group',
              'aria-label' => 'Задание количества строк в таблице',
            ],
          ]);
          unset($buttons, $limit, $options);
          ?>

        </div>
      </div>
    </div>
  </div>
  <?php $this->params['form'] = ActiveForm::begin(['id' => 'message-form']); ?>

<?php
echo GridView::widget([
  'dataProvider' => $dataProvider,
  'tableOptions' => ['id' => 'table1', 'class' => 'table table-striped text-center'],
  'columns' => [
    [
      'headerOptions' => ['scope' => 'col', 'class' => 'sticky-top'],
      'contentOptions' => ['scope' => 'row'],
      'class' => \yii\grid\SerialColumn::className(),
      'header' => '#',
    ],
    [
      'attribute' => 'name',
      'label' => "Имя",
      'headerOptions' => ['scope' => 'col', 'class' => 'sticky-top'],
      'contentOptions' => ['class' => 'sticky-left'],
      'format' => ['text'],
    ],
    [
      'attribute' => 'email',
      'label' => "Email",
      'headerOptions' => ['scope' => 'col', 'class' => 'sticky-top'],
      'format' => ['text'],
    ],
    [
      'attribute' => 'number_attachment',
      'label' => "Вложения",
      'headerOptions' => ['scope' => 'col', 'class' => 'sticky-top'],
      'contentOptions' => ['class' => 'text-center p-1'],
      'format' => ['raw'],
      'value' => function ($model, $key, $index, $column) {
        return isset($model->number_attachment) ? $model->number_attachment : 'нет';
      },
    ],
    [
      'attribute' => 'created_at',
      'label' => "Создано",
      'headerOptions' => ['scope' => 'col', 'class' => 'sticky-top'],
      'format' => ['date', 'php:H:i d.m.Y'],
    ],
    [
      'attribute' => 'updated_at',
      'label' => "Изменено",
      'headerOptions' => ['scope' => 'col', 'class' => 'sticky-top'],
      'format' => ['raw'],
      'value' => function ($model, $key, $index, $column) {
        return !empty($model->updated_at) ? $model->getUpdatedDate() : '&nbsp;';
      },
    ],
    [
      'attribute' => 'delete_at',
      'label' => "Удалено",
      'headerOptions' => ['scope' => 'col', 'class' => 'sticky-top'],
      'format' => ['raw'],
      'value' => function ($model, $key, $index, $column) {
        return !empty($model->delete_at) ? $model->getDeleteDate() : '&nbsp;';
      },
      'visible' => $columnVisible['delete_at'],
    ],
    [
      'attribute' => 'status',
      'label' => "Статус",
      'headerOptions' => ['scope' => 'col', 'class' => 'sticky-top'],
      'format' => ['text'],
      'value' => function ($model, $key, $index, $column) {
        return isset($model->status) ? $model->getStatusText() : '&nbsp;';
      },
      'visible' => $columnVisible['status'],
    ],
    [
      'label' => "Статус",
      'headerOptions' => ['scope' => 'col', 'class' => 'sticky-top'],
      'contentOptions' => ['class' => 'p-1'],
      'visible' => !$columnVisible['status'],
      'format' => ['raw'],
      // 'value' => function ($model, $key, $index, $column) {
      //   return Html::dropDownList(
      //     "MessageAction[status][{$model->id}]",
      //     // null,
      //     $model->status,
      //     TabMessage::nameStatus(),
      //     [
      //       'class' => 'custom-select',
      //       'data-id' => $model->id,
      //       'data-name' => "MessageAction[status][{$model->id}]",
      //       'data-value' => $model->status,
      //     ]
      //   );
      // },
      'value' => function ($model, $key, $index, $column) {
        return $this->params['form']->
                field($this->params['modelAction'], "status[{$model->id}]", [
                  'enableLabel' => false,
                  'options' => ['tag' => false],
                  'inputOptions' => ['class' => 'custom-select'],
                ])
                ->dropDownList(
                  TabMessage::nameStatus(),[
                    'options' => [$model->status => ['Selected' => true]],
                    'data-msg-id' => $model->id,
                    'data-value' => $model->status
                  ]);
      },
    ],
    [
      'label' => "Удалить",
      'headerOptions' => ['scope' => 'col', 'class' => 'sticky-top'],
      'contentOptions' => ['class' => 'text-center p-1'],
      'visible' => $columnVisible['delete_msg'],
      'format' => ['raw'],
      // 'value' => function ($model, $key, $index, $column) {
      //   return implode("\n", [
      //     Html::beginTag('div', [
      //       'class' => 'btn-group-toggle',
      //       'data-toggle' => 'buttons'
      //     ]),
      //     Html::beginTag('label', [
      //       'class' => 'btn btn-outline-danger text-font-glyphicons-halflings'
      //     ]),
      //     Html::checkbox(
      //       "MessageAction[delete][{$model->id}]",
      //       false,
      //       [
      //         'data-msg-id' => $model->id,
      //       ]
      //     ),
      //     '&#xE014;',
      //     Html::endTag('label'),
      //     Html::endTag('div')
      //   ]);
      // },
      'value' => function ($model, $key, $index, $column) {
        return $this->params['form']->field($this->params['modelAction'], 'delete['.$model->id.']', [
            'checkTemplate' => "{beginLabel}\n{input}\n{labelTitle}\n{endLabel}",
            'enableError' => false,
            'options' => [
              'class' => ['btn-group-toggle'],
              'data-toggle' => 'buttons'
            ],
            'inputOptions' => ['class' => 'custom-select'],
          ])->checkbox(['class' => '', 'autocomplete' => 'off', 'data-msg-id' => $model->id])->label('&#xE014;', ['class' => 'btn btn-outline-danger text-font-glyphicons-halflings']);
      },
    ],
  ],
  'pager' => [
    'class' => LinkPager::className(),
    'firstPageLabel' => true,
    'lastPageLabel' => true,
    'options' => ['class' => 'container d-flex justify-content-center'],
    'listOptions' => ['class' => ['pagination mb-1']],
  ],
]);
?>

    <div class="row justify-content-center mt-1">
      <div class="col-10">
        <?= Html::submitButton('Сохранить изменения статусов сообщений и удалить выбранные сообщения', ['class' => 'btn btn-info btn-block']) ?>
      </div>
    </div>
  <?php ActiveForm::end(); ?>

</div>
