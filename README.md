# simple-timeline

## PHP

Version: 8.2

## Database

Stucture:
```shell
$ ./script/sql/database
```

Triggers:
```shell
$ ./script/sql/trigger
```

Procedure:
```shell
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
