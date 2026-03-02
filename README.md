# abelohosttest
Тестовое задание для Full-stack Developer (PHP / JS) AbeloHost

## Инструкция по запуску: ##

1. клонируем проект: **git clone https://github.com/arivlav/abelohosttest** и переходим в него (cd abelohosttest)
2. Разворачиваем контейнеры через docker-compose:
   * Переименуйте (скопируйте) файл .env.example в .env и отредактируйте его по своему усмотрению или оставьте как есть
   * Запускаем контейнеры: **docker-compose up -d** (флаг -d (detached) запускает контейнеры в фоне, позволяя закрыть терминал)
   * (Опционально) Можно проверить работу контейнеров: **docker-compose ps**
   * (Опционально) Остановить работу контейнеров можно: **docker-compose down**
3. Не забываем добавить в hosts такое же значение как для APP_SERVER_URL в .env:
   
   В .env:
   
   APP_SERVER_URL=**abelohosttest.loc**
   
   Н-р, для linux

         nano /etc/hosts
   В самом файле добавляем строку: 127.0.0.1         abelohosttest.loc   
4. Переходим http://abelohosttest.loc (или тот, который указали в .env APP_SERVER_URL)

