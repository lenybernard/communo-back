FROM php:8.0-fpm

# Install extensions
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/572050a69b646d596b93b421abdff4426a4a679b/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions
RUN install-php-extensions intl pdo_pgsql bcmath gd zip redis opcache

# Install composer
# hadolint ignore=DL3008,DL3009
RUN apt-get update && apt-get install --yes --no-install-recommends git unzip
# hadolint ignore=DL3022
COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer

#install chromium for panther
RUN apt-get update && apt-get install -y libzip-dev zlib1g-dev chromium
ENV PANTHER_NO_SANDBOX 1
ENV PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage'

# ---------------------------------
# xdebug
# https://dev.to/brpaz/docker-phpstorm-and-xdebug-the-definitive-guide-14og
ARG XDEBUG_ENABLED=false
ARG XDEBUG_MODE=debug
ADD xdebug-switch /usr/local/bin/xdebug-switch
RUN chmod 0500 /usr/local/bin/xdebug-switch

RUN pecl install xdebug; \
    docker-php-ext-enable xdebug; \
    echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.mode=${XDEBUG_MODE}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    xdebug-switch off;

RUN if [ $XDEBUG_ENABLED = "true" ] ; then \
    xdebug-switch on; \
    fi ;

# Configure permissions
ARG UID=1000
RUN useradd --uid $UID --create-home user
USER user

WORKDIR /app
