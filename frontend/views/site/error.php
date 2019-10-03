<?php
/**
 * Представление "Вывода ошибок"
 *
 * @var $this yii\web\View
 * @var $name string
 * @var $message string
 * @var $exception Exception
 */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>

    </div>
    <p>
        Вышеуказанная ошибка произошла когда веб-сервер обрабатывал ваш запрос.
    </p>
    <p>
        Please contact us if you think this is a server error. Спасибо.
    </p>
</div>
