<?php
/**
 * Основной шаблон страницы
 *
 * @var $this yii\web\View
 * @var $content string контент страниц
 */

use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php $this->registerCsrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
  <?php
  NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
      'class' => ['widget' => 'navbar','navbar-expand-lg','fixed-top','navbar-dark','bg-dark'],//'sticky-top'
    ],
  ]);
  $menuItems = [
    // ['label' => 'Home', 'url' => ['/site/index']],
    // ['label' => 'About', 'url' => ['/site/about']],
    ['label' => 'Отправить сообщение', 'url' => ['/site/contact']],
  ];
  if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Зарегистрироваться', 'url' => ['/site/signup']];
    $menuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
  } else {
    $menuItems[] = '<li>'
      . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline h-100'])
      . Html::submitButton(
        'Выйти (' . Yii::$app->user->identity->username . ')',
        ['class' => 'btn btn-dark']
      )
      . Html::endForm()
      . '</li>';
  }
  echo Nav::widget([
    'options' => ['class' => 'navbar-nav ml-auto'],
    'items' => $menuItems,
  ]);
  NavBar::end();
  ?>

  <div class="container">
    <?= Breadcrumbs::widget([
      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
      'options' => ['class' => 'breadcrumb mb-2'],
      'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
      'activeItemTemplate' => "<li class=\"breadcrumb-item active\">{link}</li>\n",
    ]) ?>
    <?= Alert::widget() ?>
    <?= $content ?>

  </div>
</div>
<footer class="footer">
  <div class="container">
    <div class="row">
      <p class="col-auto">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
      <p class="col-auto ml-auto"><?= Yii::powered() ?></p>
    <div>
  </div>
</footer>
<div id="butToTop">&#xE133;</div><?php
$this->registerJs('jQuery(function($){$(window).scroll(function(){if($(this).scrollTop()!=0){$("#butToTop").fadeIn();}else{$("#butToTop").fadeOut();}});$("#butToTop").click(function(){$("body,html").animate({scrollTop:0},800);});});'); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
