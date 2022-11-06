#!/usr/bin/env bash

set -euo pipefail

function install_php() {
    local PHP_VERSION=$1

    apt install -y \
    "php${PHP_VERSION}-cli" \
    "php${PHP_VERSION}-curl" \
    "php${PHP_VERSION}-bcmath" \
    "php${PHP_VERSION}-fpm" \
    "php${PHP_VERSION}-amqp" \
    "php${PHP_VERSION}-apcu" \
    "php${PHP_VERSION}-memcached" \
    "php${PHP_VERSION}-ldap" \
    "php${PHP_VERSION}-bz2" \
    "php${PHP_VERSION}-mbstring" \
    "php${PHP_VERSION}-imagick" \
    "php${PHP_VERSION}-gd" \
    "php${PHP_VERSION}-imap" \
    "php${PHP_VERSION}-intl" \
    "php${PHP_VERSION}-mysql" \
    "php${PHP_VERSION}-pgsql" \
    "php${PHP_VERSION}-redis" \
    "php${PHP_VERSION}-gmp" \
    "php${PHP_VERSION}-mongodb" \
    "php${PHP_VERSION}-xdebug" \
    "php${PHP_VERSION}-xmlrpc" \
    "php${PHP_VERSION}-soap" \
    "php${PHP_VERSION}-xsl" \
    "php${PHP_VERSION}-zip"
}


apt update && apt upgrade

apt install -y \
    git \
    jq \
    curl \
    sudo \
    software-properties-common \
    supervisor \
    htop \
    unzip \
    zip

add-apt-repository ppa:ondrej/php

apt update

# TODO:
# install_php "7.2"
install_php "7.4"
# install_php "8.0"
# install_php "8.1"
