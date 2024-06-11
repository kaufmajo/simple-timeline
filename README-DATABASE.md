# MariaDB

## Console Commands

### Install DB-Server on Debian

```shell
$ apt install mariadb-server
$ mariadb-secure-installation
```

### Connect to Database

```shell
mysql --host=localhost --user=root --password='xxxxxxxxxxxx'
mysql --host=127.0.0.1 --user=root --password='xxxxxxxxxxxx'
```

```sql
USE mysql;
```

### Show user permissions:

```sql
SELECT *
FROM user;
```

```sql
SHOW GRANTS FOR 'developer1'@'192.168.1.1';
```

### Create user and set persmissions

You can create a user that has privileges similar to the default root accounts by executing the following:

```sql
CREATE USER 'developer1'@'127.0.0.1' IDENTIFIED BY 'db12db1';

GRANT ALL PRIVILEGES ON *.* to 'developer1'@'127.0.0.1' WITH GRANT OPTION; # WITH GRANTS allows that this user can create further users and give them permissions
```

#### Grant explicit privileges

```sql
GRANT SUPER ON *.* TO 'developer1'@'192.168.1.1';
```

```sql
GRANT ALL PRIVILEGES ON `ksba0`.* TO 'developer1'@'192.168.1.1';
```

```sql
GRANT SELECT, INSERT, UPDATE, DELETE, LOCK TABLES, EXECUTE, SHOW VIEW ON `ksba0`.* TO 'developer1'@'192.168.1.1'; # IDENTIFIED BY 'xxxxxxxxxxxx';
```

### Revoke permissions and drop user:

```sql
REVOKE ALL PRIVILEGES ON `ksba0`.* FROM 'developer1'@'192.168.1.1';
```

```sql
DROP USER 'developer1'@'192.168.1.1';
```

### Set password for user:

```sql
SET PASSWORD FOR 'developer1'@'192.168.1.1' = PASSWORD ('xxxxxxxxxxxx');
FLUSH PRIVILEGES;
```

### Backup:

```sql
CREATE USER 'dumper'@'192.168.1.1' IDENTIFIED BY 'xxxxxxxxxxxx';
GRANT SELECT, SHOW VIEW, RELOAD, REPLICATION CLIENT, EVENT, TRIGGER, EXECUTE ON *.* TO 'dumper'@'192.168.1.1';
FLUSH PRIVILEGES;
```

### Dump Error Messages to File:

Installing the Plugin

```sql
INSTALL SONAME 'sql_errlog';
```

Filter the systemvariabels with 'error' and see what's the name of the written file for error messages  
