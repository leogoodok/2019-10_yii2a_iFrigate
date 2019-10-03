<?php
/**
 * Представление "Войти" авторизации пользователей
 *
 * @var $this yii\web\View
 * @var $form yii\bootstrap4\ActiveForm
 * @var $model \common\models\LoginForm
 */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Войти';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Пожалуйста заполните следующие поля для входа:</p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    Если забыли свой пароль вы можете <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                    <br>
                    Нужно новое письмо с подтверждением? <?= Html::a('Resend', ['site/resend-verification-email']) ?>

                </div>
                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

                </div>
            <?php ActiveForm::end(); ?>
            
        </div>
    </div>
</div>
