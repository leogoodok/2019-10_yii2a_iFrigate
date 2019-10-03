<?php
namespace frontend\controllers;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;

/**
 * Контроллер 'Site'
 */
class SiteController extends Controller
{
  /**
   * {@inheritdoc}
   */
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'only' => ['logout', 'signup'],
        'rules' => [
          [
            'actions' => ['signup'],
            'allow' => true,
            'roles' => ['?'],
          ],
          [
            'actions' => ['logout'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
          'class' => VerbFilter::className(),
          'actions' => [
          'logout' => ['post'],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function actions()
  {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ],
      'captcha' => [
        'class' => 'yii\captcha\CaptchaAction',
        'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
      ],
    ];
  }

  /**
   * Displays домашняя страница.
   *
   * @return mixed
   */
  // public function actionIndex()
  // {
  //   return $this->render('index');
  // }


  /**
   * Авторизация пользователей
   *
   * @return mixed
   */
  public function actionLogin()
  {
    if (!Yii::$app->user->isGuest) {
      return $this->goHome();
    }
    $model = new LoginForm();
    if ($model->load(Yii::$app->request->post()) && $model->login()) {
      return $this->goBack();
    } else {
      $model->password = '';
      return $this->render('login', [
        'model' => $model,
      ]);
    }
  }


  /**
   * Выход из текущей авторизации пользователя.
   *
   * @return mixed
   */
  public function actionLogout()
  {
    Yii::$app->user->logout();
    return $this->goHome();
  }


  /**
   * Отображает страницу "Отправки сообщения администратор"у.
   * @return mixed
   */
  public function actionContact()
  {
    //Создание экземпляра класса модели
    $model = new ContactForm();
    //Присвоение атрибутам модели значений POST-параметров и выполнение валидации
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      $model->files = UploadedFile::getInstances($model, 'files');
      if ($model->sendEmail(Yii::$app->params['adminEmail']) && $model->saveMSG()) {
        Yii::$app->session->setFlash('success', 'Благодарим Вас за обращение к нам. Мы ответим вам как можно скорее.');
      } else {
        Yii::$app->session->setFlash('error', 'Произошла ошибка в процессе отправки сообщения.');
      }
      return $this->refresh();
    } else {
      return $this->render('contact', [
        'model' => $model,
      ]);
    }
  }


  /**
   * Displays about page.
   *
   * @return mixed
   */
  // public function actionAbout()
  // {
  //   return $this->render('about');
  // }

  /**
   * Регистрации пользователя.
   * @return mixed
   */
  public function actionSignup()
  {
    $model = new SignupForm();
    if ($model->load(Yii::$app->request->post()) && $model->signup()) {
      Yii::$app->session->setFlash('success', 'Спасибо за регистрацию.  Пожалуйста проверьте ваш почтовый ящик для подтверждения электронной почты.');
      return $this->goHome();
    }
    return $this->render('signup', [
      'model' => $model,
    ]);
  }


  /**
   * Requests password reset.
   * @return mixed
   */
  public function actionRequestPasswordReset()
  {
    $model = new PasswordResetRequestForm();
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if ($model->sendEmail()) {
        Yii::$app->session->setFlash('success', 'Проверьте свою электронную почту для дальнейших инструкций.');
        return $this->goHome();
      } else {
        Yii::$app->session->setFlash('error', 'Извините мы не можем сбросить пароль для указанного адреса электронной почты.');
      }
    }
    return $this->render('requestPasswordResetToken', [
      'model' => $model,
    ]);
  }


  /**
   * Resets password.
   * @param string $token
   * @return mixed
   * @throws BadRequestHttpException
   */
  public function actionResetPassword($token)
  {
    try {
      $model = new ResetPasswordForm($token);
    } catch (InvalidArgumentException $e) {
      throw new BadRequestHttpException($e->getMessage());
    }
    if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
      Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');
      return $this->goHome();
    }
    return $this->render('resetPassword', [
      'model' => $model,
    ]);
  }


  /**
   * Verify email address
   * @param string $token
   * @throws BadRequestHttpException
   * @return yii\web\Response
   */
  public function actionVerifyEmail($token)
  {
    try {
      $model = new VerifyEmailForm($token);
    } catch (InvalidArgumentException $e) {
        throw new BadRequestHttpException($e->getMessage());
    }
    if ($user = $model->verifyEmail()) {
      if (Yii::$app->user->login($user)) {
        Yii::$app->session->setFlash('success', 'Ваше сообщение было подтверждено!');
        return $this->goHome();
      }
    }
    Yii::$app->session->setFlash('error', 'К сожалению мы не можем подтвердить ваш аккаунт с помощью предоставленного токена.');
    return $this->goHome();
  }


  /**
   * Resend verification email
   *
   * @return mixed
   */
  public function actionResendVerificationEmail()
  {
    $model = new ResendVerificationEmailForm();
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      if ($model->sendEmail()) {
        Yii::$app->session->setFlash('success', 'Проверьте свою электронную почту для дальнейших инструкций.');
        return $this->goHome();
      }
      Yii::$app->session->setFlash('error', 'Извините мы не можем отправить письмо с подтверждением на указанный адрес электронной почты..');
    }
    return $this->render('resendVerificationEmail', [
      'model' => $model
    ]);
  }
}
