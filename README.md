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

### Более полный .gitignore

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

### Добавлены технические пакеты для упрощения разработки и улучшения её качества

"barryvdh/laravel-ide-helper",
"beyondcode/laravel-dump-server",
"friendsofphp/php-cs-fixer": "^2.16",
"php-parallel-lint/php-var-dump-check": "^0.5.0",
"psalm/plugin-laravel": "^1.4",
"vimeo/psalm": "^4.1"

Часть из них задействована в хуках

### robots.txt

robots.txt изменен, чтобы по-умолчанию приложение запрещало роботам индексацию если они вдруг до него доберутся

