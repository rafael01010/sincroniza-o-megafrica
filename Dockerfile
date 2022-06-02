FROM php:7.4.1-apache
# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive
USER root

WORKDIR /var/www/html
RUN apt-get update
RUN apt -y install curl
RUN apt -y install nano
RUN apt -y install wget


# install pre requisites
RUN apt-get update && \
      apt-get -y install sudo
RUN apt-get update && apt-get install -y gnupg
#RUN sudo su
ENV ACCEPT_EULA=Y


# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install apt-utils libxml2-dev gnupg apt-transport-https \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
# Install MS ODBC Driver for SQL Server
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && apt-get -y --no-install-recommends install msodbcsql17 unixodbc-dev \
    && pecl install sqlsrv \
    && pecl install pdo_sqlsrv \
    && echo "extension=pdo_sqlsrv.so" >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/30-pdo_sqlsrv.ini \
    && echo "extension=sqlsrv.so" >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/30-sqlsrv.ini \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install required extensions
RUN docker-php-ext-install intl mysqli pdo pdo_mysql



RUN curl https://packages.microsoft.com/keys/microsoft.asc | sudo apt-key add -
RUN curl https://packages.microsoft.com/config/ubuntu/20.04/mssql-server-2019.list | sudo tee /etc/apt/sources.list.d/mssql-server.list
RUN sudo apt update
#RUN apt-get update &&  ACCEPT_EULA=Y apt-get -y install mssql-server
RUN curl https://packages.microsoft.com/config/ubuntu/21.04/prod.list | sudo tee /etc/apt/sources.list.d/msprod.list
RUN apt-get update && ACCEPT_EULA=Y apt-get install -y mssql-tools 
RUN sudo ln -s /opt/mssql-tools/bin/sqlcmd /usr/bin/

# install openssl-1.1.1
RUN wget https://www.openssl.org/source/openssl-1.1.1.tar.gz --no-check-certificate
RUN tar -zxf openssl-1.1.1.tar.gz && cd openssl-1.1.1
WORKDIR /var/www/html/openssl-1.1.1
RUN ./config
RUN sudo apt install make gcc
RUN sudo mv /usr/bin/openssl ~/tmp
RUN make install_sw
RUN sudo ln -s /usr/local/bin/openssl /usr/bin/openssl
RUN sudo ldconfig
WORKDIR /var/www/html

#copy All COntent for html folder
COPY . /var/www/html

# Install crontab
RUN apt-get update && apt-get -y install cron
# Copy hello-cron file to the cron.d directory
COPY ./cron/hello-cron /etc/cron.d/hello-cron
# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/hello-cron
# Apply cron job
RUN crontab /etc/cron.d/hello-cron

RUN apt update && apt install -y \
        nodejs \
        npm \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        zip \
        curl \
        unzip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-source delete

#Settings APACHE2
#COPY vhost.conf /etc/apache2/sites-available/000-default.conf
#Intall AND active Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chown -R www-data:www-data /var/www/html && a2enmod rewrite
#COPY php.ini /usr/local/etc/php/

# OPEN PORTS
EXPOSE 80/tcp
EXPOSE 80/udp