FROM ubuntu:22.04

ENV IMAGE_USER=vagrant
ENV HOME=/home/$IMAGE_USER
ENV COMPOSER_HOME=$HOME/.composer

ENV DEBIAN_FRONTEND=noninteractive

WORKDIR /tmp

COPY ./php/scripts/*.sh /tmp/
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN adduser --disabled-password --gecos "" $IMAGE_USER \
  && mkdir -p /run/php \
  && bash ./packages.sh \
  && bash ./nvm.sh \
  && rm -rf ~/.composer/cache/* \
  && chown -R $IMAGE_USER:$IMAGE_USER $HOME \
  && echo "$IMAGE_USER  ALL = ( ALL ) NOPASSWD: ALL" >> /etc/sudoers

COPY ./php/supervisord.conf /etc/supervisor.d/supervisord.conf
COPY ./php/aliases $HOME/.bash_aliases

USER $IMAGE_USER

WORKDIR /home/vagrant

# Override stop signal to stop process gracefully
# https://github.com/php/php-src/blob/17baa87faddc2550def3ae7314236826bc1b1398/sapi/fpm/php-fpm.8.in#L163
STOPSIGNAL SIGQUIT

EXPOSE 9000
CMD ["sudo", "supervisord", "-c", "/etc/supervisor.d/supervisord.conf"]
