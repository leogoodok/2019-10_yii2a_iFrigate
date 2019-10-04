<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => 'Пожалуйста, заполните поле'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Это имя пользователя уже занято.'],
            ['username', 'string', 'min' => 2, 'max' => 100],

            ['email', 'trim'],
            ['email', 'required', 'message' => 'Пожалуйста, заполните поле'],
            ['email', 'email'],
            ['email', 'string', 'max' => 100],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот адрес электронной почты уже занят.'],

            ['password', 'required', 'message' => 'Пожалуйста, заполните поле'],
            ['password', 'string', 'min' => 6],
        ];
    }



    /**
     * Названия полей формы (label)
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
      return [
        'username' => 'Логин*',
        'email' => 'Email*',
        'password' => 'Пароль*',
      ];
    }


    /**
     * Signs user up.
     *
     * @return bool было ли создание нового аккаунта успешным и было ли отправлено электронное письмо
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        return $user->save() && $this->sendEmail($user);
    }


    /**
     * Отправляет подтверждение по электронной почте пользователю
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
