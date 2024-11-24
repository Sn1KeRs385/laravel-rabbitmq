## RabbitMq для laravel

### Установка
1. Добавление пакета через композер
    ```
    composer require sn1kers/laravel-rabbitmq
    ```
2. Публикация файла конфига 
    ```
    php artisan vendor:publish --provider="Sn1KeRs\LaravelRabbitmq\RabbitMQServiceProvider" --tag="config"
    ```
3. Добавить в .env параметры и поменять под ваше подключение
    ```
    RABBIT_MQ_HOST=127.0.0.1
    RABBIT_MQ_PORT=5672
    RABBIT_MQ_USER=user
    RABBIT_MQ_PASSWORD=password
    RABBIT_MQ_VHOST=/
    ```
   
### Broadcasting
Примеры настройки бродкастера можно найти в файле 
```vendor/sn1kers/laravel-rabbitmq/config/broadcasting_example```
Пример отправки ивента через бродкастер можно найти в файла
```vendor/sn1kers/laravel-rabbitmq/src/Events/ExampleEvent```