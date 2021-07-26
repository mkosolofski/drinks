FROM node:buster

ARG NODE_ENV
ARG REACT_APP_API_URL

USER root

RUN apt-get update && apt-get install -y curl \
    && apt-get install -y ruby-full rubygems-integration acl

ADD files/ /

RUN chmod 755 /run.sh

RUN chmod 777 -R /var/www

RUN setfacl -R -d -m group:root:rw /var/www

CMD ["/run.sh"]
