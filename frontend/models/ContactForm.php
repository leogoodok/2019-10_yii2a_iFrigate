<?php
namespace frontend\models;
use Yii;
use yii\base\Model;
use common\models\db\TabMessage;

/**
 * ContactForm - модель "Отправки сообщения администратору"
 */
class ContactForm extends Model
{
  /**
   * @param параметры модели (поля формы)
   */
  public $name;
  public $email;
  public $subject;
//     public $phone;
  public $body;
  public $files;
  public $verifyCode;


  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      // name, email, subject и body необходимы
      [['name', 'email', 'subject', 'body'], 'required', 'message' => 'Пожалуйста, заполните поле'],
      // email должен быть действительный адрес электронной почты
      ['email', 'email'],
      // name, email должно быть от 2 до 100 символов
      [['name', 'email'], 'string', 'length' => [2, 100]],
      // name и email удалить пробелы перд и после
      [['name', 'email'], 'trim'],
      //phone телефон должен соответствовать шаблону +7(999)888-77-66
//     ['phone', 'match', 'pattern' => '/\+\d{1,3}\(?\d{1,3}\)\d{1,3}\-\d{2}-\d{2}$/'],
      //files должен быть: или рисунок или ..., макс.размер 5Мб, макс. 10 файлов
      [['files'], 'file', 'skipOnEmpty' => true, 'mimeTypes' => ['image/*', 'application/zip', 'application/gzip', 'text/plain', 'application/pdf', 'application/msword'], 'maxSize' => 2*1024*1024, 'maxFiles' => 10],
      // verifyCode должен быть введен правильно
      ['verifyCode', 'captcha'],
    ];
  }


  /**
   * Названия полей формы (label)
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'name' => 'Введите Ваше имя*',
      'email' => 'Введите Ваш Email*',
      'subject' => 'Тема сообщения',
      'body' => 'Содержание сообщения',
      'verifyCode' => 'Код верификации',
    ];
  }


  /**
   * Отправляет электронное письмо на адрес электронной почты Администратора,
   * используя информацию собранную этой моделью.
   * @param string $email адрес электронной почты отправителя
   * @return bool было ли письмо отправлено
   */
  public function sendEmail($email)
  {
    $message = Yii::$app->mailer->compose()
      ->setTo($email)
      ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
      ->setReplyTo([$this->email => $this->name])
      ->setSubject($this->subject)
      ->setTextBody($this->body)
      ->setHtmlBody(
        '<h3>Здравствуйте,</h3>'
        .$this->body
        .'<p style="font-weight: bold;">С уважением,</p>'
        .'<p style="font-weight: bold;">'.$this->name.'</p>'
        .'<p>Email: ' . $this->email . '</p>'
//          .'<p>Телефон: ' . $this->phone . '</p>'
      )->addHeader('Precedence', 'bulk');
    if($this->files) {
      foreach ($this->files as $file) {
        $message->attach($file->tempName, ['fileName' => $file->baseName . '.' . $file->extension]);
      }
    }
    return $message->send();
  }


  /**
   * Сохраняет информацию собранную этой моделью в БД
   * @return bool было ли письмо сохранено в БД
   */
  public function saveMSG()
  {
    $message = new TabMessage();
    $message->name = $this->name;
    $message->email = $this->email;
    $message->status = 1;
    if (!empty($this->files)) {
      $message->number_attachment = count($this->files);
    }
    $message->created_at = time();
    return $message->save();
  }
}
