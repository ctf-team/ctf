#!/bin/bash

# Set TimeZone
if [ ! -z "$TZ" ]; then
        echo ">> set timezone"
        echo ${TZ} >/etc/timezone && dpkg-reconfigure -f noninteractive tzdata
        sed -i -e 's/;date.timezone =/date.timezone=${TZ}/g' /etc/php/7.0/fpm/php.ini
fi

# Display PHP error's or not
if [[ "$PHP_ERRORS" == "1" ]] ; then
        sed -i -e 's/display_errors = Off/display_errors = On/g' /etc/php/7.0/fpm/php.ini
        #sed -i -e 's/;php_flag[display_errors] = off/php_flag[display_errors] = on/g' /etc/php/7.0/fpm/pool.d/www.conf
fi

# Increase the memory_limit
if [ ! -z "$PHP_MEM_LIMIT" ]; then
        sed -i "s/memory_limit = 128M/memory_limit = ${PHP_MEM_LIMIT}M/g" /etc/php/7.0/fpm/php.ini
fi

# Increase the post_max_size
if [ ! -z "$PHP_POST_MAX_SIZE" ]; then
        sed -i "s/post_max_size = 8M/post_max_size = ${PHP_POST_MAX_SIZE}M/g" /etc/php/7.0/fpm/php.ini
fi

# Increase the upload_max_filesize
if [ ! -z "$PHP_UPLOAD_MAX_FILESIZE" ]; then
        sed -i "s/upload_max_filesize = 2M/upload_max_filesize = ${PHP_UPLOAD_MAX_FILESIZE}M/g" /etc/php/7.0/fpm/php.ini
fi

# Increase the max_file_uploads
if [ ! -z "$PHP_MAX_FILE_UPLOADS" ]; then
        sed -i "s/max_file_uploads = 20/max_file_uploads = ${PHP_MAX_FILE_UPLOADS}/g" /etc/php/7.0/fpm/php.ini
fi

# get python
apt update
apt install python2.7

# exec CMD
echo ">> exec docker CMD"
echo "$@"
exec "$@"