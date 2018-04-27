#!/bin/bash

docker-compose rm -fsv && \
	docker-compose build --no-cache && \
	docker-compose up
