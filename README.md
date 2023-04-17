# accounting
accounting made easy

An easy program in PHP and MYSQL that can help my mum with her accounting stuff.

In future maybe I'll add the possibility to have multiple users, but I need to figure out how to hide/obfuscate sensitive data

## How to install:
It can work even locally in any pc, just install the XAMPP app and change the root path in the z.scripts/root.php file
In linux:
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
```

and it's done



## to uninstall:
```
$ sudo rm -rf /var/www/html/*
```


## to update:
```
$ sudo rm -rf /var/www/html/*
$ cd /tmp/accounting
$ sudo git pull
$ sudo ./INSTALL.sh
```
