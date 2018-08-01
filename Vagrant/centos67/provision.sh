#!/bin/sh

yum -y install kernel kernel-devel
yum -y update

setenforce 0
sed -i -e "s/^SELINUX=enforcing$/SELINUX=disabled/g" /etc/selinux/config

timedatectl set-timezone Asia/Tokyo

yum -y install epel-release.noarch
yum -y install http://rpms.famillecollet.com/enterprise/remi-release-7.rpm

yum -y --enablerepo=epel,remi,remi-php72 install httpd httpd-devel
yum -y --enablerepo=epel,remi,remi-php72 install mariadb mariadb-server
yum -y --enablerepo=epel,remi,remi-php72 install php php-devel php-mbstring php-mysql php-pdo php-mcrypt php-xml
yum -y --enablerepo=epel,remi,remi-php72 install phpMyAdmin

cat << EOF > /etc/httpd/conf.d/httpd.additional.conf
<Directory "/var/www/html">
    Options Includes FollowSymLinks
    AllowOverride All
</Directory>

DirectoryIndex index.html index.php
EOF
rm -f /etc/httpd/conf.d/welcome.conf

cat << EOF > /etc/php.d/00-additional.ini
error_reporting = E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED
display_errors = On
display_startup_errors = On
error_log = /var/log/php_errors.log
date.timezone = "Asia/Tokyo"
mbstring.language = Japanese
mbstring.internal_encoding = UTF-8
mbstring.http_input = pass
mbstring.http_output = pass
mbstring.detect_order = UTF-8,SJIS,EUC-JP,ASCII
EOF

cat << EOF > /etc/my.cnf.d/default-character-set.cnf
[server]
character-set-server=utf8mb4

[client]
default-character-set=utf8mb4
EOF

cat << EOF > /etc/httpd/conf.d/phpMyAdmin_additional.conf
<Directory /usr/share/phpMyAdmin/>
    <IfModule mod_authz_core.c>
        # Apache 2.4
        # Require local
        Require all granted
    </IfModule>
    <IfModule !mod_authz_core.c>
        # Apache 2.2
        Order Deny,Allow
        Deny from None
        Allow from All
    </IfModule>
</Directory>
EOF
sed -i -e "s/^\$cfg\['Servers'\]\[\$i\]\['AllowNoPassword'\] = false;$/\$cfg['Servers'][\$i]['AllowNoPassword'] = true;/" /etc/phpMyAdmin/config.inc.php

systemctl start httpd
systemctl enable httpd
systemctl start mariadb
systemctl enable mariadb
