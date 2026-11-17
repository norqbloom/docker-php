FROM httpd:2.4

RUN sed -i \
    -e 's/^#LoadModule proxy_module /LoadModule proxy_module /' \
    -e 's/^#LoadModule proxy_fcgi_module /LoadModule proxy_fcgi_module /' \
    /usr/local/apache2/conf/httpd.conf \
    && printf '\nInclude conf/extra/php-fpm.conf\n' >> /usr/local/apache2/conf/httpd.conf

COPY docker/apache/php-fpm.conf /usr/local/apache2/conf/extra/php-fpm.conf
COPY public/ /var/www/html/
