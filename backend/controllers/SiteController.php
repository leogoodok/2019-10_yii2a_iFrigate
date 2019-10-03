<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\MessageAction;
use backend\models\MessageForm;

/**
 * Site контроллер
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
        'rules' => [
          [
            'actions' => ['login', 'error'],
            'allow' => true,
          ],
          [
            'actions' => ['logout', 'index', 'message'],
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
    ];
  }

  /**
   * Displays homepage.
   *
   * @return string
   */
  // public function actionIndex()
  // {
  //     return $this->render('index');
  // }



  /**
   * Displays homepage.
   *
   * @return string
   */
  public function actionMessage()
  {
    //Создание экземпляра класса модели
    $modelAction = new MessageAction();
    //Присвоение атрибутам модели значений POST-параметров и выполнение валидации
    if ($modelAction->load(Yii::$app->request->post()) && $modelAction->validate()) {
      if (!empty($modelAction->status)) {
        if ($modelAction->updateStatusMSG()) {
          Yii::$app->session->setFlash('success', 'Изменение статусов выбранных сообщений выполнено.');
        } else {
          Yii::$app->session->setFlash('error', 'Произошла ошибка в процессе изменения статусов сообщений.');
        }
      }
      if (!empty($modelAction->delete)) {
        if ($modelAction->deleteMSG()) {
          Yii::$app->session->addFlash('success', 'Удаление выбранных сообщений выполнено.');
        } else {
          Yii::$app->session->addFlash('error', 'Произошла ошибка в процессе удаления сообщений.');
        }
      }
    }

    //Создание экземпляра класса модели
    $model = new MessageForm();
    //Присвоение атрибутам модели значений GET-параметров и выполнение валидации
    $model->setAttributes(Yii::$app->request->get());
    if (!$model->validate()) {
      Yii::$app->session->addFlash('error', 'Ошибка получения параметров фильтрации сообщений.');
    }
    $dataProvider = $model->getDataProvider();

    //! Временно, пока нет назначения "ролей" и "разрешений" пользователей
    $permissions = [
      'update_status' => false,
      'delete_msg' => false
    ];
    $authUser = Yii::$app->user->getIdentity()->getAttributes();
    if (isset($authUser['id'])) {
      if ($authUser['id'] == 1 || $authUser['id'] == 2) {
        $permissions['update_status'] = true;
      }
      if ($authUser['id'] == 1) {
        $permissions['delete_msg'] = true;
      }
    }

    return $this->render('message', [
      'model' => $model,
      'dataProvider' => $dataProvider,
      'modelAction' => $modelAction,
      'columnVisible' => [
        'delete_at' => (isset($model->filter_msg) && ($model->filter_msg == 0)),
        'delete_msg' => $permissions['delete_msg'],
        'status' => ((isset($model->filter_msg) && ($model->filter_msg == 0)) || !$permissions['update_status']),
      ],
    ]);
  }


  /**
   * Login action.
   *
   * @return string
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
   * Logout action.
   *
   * @return string
   */
  public function actionLogout()
  {
    Yii::$app->user->logout();

    return $this->goHome();
  }
}
