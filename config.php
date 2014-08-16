<?php
//CommonConfiguration

//DB Connect Parameter
define('DSN','mysql:host=localhost;dbname=todo_app');
define('DB_USER','admindb');
define('DB_PASSWORD','password');

//ErrorReport Control
//AllErrorlog Output , Except Notice
error_reporting(E_ALL&~E_NOTICE);