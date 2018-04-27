FROM cache_practices_daemon:latest

ENV DIR="/var/daemon" \
    MYSQL_HOST="mysql" \
    MYSQL_USER="bookstore" \
    MYSQL_USER_PASSWORD="12345" \
    MYSQL_REPLICATION_LISTENER="/var/deamon/mysql-replication-listener" \
    REDIS_HOST="redis"

RUN mkdir -p ${DIR}

WORKDIR "${DIR}/src"
ADD . ${DIR}

RUN cd ${DIR} && \
  php /usr/bin/composer install

CMD ["php", "daemon.php"]
