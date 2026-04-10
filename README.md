## Laravel-API

## Иструкция

### 1:

Создать .env и скопировать все из .env.example

### 2:

Создать контейнер для db

```
docker-compose up -d --build
```


### 3:

Установать composer

```
composer install
```


### 4:

Провести миграции

```
php artisan migrate
```

### 5:

Запуск

```
php artisan serve
```

### 6:

Для проверки работы используйте свагер

```
http://127.0.0.1:8000/docs
```

## swagger

![Авторизация](/pic/autho.png)

![Автор](/pic/authors.png)

![Книги](/pic/book.png)

![Жанры](/pic/genre.png)

Для того чтобы воспользоваться авторизацией нужно зарегистрироваться и выданный токен вставить в рамку по нажатию на кнопку Authorize

![Кнопка](/pic/key.png)