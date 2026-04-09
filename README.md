# Short Linker

Сервис сокращения ссылок + генерация QR-кодов на Yii2


## Стек

* PHP 8.2
* Yii2 Basic
* MySQL
* Docker + docker-compose
* jQuery + Bootstrap


## Установка и запуск

### Создать `.env`

Создай файл `.env` в корне проекта:

```env
cp .env.example .env
```


### Запустить Docker

```bash
docker compose up -d --build
```

Проверить, что контейнеры поднялись:

```bash
docker compose ps
```


### Установить зависимости

```bash
docker compose exec php composer install
```


### Применить миграции

```bash
docker compose exec php php yii migrate
```

Подтвердить:

```bash
yes
```


### Открыть проект

```text
http://localhost:8080
```

## Проверка работы

### Создание короткой ссылки

1. Вставить URL (например `https://google.com`)
2. Нажать "OK"
3. Должно появиться:

   * короткая ссылка
   * QR-код


### Проверка редиректа

1. Открыть короткую ссылку
2. Произойдет редирект на оригинальный URL
3. В БД увеличится счетчик переходов


## База данных

Используется MySQL внутри Docker.

Доступ:

* Host: `localhost`
* Port: `3306`
* DB: `short_linker`
* User: `root`
* Password: `root`


## Полезные команды

Перезапуск контейнеров:

```bash
docker compose restart
```

Остановить:

```bash
docker compose down
```

Логи:

```bash
docker compose logs -f
```
