#!/bin/sh

yum -y install java-1.8.0-openjdk
yum -y install unzip

wget http://ftp.kddilabs.jp/infosystems/apache/tomcat/tomcat-8/v8.5.32/bin/apache-tomcat-8.5.32.zip
unzip apache-tomcat-8.5.32.zip
mv apache-tomcat-8.5.32 /usr/local/tomcat
useradd -s /sbin/nologin tomcat
chown -R tomcat:tomcat /usr/local/tomcat
chmod 775 /usr/local/tomcat/bin/*.sh

cat << EOF > /etc/systemd/system/tomcat.service
[Unit]
Description=Apache Tomcat 8
After=syslog.target network.target

[Service]
User=tomcat
Group=tomcat
Type=oneshot
PIDFile=/usr/local/tomcat/tomcat.pid
RemainAfterExit=yes

ExecStart=/usr/local/tomcat/bin/startup.sh
ExecStop=/usr/local/tomcat/bin/shutdown.sh
ExecReStart=/usr/local/tomcat/bin/shutdown.sh;/usr/local/tomcat/bin/startup.sh

[Install]
WantedBy=multi-user.target
EOF
chmod 755 /etc/systemd/system/tomcat.service

systemctl start tomcat
systemctl enable tomcat
