# accounting For Moms
## Production website: *https://accountingformoms.com
An easy program in PHP and MYSQL that can help my mum with her accounting stuff.

In future maybe I'll add the possibility to have multiple users, but I need to figure out how to hide/obfuscate sensitive data

You can check a testing webpage of this code here:
* https://accountingformoms.com 


## How to install:
It can work even locally in any pc

### How to install in Windows:
(not too sure but should be:)
- install the XAMPP app 
- get the zip file of this project and extract it in C:\wherever\you\want
- change the root path in the z.scripts/root.php file and replace $root=c:\wherever\you\want\
- change the root path in the z.scripts/root.php file and replace $root_HTML=c:\wherever\you\want\ 

### How to install In linux:
Install minimum dependencies (curl, sudo, git)
```
# apt install sudo curl
```

create user accounting:
```
# adduser accounting
# usermod -aG sudo accounting
```
```
login with new user
# su accounting
```

download git:
```
$ curl -fsSL https://cli.github.com/packages/githubcli-archive-keyring.gpg | sudo dd of=/usr/share/keyrings/githubcli-archive-keyring.gpg && sudo chmod go+r /usr/share/keyrings/githubcli-archive-keyring.gpg && echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/githubcli-archive-keyring.gpg] https://cli.github.com/packages stable main" | sudo tee /etc/apt/sources.list.d/github-cli.list > /dev/null && sudo apt update && sudo apt install gh -y
```

and download the software:
```
$ cd /tmp/
$ sudo git clone https://github.com/leonardo091800/accounting.git
$ cd accounting
$ sudo chmod +x INSTALL.sh
$ sudo ./INSTALL.sh
$ sudo chown -R www-data:www-data /var/www/html/accounting
```

and it's done



## to update:
```
$ cd /tmp/accounting
$ sudo ./UPDATE.sh
```


## to completely uninstall:
Be careful it will erase all your data:
```
$ sudo rm -rf /var/www/html/*
$ sudo rm -rf /tmp/accounting
$ sudo mysql
> drop database accounting_db;
> exit
```
