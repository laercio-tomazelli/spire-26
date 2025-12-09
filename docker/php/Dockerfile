# =============================================================================
# SPIRE 26 - PHP-FPM on Oracle Linux 9 (identical to OCI production)
# =============================================================================
FROM oraclelinux:9-slim

LABEL maintainer="SPIRE Development Team"
LABEL description="PHP 8.4 FPM on Oracle Linux 9 for SPIRE 26"

# -----------------------------------------------------------------------------
# Environment Variables
# -----------------------------------------------------------------------------
ENV PHP_VERSION=8.4
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/tmp/composer

# -----------------------------------------------------------------------------
# Install EPEL and Remi repository for PHP 8.4
# -----------------------------------------------------------------------------
RUN microdnf install -y \
    oracle-epel-release-el9 \
    dnf \
    && dnf install -y \
    https://rpms.remirepo.net/enterprise/remi-release-9.rpm \
    && dnf module reset php -y \
    && dnf module enable php:remi-${PHP_VERSION} -y

# -----------------------------------------------------------------------------
# Install PHP and required extensions
# -----------------------------------------------------------------------------
RUN dnf install -y \
    php-fpm \
    php-cli \
    php-common \
    php-mysqlnd \
    php-pdo \
    php-mbstring \
    php-xml \
    php-curl \
    php-zip \
    php-gd \
    php-intl \
    php-bcmath \
    php-opcache \
    php-redis \
    php-sodium \
    php-fileinfo \
    php-tokenizer \
    php-dom \
    # Utilities
    git \
    unzip \
    curl \
    supervisor \
    procps-ng \
    && dnf clean all \
    && rm -rf /var/cache/dnf

# -----------------------------------------------------------------------------
# Install Composer
# -----------------------------------------------------------------------------
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

# -----------------------------------------------------------------------------
# Configure PHP-FPM
# -----------------------------------------------------------------------------
RUN sed -i 's/listen = \/run\/php-fpm\/www.sock/listen = 9000/' /etc/php-fpm.d/www.conf \
    && sed -i 's/^listen.allowed_clients/;listen.allowed_clients/' /etc/php-fpm.d/www.conf \
    && sed -i 's/user = apache/user = www-data/' /etc/php-fpm.d/www.conf \
    && sed -i 's/group = apache/group = www-data/' /etc/php-fpm.d/www.conf \
    && sed -i 's/;clear_env = no/clear_env = no/' /etc/php-fpm.d/www.conf

# -----------------------------------------------------------------------------
# Configure PHP
# -----------------------------------------------------------------------------
RUN echo "upload_max_filesize = 64M" >> /etc/php.d/99-spire.ini \
    && echo "post_max_size = 64M" >> /etc/php.d/99-spire.ini \
    && echo "memory_limit = 256M" >> /etc/php.d/99-spire.ini \
    && echo "max_execution_time = 120" >> /etc/php.d/99-spire.ini \
    && echo "opcache.enable = 1" >> /etc/php.d/99-spire.ini \
    && echo "opcache.memory_consumption = 128" >> /etc/php.d/99-spire.ini \
    && echo "opcache.validate_timestamps = 1" >> /etc/php.d/99-spire.ini

# -----------------------------------------------------------------------------
# Create www-data user (consistent with nginx)
# -----------------------------------------------------------------------------
RUN groupadd -g 1000 www-data \
    && useradd -u 1000 -g www-data -m -s /bin/bash www-data

# -----------------------------------------------------------------------------
# Create required directories
# -----------------------------------------------------------------------------
RUN mkdir -p /var/run/php-fpm \
    && mkdir -p /var/log/php-fpm \
    && chown -R www-data:www-data /var/run/php-fpm \
    && chown -R www-data:www-data /var/log/php-fpm

# -----------------------------------------------------------------------------
# Copy Supervisor configuration
# -----------------------------------------------------------------------------
COPY supervisord.conf /etc/supervisord.d/laravel.ini
RUN mkdir -p /var/log/supervisor

# -----------------------------------------------------------------------------
# Working directory
# -----------------------------------------------------------------------------
WORKDIR /var/www/html

# -----------------------------------------------------------------------------
# Expose PHP-FPM port
# -----------------------------------------------------------------------------
EXPOSE 9000

# -----------------------------------------------------------------------------
# Start Supervisor (manages PHP-FPM + Queue Workers + Scheduler)
# -----------------------------------------------------------------------------
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
