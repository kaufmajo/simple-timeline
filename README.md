# simple-timeline

## PHP

Version: 8.3

## Database

```shell
$ ./script/sql/database
$ ./script/sql/trigger
$ ./script/sql/proc
```

## File-Persmission on Dev-Server

https://stackoverflow.com/questions/30639174/how-to-set-up-file-permissions-for-laravel

## Password

```php
<?php
/**
 * We just want to hash our password using the current DEFAULT algorithm.
 * This is presently BCRYPT, and will produce a 60 character result.
 *
 * Beware that DEFAULT may change over time, so you would want to prepare
 * By allowing your storage to expand past 60 characters (255 would be good)
 */
$mypassword = password_hash("rasmuslerdorf", PASSWORD_DEFAULT);
?>
```

```sql
INSERT INTO `tajo1_user` (`user_id`, `user_name`, `user_email`, `user_password`, `user_role`) VALUES (2, 'admin', 'user@mail.com', 'mypassword', 'admin');
```

## Setup and Installation

### Install Apache Webserver

```shell 
$ apt-get install apache2
```

### Activate Apache Rewrite Module

```shell
$ a2enmod rewrite
```

### Install PHP

```shell 
$ apt-get install php libapache2-mod-php
```

### Install PHP Modules for Apache

```shell
$ apt-get install php8.3-mysql
$ apt-get install php8.3-gd
$ apt-get install php8.3-intl
$ apt-get install php8.3-tidy
$ apt-get install php8.3-xml
$ apt-get install php8.3-xdebug # only required on dev server

```

### Dev WSL Configuration

#### Don't forget to change listen port on WSL

If dev machine cant listen on port 80, just use another port:

In my case, I use ports from this scope 888x

```shell
$ vi /etc/apache2/ports.conf 
```

### Install composer on Dev

See instructions on https://getcomposer.org/

To update composer:

```shell
$ php composer.phar self-update
```

To update the application:

```shell
$ php composer.phar self-update
$ php composer.phar update
```

### VHOSTS Example

```shell
<VirtualHost *:8888>
        # The ServerName directive sets the request scheme, hostname and port that
        # the server uses to identify itself. This is used when creating
        # redirection URLs. In the context of virtual hosts, the ServerName
        # specifies what hostname must appear in the request's Host: header to
        # match this virtual host. For the default virtual host (this file) this
        # value is not decisive as it is used as a last resort host regardless.
        # However, you must set it for any further virtual host explicitly.
        #ServerName jk1.dev.dev

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/jk1/public

        # Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
        # error, crit, alert, emerg.
        # It is also possible to configure the loglevel for particular
        # modules, e.g.
        #LogLevel info ssl:warn

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

        # For most configuration files from conf-available/, which are
        # enabled or disabled at a global level, it is possible to
        # include a line for only one particular virtual host. For example the
        # following line enables the CGI configuration for this host only
        # after it has been globally disabled with "a2disconf".
        #Include conf-available/serve-cgi-bin.conf


        <Directory /var/www/jk1/public>
                DirectoryIndex index.php
                AllowOverride All
                Order allow,deny
                Allow from all
        </Directory>

</VirtualHost>
```

### Apache (de)activate VHOST

Activate

```shell
$ a2ensite 020-web1.conf
```

Deactivate

```shell
$ a2dissite 020-web1.conf 
```

### Activate SSL for website
See instructions on https://letsencrypt.org/de/

### PHP Versions

If you have to switch your php version:

Show installed versions on OS:

```shell
$ sudo update-alternatives --list php
```

Change default php version on OS:

```shell
sudo update-alternatives --config php
```

Change default php version on Apache:

```shell
sudo a2dismod php8.2 
sudo a2enmod php8.3 
sudo service apache2 restart 
```

## Activate German locale on WSL 

Check if local is already installed:

```shell
$ locale -a
```

Show all supported locales:

```shell
$ less /usr/share/i18n/SUPPORTED
```

If local is missing, you have to generate it:

```shell 
$ sudo locale-gen de_CH.UTF-8
```

## Adapt console prompt

```shell 
$ cp ~/.bashrc ~/.bashrc.bak
$ vi ~/.bashrc
```

Example - change line:
```shell 
PS1='debian_10_dev ${debian_chroot: ...
```

## Tcp Udp ports listening 

How to show listening tcp udp ports:

```shell
$ ss -tulw | grep LISTEN
```

## Mariadb Version

How to check mariadb version:

```shell
$ mariadb -V
```

## Secure copy Beispiele (scp)

If it is a shell script, there is normally no need to configure the permission again 

```shell
$ scp -r root@server:/var/www/jk1/data/media/* /var/www/jk1/data/media/
$ scp -r user1@server:/var/www/jk1/data/* /var/www/jk1/data/
$ scp user1@server:/var/www/jk1/config/autoload/production.local.php /var/www/jk1/config/autoload/production.local.php
```

## Set permission for special folders

```shell
$ chown www-data /var/www/jk1/data/cache -R
$ chown www-data /var/www/jk1/data/log -R
$ chown www-data /var/www/jk1/data/media -R
$ chown www-data /var/www/jk1/data/temp -R
```

## Set execution permission for scripts

```shell
$ chmod u+x /var/www/jk1/script/sync/stage_to_provider.sh
```

## AuthUserFile für .htaccess Auth-Configuration

Auth type = "Basic Auth"

```shell 
$ cd /srv/scripts/apache/passwd/basic
```

### Example

soapme:$xxx1$xxxx.xxx$xxx

#### .htaccess example

```shell 
AuthType basic
AuthName "Authentication Required"
AuthBasicProvider file
AuthUserFile /srv/scripts/apache/passwd/basic
Order deny,allow
Deny from all
Satisfy All
Options -Indexes
```

## Install rsync 

```shell 
$ dpkg -s rsync # check if packet is already installed
$ apt-get install rsync
```

## File and Folder permission on webserver for development

Please see follow these notes:

https://stackoverflow.com/questions/30639174/how-to-set-up-file-permissions-for-laravel

```shell
$ sudo chown -R www-data:www-data /path/to/your/laravel/root/directory

$ sudo usermod -a -G www-data user1
```

## Update php.ini

php.ini

```shell
post_max_size = 50M
upload_max_filesize = 50M
# max_execution_time 300
```

or you can set these values within the ".htaccess" file, which is located in the "public" folder:  

```shell
php_value post_max_size 50M
php_value upload_max_filesize 50M
#php_value max_execution_time 300
```

## Why is .gitignore not ignoring my files?

The .gitignore file ensures that files not tracked by Git remain untracked.

Just adding folders/files to a .gitignore file will not untrack them -- they will remain tracked by Git.

To untrack files, it is necessary to remove from the repository the tracked files listed in .gitignore file. Then re-add them and commit your changes.

The easiest, most thorough way to do this is to remove and cache all files in the repository, then add them all back. All folders/files listed in .gitignore file will not be tracked. From the top folder in the repository run the following commands:

```shell
$ git rm -r --cached .
$ git add .
```

Then commit your changes:

```shell
$ git commit -m "Untrack files in .gitignore"
```

## Managing remote repositories

https://docs.github.com/en/get-started/getting-started-with-git/managing-remote-repositories