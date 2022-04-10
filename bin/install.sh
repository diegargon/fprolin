#!/bin/bash
##
##  @copyright Copyright @ 2022 - 2023 Diego Garcia
##

APPNAME="fprolin"
CWD=$(pwd)
CWD_ROOT=$(dirname $CWD)

##TODO: Root check

apt install lighttpd php-fpm php-sqlite3 -y

php_version_full=`php -v | awk 'NR==1 {print $2}'`
php_version=${php_version_full%.*}
php_init_path="/etc/php/${php_version}/fpm/php.ini"
##TODO: error if php_init_path not exists

##uncomment cgi.fix_pathinfo in php.ini
sed -i '/cgi.fix_pathinfo/s/^;//' $php_init_path

##www.conf
php_www_conf="/etc/php/${php_version}/fpm/pool.d/www.conf"
##TODO: error if php_www_conf not exists

sed -i -e '/^listen =/ s/= .*/= 127\.0\.0\.1\:9000/' $php_www_conf

chown www-data:www-data ../src/public/data/${APPNAME}.db

systemctl restart php${php_version}-fpm

# Create and copy cert
#openssl req -new -x509 -keyout lighttpd.pem -out lighttpd.pem -days 365 -nodes
cp ../etc/lighttpd.pem /etc/lighttpd
chmod 400 /etc/lighttpd

#lighttpd conf replacing
cp ../etc/10-fastcgi.conf /etc/lighttpd/conf-available
cp ../etc/15-fastcgi-php.conf /etc/lighttpd/conf-available
cp ../etc/10-ssl.conf /etc/lighttpd/conf-available
ln -s /etc/lighttpd/conf-available/10-fastcgi.conf /etc/lighttpd/conf-enabled
ln -s /etc/lighttpd/conf-available/15-fastcgi-php.conf /etc/lighttpd/conf-enabled
ln -s /etc/lighttpd/conf-available/10-ssl.conf /etc/lighttpd/conf-enabled

rm -rf /var/www/html
ln -s ${CWD_ROOT}/src/public /var/www/html 
chown www-data:www-data /var/www/html/data/

systemctl restart lighttpd

#Used in WOSProxy
apt install python3-pip -y
pip install psutil
