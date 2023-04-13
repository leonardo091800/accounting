#! /bin/bash
#installing dependencies..
apt update
apt install apache2 apache2-bin apache2-utils -y
apt install mariadb-server -y
apt install openssl unzip -y
apt install php php-cli php-mbstring php-curl php-xml php-zip -y
apt install libapache2-mod-php php-mysql -y

# secure mysql installation
  # Kill the anonymous users
  mysql -e "DROP USER ''@'localhost'"
  # Because our hostname varies we'll use some Bash magic here.
  mysql -e "DROP USER ''@'$(hostname)'"
  # Kill off the demo database
  mysql -e "DROP DATABASE test"
  # Make our changes take effect
  mysql -e "FLUSH PRIVILEGES"

#create psw for mysql user
user="accountingAdmin"
psw="$(openssl rand -base64 12)"
#db="accounting_db"
mysql -e "CREATE USER $user@localhost IDENTIFIED BY '$psw';"

#update psw for mysql user in db::main
sed -i "s/username=''/username='$user'/" db/main.php
sed -i "s/password=''/password='$psw'/" db/main.php

echo "finished installation, please visit: http://localhost"
echo "mysql psw: $psw"

