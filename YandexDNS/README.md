# Работа с Yandex DNS как регистратор

- Добавление домена
- Парковка домена
- Добавление, редактирование, и удаление DNS записей

# Создание регистранта на Яндексе

## Зарегистрировать приложение

- На сервисе OAuth: https://oauth.yandex.ru/client/new
- ID: id, Пароль: pass
- Callback URL: https://oauth.yandex.ru/verification_code

## Создать учетную запись регистратора

- На странице управления регистратором: https://pddimp.yandex.ru/api2/registrar/registrar
- Registrar id: registrar, Registrar name: name, Registrar password: pass

## Получение OAuth client ID

- По ID приложения https://oauth.yandex.ru/authorize?response_type=token&client_id=id
- OAuth client ID: OAuth_client_ID

## Получить Pdd-токен

- На странице управления токеном
- Указать идентификатор созданного регистратора https://pddimp.yandex.ru/api2/registrar/get_token