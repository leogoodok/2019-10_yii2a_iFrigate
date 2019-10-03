<?php
/**
 * Представление "Отправки сообщения администратор"
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap4\ActiveForm
 * @var $model \frontend\models\ContactForm
 */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Отправить сообщение администратору';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
  <h1><?= Html::encode($this->title) ?></h1>
  <p>Если у вас есть вопросы пожалуйста заполните следующую форму чтобы связаться с нами.  Спасибо.</p>
  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

        <?= $form->field($model, 'name', [
            'inputOptions' => [
              'placeholder' => 'Иван Иванов'
            ]
          ])->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'email', [
            'inputOptions' => [
              'placeholder' => 'example@example.com'
            ]
          ]) ?>

        <?= $form->field($model, 'subject', [
            'inputOptions' => [
              'placeholder' => 'Тема сообщения'
            ]
          ]) ?>

        <?= $form->field($model, 'body', [
            'inputOptions' => [
              'placeholder' => 'Содержание сообщения'
            ]
          ])->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'files[]')->fileInput(['multiple' => true, 'accept' => 'image/*,application/zip,application/gzip,text/plain,application/pdf,application/msword'])->label('Прикрепить файл') ?>

        <?= $form->field($model, 'verifyCode', [
            'inputOptions' => [
              'autocomplete' => 'off'
            ]
          ])->widget(Captcha::className(), [
          'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
        ]) ?>

        <div class="form-group">
          <?= Html::submitButton('Отправить форму', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>
      <?php ActiveForm::end(); ?>

    </div>
  </div>
</div>
