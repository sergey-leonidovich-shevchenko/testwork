#!/bin/bash

clear
rm -rf ./var/cache/*

echo 'Подтягиваем composer зависимости...'
composer install -n

echo 'Запускаем php-fpm...'
exec "${@}"
