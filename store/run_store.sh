#!/bin/sh
php-fpm7 -c /etc/php7 & \
  nginx
