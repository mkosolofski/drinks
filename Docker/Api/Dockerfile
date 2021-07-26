FROM php:7.4.21-fpm

USER root

# Dependencies needed
RUN docker-php-source extract \
    && apt-get update \
    && apt-get -y --no-install-recommends install libcurl3-dev \
        libicu-dev \
        supervisor \
        ack-grep \
        nginx \
        vim \
        curl \
        ruby-full \
        rubygems-integration \
        acl \
        procps \
        git \
        libzip-dev \
        zip \
        unzip \
        wget \
        curl \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mysqli \
        opcache \
        intl \
        zip

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

ADD files/ /

RUN cd /root && wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN chmod 755 /run.sh

RUN chmod 777 -R /var/www

RUN setfacl -R -d -m group:root:rw /var/www

CMD ["/run.sh"]
