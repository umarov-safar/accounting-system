# Ensi Backend Service Skeleton

Представляет из себя модифицированную под наши нужды экземпляр `Laravel 8.4.2`

## Установка

1. git clone git@gitlab.com:greensight/ensi/backend-service-skeleton.git <new-repo-name>
2. cd <new-repo-name>
3. rm -rf .git && git init
4. git remote add origin git@gitlab.com:greensight/<project>/<new-repo-name>.git
5. Указываем в .env.example нужный APP_NAME
5. cp .env.example .env
6. Указываем в .env доступы к БД
7. composer i && npm i
8. git add . && git commit -m "Initial commit" && git push -u origin master

## Модификации относительно чистого Laravel

### Готовый .gitignore

Внесены всякие pdf-ки, архивы, служебные файлы IDEшек и служебных инструментов

### Timezone и Locale

config('app.timezone') = 'Europe/Moscow';
config('app.locale') = 'ru';

### Дополнительный общий .env

Переменные окружения читаются не только из .env файла в корне проекта, но и из .env файла в директории на уровень выше, если он есть.
Приоритет остается у переменных из локального файла. Упрощает управление переменными окружения на тестовом сервере с множеством площадок.
Реализация в `bootstrap/environment.php`.

### Добавлены lang файлы для русского языка

Для пагинации и валидации.

### Git hooks

Хуки лежат в репозитории, в директории .git_hooks
Устанавливаются автоматически через husky во время npm install

Управлять какие именно хуки выполняются и в каком порядке можно в файле .huskyrc.json, он лежит в репозитории.
При необходимости его содержимое можно переопределить файлом .huskyrc с тем же форматом, он уже находится в .gitignore.

### Ensi Storage

Для работы с файлами в Ensi добавлены `app()->make('ensi.filesystem')` и `EnsiStorage` фасад которые полностью аналогичны 
`app()->make('filesystem')` и `Storage`, но имеют дополнительные методы `protected(?string $service = null)` и `public(?string $service = null)` которые возвращают публичный или защищенный диск для нужного (или текущего) сервиса.
Например, вызовом `$content = EnsiStorage::protected('pim')->get('test.csv')` можно получить содержимое файла `test.csv`, лежащего в директории для сервиса pim в защищенном файловом хранилище.

`public` - содержимое доступно всем по `https://s.project.domain/<service_code>/...`
`protected` - доступно только самому сервису и другим сервисам ensi

Для работы всего этого нужно
1. Чтобы config/app.php был выставлен корректный код текущего сервиса
2. В config/filesystems.php в $ensiServicesCodes нужно задать список сервисов, с чьими хранилищами будет осуществляться взаимодействие (включая текущий).

### Добавлены технические пакеты для упрощения разработки и улучшения её качества

"barryvdh/laravel-ide-helper",
"beyondcode/laravel-dump-server",
"friendsofphp/php-cs-fixer",
"php-parallel-lint/php-var-dump-check",
"psalm/plugin-laravel",
"vimeo/psalm",

Часть из них задействована в хуках

### Добавлен хэлсчек

GET /health возвращает ОК с кодом 200.

### Установлены пакеты для работы с API

"greensight/laravel-serve-swagger", // Документация достуна по /docs/swagger
"greensight/laravel-openapi-client-generator",
"greensight/laravel-openapi-server-generator"

### Подчищено всё ненужное из Laravel для чистоты и быстродействия

- встроенный в Laravel фронтэнд
- всё что касается User Management-а и сессий
- AWS, PUSHER и прочее в конфигах
- Broadcasting
- большинство middleware подлюкченных по-умолчанию
- часть Service Provider-ов закоментирована (если из-за этого что-то сломалось - раскоментируйте)

### robots.txt

robots.txt изменен, чтобы по-умолчанию приложение запрещало роботам индексацию если они вдруг до него доберутся

