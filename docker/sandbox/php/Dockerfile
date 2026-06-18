FROM php:8.3-cli-alpine

RUN adduser -D -u 1000 sandbox
COPY php.ini /usr/local/etc/php/conf.d/zz-sandbox.ini
COPY runner.php /sandbox/runner.php
RUN chmod 0555 /sandbox/runner.php

USER sandbox
WORKDIR /workspace

