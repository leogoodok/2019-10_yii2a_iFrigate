<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password обязательные поля
            [['username', 'password'], 'required', 'message' => 'Пожалуйста, заполните поле'],
            // rememberMe должно быть логическим значением
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
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
        'password' => 'Пароль*',
        'rememberMe' => 'Запомни меня',
      ];
    }


    /**
     * Подтверждает пароль.
     * Этот метод служит встроенной проверкой пароля.
     *
     * @param string $attribute какой атрибут в настоящее время проверяется
     * @param array $params дополнительные пары имя-значение указанные в правиле
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }


    /**
     * Вход пользователя с использованием предоставленного имени пользователя и пароля.
     *
     * @return bool успешно ли пользователь вошел в систему
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }


    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
