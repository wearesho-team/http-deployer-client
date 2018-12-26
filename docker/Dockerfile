FROM ubuntu:xenial

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV TZ=Europe/Kiev

WORKDIR /usr/src/site

RUN apt-get update
RUN apt-get install -y software-properties-common locales

RUN locale-gen en_US.UTF-8
ENV LANG en_US.UTF-8

RUN add-apt-repository ppa:ondrej/php && \
    apt-get update && \
    apt-get -y install nodejs build-essential nginx curl git zip php7.2-zip php7.2-bcmath php7.2-soap php7.2-intl php7.2-dom php7.2-simplexml php7.2 php7.2-common php7.2-cli php7.2-fpm php7.2-curl php7.2-gd php7.2-mbstring php7.2-pgsql php7.2-pdo php7.2-imagick

RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
    && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
    && php composer-setup.php -- --install-dir=/usr/bin --filename=composer \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

COPY docker/cli.php.ini /etc/php/7.2/cli/php.ini

COPY composer.json composer.lock /usr/src/site/
RUN composer install --no-ansi --no-dev --no-progress --no-suggest --prefer-dist --optimize-autoloader

COPY . .

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/www.conf /etc/php/7.2/fpm/pool.d/
COPY docker/php.ini /etc/php/7.2/fpm/php.ini

RUN echo "disable_functions = " >> /etc/php/7.2/cli/php.ini \
    && echo "daemon off;" >> /etc/nginx/nginx.conf \
    && rm -f composer.json composer.lock README.md composer-setup.php composer-setup.php.sig .env.example .gitignore \
    && chmod -R 777 /usr/src/site/web/assets

EXPOSE 8080
CMD service php7.2-fpm start && service nginx start
