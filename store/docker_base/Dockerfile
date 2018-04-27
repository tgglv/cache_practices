FROM alpine:latest

RUN apk add --update --no-cache \
    nginx wget curl git nano \
    php7 php7-mysqli php7-session php7-json php7-ctype php7-fpm tzdata php7-redis \
    php7-bcmath php7-phar php7-curl php7-openssl php7-mbstring php7-iconv php7-tokenizer php7-dom php7-xmlwriter php7-xml

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer