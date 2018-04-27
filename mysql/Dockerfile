# MySQL

FROM mariadb:latest

ENV MYSQL_ROOT_PASSWORD 12345
ENV MYSQL_USER bookstore
ENV MYSQL_PASSWORD 12345
ENV MYSQL_DATABASE bookstore

COPY ./bookstore.cnf /etc/mysql/conf.d
COPY ./01_init.sql /docker-entrypoint-initdb.d
COPY ./02_data.sql /docker-entrypoint-initdb.d

ENTRYPOINT ["/docker-entrypoint.sh"]

EXPOSE 3306:3306

CMD ["mysqld"]