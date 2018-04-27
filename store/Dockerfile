FROM timurns/cache_practices_bookstore:latest

# Application Settings:
# - Storage Type: mysql|redis
# - Opcache: on|off
# - Twig Cache: on|off
ENV SETTINGS_STORAGE_TYPE="mysql" \
    SETTINGS_STORE_OPCACHE="on" \
    SETTINGS_STORE_TWIG_CACHE="on" \
    MYSQL_HOST="mysql" \
    MYSQL_USER="bookstore" \
    MYSQL_USER_PASSWORD="12345" \
    REDIS_HOST="redis" \
    PHP_FPM_USER="www" \
    PHP_FPM_GROUP="www" \
    PHP_FPM_LISTEN_MODE="0660" \
    PHP_MEMORY_LIMIT="512M" \
    PHP_MAX_UPLOAD="50M" \
    PHP_MAX_FILE_UPLOAD="200" \
    PHP_MAX_POST="100M" \
    PHP_DISPLAY_ERRORS="On" \
    PHP_DISPLAY_STARTUP_ERRORS="On" \
    PHP_ERROR_REPORTING="E_COMPILE_ERROR\|E_RECOVERABLE_ERROR\|E_ERROR\|E_CORE_ERROR" \
    PHP_CGI_FIX_PATHINFO=0 \
    PHP_FPM_CONF="/etc/php7/php-fpm.conf" \
    PHP_FPM_WWW_CONF="/etc/php7/php-fpm.d/www.conf" \
    PHP_INI="/etc/php7/php.ini" \
    PHP_OPCACHE_INI="/etc/php7/conf.d/00_opcache.ini" \
    TIMEZONE="Asia/Yekaterinburg" \
    DIR="/var/lib/nginx/html"

RUN mkdir -p ${DIR} && \
    chmod -R 0777 "${DIR}/../"


WORKDIR ${DIR}
ADD . ${DIR}

RUN adduser -D -g 'www' www && \
    sed -i "s|;listen.owner\s*=\s*nobody|listen.owner = ${PHP_FPM_USER}|g" ${PHP_FPM_WWW_CONF} && \
    sed -i "s|;listen.group\s*=\s*nobody|listen.group = ${PHP_FPM_GROUP}|g" ${PHP_FPM_WWW_CONF} && \
    sed -i "s|;listen.mode\s*=\s*0660|listen.mode = ${PHP_FPM_LISTEN_MODE}|g" ${PHP_FPM_WWW_CONF} && \
    sed -i "s|user\s*=\s*nobody|user = ${PHP_FPM_USER}|g" ${PHP_FPM_WWW_CONF} && \
    sed -i "s|group\s*=\s*nobody|group = ${PHP_FPM_GROUP}|g" ${PHP_FPM_WWW_CONF} && \
    sed -i "s|;log_level\s*=\s*notice|log_level = notice|g" ${PHP_FPM_CONF} && \
    sed -i "s|;*daemonize.*=.*|daemonize = no|i" ${PHP_FPM_CONF} && \
    sed -i "s|listen = 127.0.0.1:9000*|listen = 0.0.0.0:9000|i" ${PHP_FPM_WWW_CONF} && \
    sed -i "s|display_errors\s*=\s*Off|display_errors = ${PHP_DISPLAY_ERRORS}|i" ${PHP_INI} && \
    sed -i "s|display_startup_errors\s*=\s*Off|display_startup_errors = ${PHP_DISPLAY_STARTUP_ERRORS}|i" ${PHP_INI} && \
    sed -i "s|error_reporting\s*=\s*E_ALL & ~E_DEPRECATED & ~E_STRICT|error_reporting = ${PHP_ERROR_REPORTING}|i" ${PHP_INI} && \
    sed -i "s|;*memory_limit =.*|memory_limit = ${PHP_MEMORY_LIMIT}|i" ${PHP_INI} && \
    sed -i "s|;*upload_max_filesize =.*|upload_max_filesize = ${PHP_MAX_UPLOAD}|i" ${PHP_INI} && \
    sed -i "s|;*max_file_uploads =.*|max_file_uploads = ${PHP_MAX_FILE_UPLOAD}|i" ${PHP_INI} && \
    sed -i "s|;*post_max_size =.*|post_max_size = ${PHP_MAX_POST}|i" ${PHP_INI} && \
    sed -i "s|;*cgi.fix_pathinfo=.*|cgi.fix_pathinfo= ${PHP_CGI_FIX_PATHINFO}|i" ${PHP_INI} && \
    cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && \
    echo "${TIMEZONE}" > /etc/timezone && \
    sed -i "s|;*date.timezone =.*|date.timezone = ${TIMEZONE}|i" ${PHP_INI}

# TODO: переделать на цикл
RUN echo "env[MYSQL_HOST] = \"${MYSQL_HOST}\"" >> ${PHP_FPM_WWW_CONF} && \
    echo "env[MYSQL_USER] = \"${MYSQL_USER}\"" >> ${PHP_FPM_WWW_CONF} && \
    echo "env[MYSQL_USER_PASSWORD] = \"${MYSQL_USER_PASSWORD}\"" >> ${PHP_FPM_WWW_CONF} && \
    echo "env[REDIS_HOST] = \"${REDIS_HOST}\"" >> ${PHP_FPM_WWW_CONF} && \
    echo "env[SETTINGS_STORAGE_TYPE] = \"${SETTINGS_STORAGE_TYPE}\"" >> ${PHP_FPM_WWW_CONF} && \
    echo "env[SETTINGS_STORE_OPCACHE] = \"${SETTINGS_STORE_OPCACHE}\"" >> ${PHP_FPM_WWW_CONF} && \
    echo "env[SETTINGS_STORE_TWIG_CACHE] = \"${SETTINGS_STORE_TWIG_CACHE}\"" >> ${PHP_FPM_WWW_CONF}

RUN if [ "${SETTINGS_STORE_OPCACHE}" = "on" ] ; \
    then \
        apk add php7-opcache && \
        mkdir /var/opcache && \
        chmod -Rv 0777 /var/opcache && \
        echo "opcache.file_cache=/var/opcache" >> ${PHP_OPCACHE_INI} && \
        echo "opcache.validate_timestamps=0" >> ${PHP_OPCACHE_INI}; \
fi

RUN cd ${DIR} && \
    composer install && \
    chmod -R 0777 "${DIR}/cache"

RUN rm /etc/nginx/conf.d/default.conf /etc/nginx/nginx.conf && \
    mkdir /var/run/nginx && \
    touch /var/run/nginx.pid

COPY ./nginx.conf /etc/nginx
COPY ./store.conf /etc/nginx/conf.d
COPY ./run_store.sh /

RUN chmod +x /run_store.sh

ENTRYPOINT ["/run_store.sh"]
