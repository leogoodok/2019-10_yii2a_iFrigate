/**
 * Обёртка jQuery для избежания конфликтов  при использовании
 * псевдонима $ с другими JS библиотеками
 */
jQuery(function($){
  /**
   *  Выполнить после построения страницы:
   */
  $(document).ready(function () {
    $(function() {
      /**
       *  Добавление атрибута "data-name"
       *  Удаление атрибута "name"
       *  всех элементов выбора "Статуса сообщения" и
       *  всех элементов выбора "Статуса сообщения"
       */
      jQuery('#table1').find('select').each(function(i,elem) {
        $(this).attr('data-name', $(this).attr('name'));
        $(this).removeAttr('name');
      });
    });


    /**
     *  Назначение обработчика события изменения значения
     * элементов выбора "Удалить сообщение"
     */
    jQuery('#table1').find('select').change(function() {
      if ($(this).data('value') == $(this).val()) {
        $(this).removeAttr('name');
        $(this).removeClass('bg-warning');
      } else {
        $(this).attr('name', $(this).attr('data-name'));
        $(this).addClass('bg-warning');
      }
    });
  });
});
