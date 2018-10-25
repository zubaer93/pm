FROM ubuntu:xenial

# Install packages and PHP 7
RUN \
  apt-get update -q && \
  apt-get install -y -u software-properties-common python-software-properties && \
  LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php && \
  apt-get update -q && apt-get install -qqy \
  git-core \
  composer \ 
  #Installing mod-php installs both recommended PHP 7 and Apache2
  apache2 \
  php7.2 \
  php-mcrypt \
  php-gd \
  php-intl \
  php-mbstring \
  php-zip \
  php-xml \
  php-codesniffer \
  php-mysql \
  php-curl \
  vim && \
  # Delete all the apt list files since they're big and get stale quickly
  rm -rf /var/lib/apt/lists/*

# Add apache config to enable .htaccess and do some stuff you want
COPY docker/apache /etc/apache2/sites-available/000-default.conf

# Enable mod rewrite and listen to localhost
RUN a2enmod rewrite

################################################################
# Example, deploy a default CakePHP 3 installation from source #
################################################################

# Clone your application (cloning CakePHP 3 / app instead of composer create project to demonstrate application deployment example)
RUN rm -rf /var/www/html

COPY . /var/www/html

# Set workdir (no more cd from now)
WORKDIR /var/www/html

# Composer install application
RUN composer -n update

# # Copy the app.php file
# RUN cp config/app.default.php config/app.php && \
#   # Inject some non random salt for this example 
#   sed -i -e "s/__SALT__/somerandomsalt/" config/app.php && \
#   # Make sessionhandler configurable via environment
#   sed -i -e "s/'php',/env('SESSION_DEFAULTS', 'php'),/" config/app.php  && \
#   # Set write permissions for webserver
#   chgrp -R www-data logs tmp && \
#   chmod -R g+rw logs tmp 

####################################################
# Expose port and run Apache webserver             #
####################################################

EXPOSE 80
CMD ["/usr/sbin/apache2ctl", "-DFOREGROUND"]