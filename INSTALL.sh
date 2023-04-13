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
#change the root and Root_HTML to production:
sed -i "s/root='\/var\/www\/html\/accounting\/;'/root='\/var\/www\/html\/';/" z.scripts/root.php
sed -i "s/root_HTML='\/accounting\/';/root_HTML='\/';/" z.scripts/root.php

#copying files to /var/www/html and removing the default index
rm /var/www/html/index.html
rsync -r ./* /var/www/html/
chown -R www-data:www-data /var/www/html

sudo systemctl restart apache2 

echo "finished installation, please visit: http://localhost"

