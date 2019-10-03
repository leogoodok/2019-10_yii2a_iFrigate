<?php
namespace common\models\db;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * Класс потомок "ActiveRecord" ассоциированный с таблицей "message"
 * @author "BigLeoGood"
 */
class TabMessage extends ActiveRecord
{
  /**
   * @var array массив названий статусов сообщения
   */
  static protected $_nameStatus = [
    'Удалено',
    'Новое',
    'Прочитано',
    'Принято',
  ];


  /**
   * @return string название таблицы, сопоставленной с классом
   */
  public static function tableName()
  {
    return '{{message}}';
  }


  /**
   * @param int $index названия статуса сообщения
   * @return array|string|null
   *     1) $index не задан метод возвращает весь масив названий
   *     2) $index задан метод возвращает название $index статуса
   *        или null если название $index статуса не определено
   */
  static public function nameStatus($index = null)
  {
    return isset($index) && is_int($index)
      ? (ArrayHelper::keyExists($index, static::$_nameStatus) ? static::$_nameStatus[$index] : null)
      : static::$_nameStatus;
  }


  /**
   * @return string|null получить название статуса сообщения
   */
  public function getStatusText()
  {
    return $this->nameStatus($this->status);
  }


  /**
   * @param string $format Шаблон вывода даты
   * @return string строковое представление даты создания сообщения
   */
  public function getCreatedDate($format = 'H:i d.m.Y')
  {
    return date($format, $this->created_at);
  }


  /**
   * @param string $format Шаблон вывода даты
   * @return string строковое представление даты создания обновления статуса
   */
  public function getUpdatedDate($format = 'H:i d.m.Y')
  {
    return date($format, $this->updated_at);
  }


  /**
   * @param string $format Шаблон вывода даты
   * @return string строковое представление даты удаления сообщения
   */
  public function getDeleteDate($format = 'H:i d.m.Y')
  {
    return date($format, $this->delete_at);
  }
}
?>
