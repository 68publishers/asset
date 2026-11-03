FROM php:8.3-cli-alpine AS php83

CMD ["/bin/sh"]
WORKDIR /var/www/html

RUN apk add --no-cache --update git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apk add --no-cache ${PHPIZE_DEPS} \
    && pecl install pcov \
    && docker-php-ext-enable pcov

CMD tail -f /dev/null

FROM php:8.4-cli-alpine AS php84

CMD ["/bin/sh"]
WORKDIR /var/www/html

RUN apk add --no-cache --update git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apk add --no-cache ${PHPIZE_DEPS} \
    && pecl install pcov \
    && docker-php-ext-enable pcov

CMD tail -f /dev/null

FROM php:8.5-cli-alpine AS php85

CMD ["/bin/sh"]
WORKDIR /var/www/html

RUN apk add --no-cache --update git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apk add --no-cache ${PHPIZE_DEPS} \
    && pecl install pcov \
    && docker-php-ext-enable pcov

CMD tail -f /dev/null
