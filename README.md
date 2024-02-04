# Accounting system

Представляет из себя модифицированную под наши нужды экземпляр `Laravel 9.2.0`
## Разворот непосредественно самого скелетона локально для разработки

Разворот сервис не отличается от разворота любого другого сервиса Eijen и описан [тут](https://docs.ensi.tech/installation/local/backend)

## Разворот сервиса из Backend Service Skeleton

1. `git clone git@gitlab.com:greensight/ensi/backend-service-skeleton.git <new-repo-name>`
2. `cd <new-repo-name>`
3. `rm -rf .git && git init`
4. `git remote add origin git@gitlab.com:greensight/<project>/<new-repo-name>.git`
5. Указываем в `.env.example` нужный `APP_NAME`
6. Переимновываем/удаляем все заглушки вроде `backend_skeleton` в конфигах, документации и коде сервиса
7. `cp .env.example .env`
8. Указываем в .env доступы к БД
9. Обновляем `README.md`
10. `composer i && npm i`
11. `git add . && git commit -m "Initial commit" && git push -u origin master`
12. `php artisan key:generate`
13. `php artisan storage:link`
