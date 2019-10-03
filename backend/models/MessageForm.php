<?php
namespace backend\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use common\models\db\TabMessage;

/**
 * модель "Получения и обработки GET данных, и загрузки из БД сообщений администратору"
 */
class MessageForm extends Model
{
  /**
   * @param параметры модели
   */
  public $filter_msg;

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      //По умолчанию NULL
      ['filter_msg', 'default'],
      //Проверка значения параметра, если он задан
      //Прим. Валидное значение параметра должно быть ключем в массиве "названий
      //статусов сообщения" - в свойстве "$_nameStatus" класса "TabMessage"
      ['filter_msg', 'filter', 'filter' => function ($value){
        return (isset($value) && ArrayHelper::keyExists($value, TabMessage::nameStatus())) ? (int)$value : null;
      }],
    ];
  }


  /**
   * Метод создания Провайдера данных
   * @return ActiveDataProvider - провайдер данных
   */
  public function getDataProvider()
  {
    $query = TabMessage::find();
    if (isset($this->filter_msg)) {
      $query->where('status=:status', [':status' => $this->filter_msg]);
    } else {
      $query->where(['>', 'status', 0]);
    }
    $query->orderBy('id ASC');
    return new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSizeLimit' => [10, 50],
        'defaultPageSize' => 10,
        'pageSizeParam' => 'limit',
      ],
      'sort' => false,
      'key' => 'id',
    ]);
  }
}
?>
