<?php
//アプリ共通設定ファイル

//DB設定
define('DSN','mysql:host=localhost;dbname=todo_app');
define('DB_USER','admindb');
define('DB_PASSWORD','password');

//エラー制御
//基本的に全てエラーログは出すが、通知レベルのメッセージは除外する。
error_reporting(E_ALL&~E_NOTICE);