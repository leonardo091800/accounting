# accounting For Moms
## Production website: *https://accountingformoms.com
An easy program in PHP and MYSQL that can help my mum with her accounting stuff. It now supports multiple users!

You can check a testing webpage of this code here:
* https://accountingformoms.com 


## How to install:
It can work even locally in any pc

### How to install in Windows:
(not too sure but should be:)
- install the XAMPP app 
- get the zip file of this project and extract it in C:\wherever\you\installed\XAMPP

### How to install In linux (e.g. Debian/Ubuntu):
Install minimum dependencies (curl, sudo, git)
```
# apt install git nginx php php-fpm -y
```

Start nginx (or apache2)
```
$ sudo vim /etc/nginx/conf.d/default
        root /var/www/html/;
        index index.html index.htm index.nginx-debian.html index.php;
        server_name _;
        location / {
                try_files $uri $uri/ =404;
        }
        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php-fpm.sock;
        }
$ sudo systemctl enable --now nginx
```

and download the php code to the right folder:
```
$ git clone https://github.com/leonardo091800/accounting.git
$ sudo ln -s accounting /var/www/html/
```

and it's done



## to completely uninstall:
Be careful it will erase all your data:
```
$ sudo rm -rf /var/www/html/*
$ sudo rm -rf /opt/accounting
$ sudo mysql
> drop database accounting_db;
> exit
```
