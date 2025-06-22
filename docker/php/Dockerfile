FROM ubuntu:latest

ARG PHP_LATEST_VERSION
ARG GITHUB_ACCESS_TOKEN

ENV APT_INSTALL_OPTIONS="-y" \
    PHP_VERSION=${PHP_LATEST_VERSION}

RUN apt update && apt install ${APT_INSTALL_OPTIONS} \
    software-properties-common \
    apt-transport-https \
    less \
    bc \
    iputils-ping \
    iproute2 \
    vim \
    curl \
    ncdu \
    lsof \
    wget \
    htop \
    binutils \
    supervisor \
    git \
    php-yaml

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN dpkg -l | grep php | tee packages.txt
RUN add-apt-repository ppa:ondrej/php -y
RUN apt update
RUN apt install ${APT_INSTALL_OPTIONS} \
    php${PHP_VERSION} \
	php${PHP_VERSION}-cli \
	php${PHP_VERSION}-bz2 \
	php${PHP_VERSION}-curl \
	php${PHP_VERSION}-mbstring \
	php${PHP_VERSION}-intl \
	php${PHP_VERSION}-xml \
	php${PHP_VERSION}-zip
RUN apt install ${APT_INSTALL_OPTIONS} \
    php${PHP_VERSION}-fpm

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
	&& php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
	&& php -r "unlink('composer-setup.php');"
RUN composer config --global --auth github-oauth.github.com ${GITHUB_ACCESS_TOKEN}

COPY ./docker/php/root/docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh && mkdir -p /var/www && mkdir -p /run/php

WORKDIR /var/www/src

ENTRYPOINT ["sh", "/docker-entrypoint.sh"]
