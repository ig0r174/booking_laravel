# RESTFul сервис бронирования номеров в отеле
## Основан на Laravel v. 8.83.27

В качестве дополнения я использовал Docker и Nginx, чтобы облешчить запуск. Достаточно ввести команду:

<code>docker-compose up</code>

Сервис будет доступен по 80 порту локальной сети.

## Основные эндпоинты
| URL                             | Параметры                                                                                                               | Метод       | Описание                                          |
|---------------------------------|-------------------------------------------------------------------------------------------------------------------------|-------------|---------------------------------------------------|
| /api/booking                    | status – статус (confirmed или not confirmed)                                                                           | GET         | Получить список броней                            |
| /api/booking                    | arrive_date – дата заезда<br/>user_id – привязанный пользователь                                                        | POST        | Создание брони                                    |
| /api/booking/{id}               | id – идентификатор брони                                                                                                | DELETE      | Удалить бронь                                     |
| /api/booking/{id}               | id – идентификатор брони<br/>arrive_date – дата заезда<br/>user_id – привязанный пользователь                           | PUT / PATCH | Редактирование брони                              |
| /api/booking/{id}               | id – идентификатор брони                                                                                                | GET         | Информация о брони {id}                           |
| /api/register                   | email – E-mail пользователя<br/>name – имя пользователя<br/>password – пароль<br/>password_confirmation – повтор пароля | POST        | Регистрация                                       |
| /api/login                      | e-mail – E-mail пользователя<br/>password – пароль                                                                      | POST        | Вход                                              |
| /api/logout                     |                                                                                                                         | GET         | Выход                                             |
| /api/profile/{user_id}/bookings | user_id – идентификатор пользователя                                                                                    | GET         | Список броней пользователя {user_id}              |

Метод получения списка броней и метод получения списка броней пользователя поддерживают параметры limit и offset, а также фильтрацию по статусу (confirmed / not confirmed).

## JWT

Для получения доступа к ресурсам необходимо передать JWT-токен в заголовке Authorization со значением Bearer {token}, где {token} – полученный токен из регистрации или авторизации.