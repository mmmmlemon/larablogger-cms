<div style="text-align: center;">
    <img src="readme/logo.png" alt="">
    <h3>CMS для видеоблогов на Laravel</h3>
</div>
<hr/>
<div>
    <h3>Что это?</h3>
    <p><b>LaraBlogger</b> это CMS на основе фреймворка Laravel для ведения персонального блога. Ключевая особенность - менеджер файлов позволяющий прикреплять к постам фото и, что самое главное, видео-контент.</p>
</div>
<div>
    <img src="readme/photo1.png" alt="">
</div>
<div>
    <h3>Ключевые функции</h3>
    <ul>
        <li>Модерация постов и комментариев. Посты можно делать скрытыми, прикреплять. То же самое и комментариями.</li>
        <li>К постам можно прикреплять фото и видео. У видео можно установить заставку и залить субтитры (как на YouTube). Есть встроенный менеджер файлов, всеми медиафайлами на сайте можно управлять.</li>
        <li>Категории профилей: Администратор, Модераторы и Пользователи.</li>
        <li>Можно создавать свои категории постов на сайте, менять дизайн у сайта.</li>
        <li>Система обратной связи для пользователей сайта.</li>
    </ul>
</div>
<hr/>
<div>
    <h3>Установка</h3>
<p>Для того чтобы установить СMS на локальной машине или хостинге сделайте следующие шаги <i>(вам будут нужны PHP 7.2 или новее и MySQL или другая любая СУБД поддерживаемая Laravel установленные на машине)</i>.</p>
<ol>
    <li>Создайте в каталоге сайта файл <i>".env"</i>, за основу можно взять файл <i>".env.example"</i> из репозитория.</li>
    <li>Создайте пустую базу данных в выбранной вами СУБД (в этом примере название базы будет "videoblog" в MySQL) и пропишите соединение с вашей СУБД в файле <i>".env"</i> как показано ниже</li>
    <blockquote>
        <p>DB_CONNECTION=mysql</p>
        <p>DB_HOST=127.0.0.1</p>   
        <p>DB_PORT=3306</p>
        <p>DB_DATABASE=videoblog</p>
        <p>DB_USERNAME=*database_username*</p>
        <p>DB_PASSWORD=*database_password*</p>
    </blockquote>
    <li>Установите <a href="https://getcomposer.org/" target="_blank">Composer</a> и запустите команду <i>"composer update"</i> в корневой директории сайта. Это установит все необходимые компоненты для работы CMS.</li>
    <li>Откройте файл <i>AppServiceProvider.php</i> (находится в папке <i>app/Providers</i>) и раскомментируйте функцию <i>boot()</i></li>
    <li>Запустите команду <i>"php artisan migrate"</i>, это заполнит базу данных нужной информацией.</li>
    <li>Запустите команду  <i>"php artisan storage:link"</i>, чтобы включить хранилище файлов на сайте.</li>
    <li>Запустите команду <i>"php artisan key:generate"</i>, чтобы сгенерировать защитный ключ.</li>
    <li>Запустите команду <i>"php artisan config:cache"</i>, чтобы очистить кэш сайта (иногда без этого не распознаётся ключ).</li>
    <li>Запустите команду <i>"php artisan serve"</i>, чтобы запустить сайт (работает только на локальном сервере, не нужно на хостинге).</li>
    <li>Откройте ссылку <i>"127.0.0.1/register"</i> (либо <i>ссылка-на-ваш-сайт.ру/register)</i>) и зарегистрируйте первого пользователя, у первого пользователя будут права Администратора.</li>
</ol>

<p>Для того чтобы запустить проект на хостинге сделайте следующее</p>
<ol>
    <li>Загрузите CMS в корневую папку вашего сайта.</li>
    <li>Повторите шаги 1-7 из инструкции выше.</li>
    <li>Если ваш хостинг ищёт index-файл в папке с названием public_html то используйте <a href="https://stackoverflow.com/questions/30198669/how-to-change-public-folder-to-public-html-in-laravel-5" traget="_blank">эту инструкцию</a> чтобы изменить папку public у CMS на public_html.</li>
    <li>Зарегистрируйте пользователя-администратора как в шаге 10 у инструкции выше.</li>
</ol>
<div>
    <h3>Система обратной связи</h3>
    <p>Чтобы включить<b>Систему обратной связи</b> сделайте следующее.</p>
    <ol>
        <li>Откройте контрольную панель у веб-сайта и установите "Contact e-mail" и "Sender e-mail" в настройках сайта. "Contact e-mail" это почта на которую будут приходить письма с обратной связью. "Sender e-mail" это почта на вашем хостинге которая будет отправлять эти письма.</li>
        <li>Откройте файл ".env" и добавьте информацию о вашем smpt сервере как в примере ниже.</li>
        <blockquote>
            <p>MAIL_MAILER=smtp</p>
            <p>MAIL_HOST=smtp.my-email-server.com</p>
            <p>MAIL_PORT=2525</p>
            <p>MAIL_USERNAME=my-sender-email@mail.com</p>
            <p>MAIL_PASSWORD=my-sender-email-password</p>
            <p>MAIL_ENCRYPTION=tls</p>
        </blockquote>
        <li>Используйте команду <i>"php artisan config:cache"</i> если конфигурация smpt-сервера на обновится.</li>
    </ol>
</div>
</div>

<hr>
<div>
    <img src="readme/photo2.png" alt="">
    <img src="readme/photo3.png" alt="">
</div>
<div>
    <h3>Открытые библиотеки использованные в этом проекте</h3>
    <ul>
        <li><a href="https://laravel.com/" target="_blank">Laravel</a></li>
        <li><a href="https://bulma.io/" target="_blank">Bulma</a></li>
        <li><a href="https://github.com/vyachkonovalov/bulma-tagsfield" target="_blank">Bulma Tags Field</a></li>
        <li><a href="https://www.dropzonejs.com/" target="_blank">Dropzone.JS</a></li>
        <li><a href="https://github.com/webfashionist/RichText" target="_blank">jQuery RichText</a></li>
        <li><a href="https://plyr.io/" target="_blank">Plyr</a></li>
    </ul>
</div>
<hr>
<div>
    <p>Эта CMS открыта и свободна для использования.</p>
</div>
