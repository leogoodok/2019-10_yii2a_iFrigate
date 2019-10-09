<?php
namespace backend\models;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\db\TabMessage;

/**
 * Модель "Получения и обработки POST данных, обновления и удаления сообщений в БД"
 */
class MessageAction extends Model
{
  /**
   * @param параметры модели
   */
  public $status;
  public $delete;

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      //По умолчанию NULL
      [['status', 'delete'], 'default'],
      //Проверка значения параметра, если он задан
      //Прим. Валидное значение параметра должно быть ключем в массиве "названий
      //статусов сообщения" - в свойстве "$_nameStatus" класса "TabMessage"
      ['status', 'filter', 'filter' => function ($variable){
        if (empty($variable) || !is_array($variable)) return;
        $result = [];
        foreach ($variable as $key => $value) {
          if (isset($value) && ArrayHelper::keyExists($value, TabMessage::nameStatus())) {
            $result[$key] = (int)$value;
          }
        }
        return $result;
      }],
      //Прим. Валидное значение параметра - int И != 0
      ['delete', 'filter', 'filter' => function ($variable){
        if (empty($variable) || !is_array($variable)) return;
        $result = [];
        foreach ($variable as $key => $value) {
          if (!empty($value) && is_numeric($value)) {
            $result[$key] = (int)$value;
          }
        }
        return $result;
      }],
    ];
  }


  /**
   * Метод получения статуса сообщений по ID строки таблицы
   * Если задано значение статуса для проверки, то
   * проверка не равенства статуса сообщения заданному
   * @return null|bool|int
   */
  public function matchStatusMSG($id = null, $newStatus = null)
  {
    if (isset($id) && ($message = TabMessage::findOne(['id' => $id]))) {
      if (isset($newStatus)) {
        return ($message->status != $newStatus);
      } else {
        return (int)$message->status;
      }
    }
    return;
  }


  /**
   * Метод обновления статуса сообщений
   * @return null|bool
   */
  public function updateStatusMSG()
  {
    if (empty($this->status)) return;
    $result = true;
    foreach ($this->status as $id => $newStatus) {
      if ($message = TabMessage::findOne(['id' => $id])) {
        if ($this->matchStatusMSG($id, $newStatus)) {
          $message->status = $newStatus;
          if ($newStatus != 0) {
            $message->updated_at = time();
          } else {
            $message->delete_at = time();
          }
          if (!$message->save()) {
            $result = false;
          }
        }
      }
    }
    return $result;
  }


  /**
   * Метод удаления сообщений
   * @return bool
   */
  public function deleteMSG()
  {
    if (empty($this->delete)) return;
    $result = true;
    foreach ($this->delete as $id => $value) {
      if (!empty($value)) {
        if ($message = TabMessage::findOne(['id' => $id])) {
          if (!$message->delete()) {
            $result = false;
          };
        }
      }
    }
    return $result;
  }
}
?>
