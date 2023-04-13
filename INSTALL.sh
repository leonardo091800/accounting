#! /bin/bash
#installing dependencies..
apt update
apt install apache2 -y
apt install mariadb-server -y
apt install openssl -y

#create psw for mysql user
user="accountingAdmin"
psw="$(openssl rand -base64 12)"
#db="accounting_db"
mysql -e "CREATE USER $(user)@localhost IDENTIFIED BY '$(psw)';"

#update psw for mysql user in db::main
sed -i "s/username=''/accountingAdmin='$(user)'" db/main.php
sed -i "s/pasword=''/accountingAdmin='$(user)'" db/main.php

echo "finished installation, please visit: http://localhost"

