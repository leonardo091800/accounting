#! /bin/bash

git reset --hard
git pull

#create psw for mysql user
user="accountingAdmin"
psw="$(openssl rand -base64 12)"
db="accounting_db"
# if it is an update then:
mysql -e "ALTER USER $user@localhost IDENTIFIED BY '$psw';"
# grants access to db for new user:
mysql -e "GRANT ALL PRIVILEGES ON $db.* TO '$user'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

#update psw for mysql user in db::main
sed -i "s/username='.*'/username='$user'/" db/main.php
sed -i "s/password='.*'/password='$psw'/" db/main.php

#replacing old files with new ones
rm /var/www/html/accounting/*
rsync -r ./* /var/www/html/accounting
chown -R www-data:www-data /var/www/html

sudo systemctl restart apache2 

echo "finished installation, please visit: http://localhost/accounting"

