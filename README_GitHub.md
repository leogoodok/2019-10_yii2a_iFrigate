# yii2aIFrigate.wotskill
  GitHub репозиторий выполнения тестового задания от "Интернет Фрегат".
  Выполнено на основе Расширенного шаблона приложения, переделанного
  - для использования на одном виртуальном хостинге;
  - под использование только "bootstrap4" без "bootstrap3".
  Задание в файле "README_Ifrigate.md".
1. Создание БД, пользователей БД, настройка подключения к БД.
2. Настройка почтовой службы (компонент 'mailer') и почтовых ящиков администратора, поддержки и т.д.
3. Создание Администратора приложения: "ifrigate"
  Прим. Первый зарегистрированный пользователь станет Администратором.
4. Обновление компонентов Yii2 с использованием "composer".
5. Создание "COMMON" части приложения (Общей для "frontend" и "backend").
5.1. Модель "TabMessage" (потомок класса "ActiveRecord") таблицы БД "message"
    (сообщений администратору) в файле "common\models\db\TabMessage.php".
6. Создание "FRONTEND" части приложения.
6.1 Представлений (views):
  - Основного шаблова страниц, в файле "frontend\views\layouts\main.php".
  - "Отправить сообщение администратору", в файле "frontend\views\site\contact.php".
  - "Вывода ошибок", в файле "frontend\views\site\error.php".
  - "Зарегистрироваться", в файле "frontend\views\site\signup.php".
  - "Войти", в файле "frontend\views\site\login.php".
6.2. Контроллер "SiteController", в файле "frontend\controllers\SiteController.php".
6.2.1. Создание/редактирование в Контроллере, Поведения и Действий:
      - "actionContact" редеринга Представления "frontend\views\site\contact.php".
      - "actionLogin" редеринга Представления "Войти" "frontend\views\site\login.php".
      - "actionLogout" Выхода из текущей авторизации пользователя.
      - "actionSignup" редеринга Представления "Зарегистрироваться" "frontend\views\site\signup.php".
6.3. Модели Отправки сообщения администратору "ContactForm" (по почте и запись БД)
      в файле "frontend\models\ContactForm.php".
7. Создание "BACKEND" части приложения.
7.1. Представлений (views):
  - Основного шаблова страниц, в файле "backend\config\main.php".
  - "Отображения сообщений администратору", в файле "backend\views\site\message.php".
  - JS скриптов для представления "message", в файле "docs\admin\js\message.js"
7.2. Контроллер "SiteController", в файле "backend\controllers\SiteController.php".
7.2.1. Создание/редактирование в Контроллере, Поведения и Действий:
      - "actionMessage" редеринга Представления "backend\views\site\message.php".
      - "actionLogin" редеринга Представления "Войти", "backend\views\site\login.php".
      - "actionLogout" Выхода из текущей авторизации пользователя.
7.3. Моделей.
7.3.1. Модель "MessageForm" отображения сообщений администратору,
      в файле "backend\models\MessageForm.php".
7.3.2. Модель "MessageAction" Получения и обработки POST данных, обновления и
      удаления сообщений в БД, в файле "backend\models\MessageAction.php".
