FROM ubuntu:21.10

RUN apt-get update

RUN DEBIAN_FRONTEND=noninteractive TZ=Etc/UTC apt-get -y install tzdata
RUN apt-get install -y sudo curl git-core \
    zsh wget fonts-powerline locales \
    ca-certificates gnupg mysql-client \
    lsb-release software-properties-common

RUN locale-gen en_US.UTF-8

RUN curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

RUN echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

RUN apt-get update && apt-get install -y docker-ce-cli

RUN curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
RUN chmod +x /usr/local/bin/docker-compose

#Install PHP8.1
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get install -y php8.0
RUN apt-get install -y php8.0-common
RUN apt-get install -y php8.0-mysql
RUN apt-get install -y php8.0-xml
RUN apt-get install -y php8.0-xmlrpc
RUN apt-get install -y php8.0-curl
RUN apt-get install -y php8.0-gd
RUN apt-get install -y php8.0-imagick
RUN apt-get install -y php8.0-cli
RUN apt-get install -y php8.0-dev
RUN apt-get install -y php8.0-imap
RUN apt-get install -y php8.0-mbstring
RUN apt-get install -y php8.0-opcache
RUN apt-get install -y php8.0-soap
RUN apt-get install -y php8.0-zip
RUN apt-get install -y php8.0-redis
RUN apt-get install -y php8.0-intl

# Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php

RUN git clone --depth=1 https://github.com/romkatv/powerlevel10k.git ~/powerlevel10k
RUN echo 'source ~/powerlevel10k/powerlevel10k.zsh-theme' >>~/.zshrc
COPY /.p10k.zsh /root/.p10k.zsh
COPY /.zshrc /root/.zshrc