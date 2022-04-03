FROM ubuntu:21.10

WORKDIR /var/www/html/

RUN apt-get update

RUN DEBIAN_FRONTEND=noninteractive TZ=Etc/UTC apt-get -y install tzdata
RUN apt-get install -y sudo curl git-core \
    zsh wget fonts-powerline locales \
    ca-certificates gnupg \
    lsb-release

# # Installing composer
# RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
# RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
# RUN rm -rf composer-setup.php
