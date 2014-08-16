<?php

//共通設定ファイル、共通関数ファイルを読み込み
require_once('config.php');
require_once('functions.php');

//DBに接続
$dbh=connectDb();

//既にあるタスクの状況を'deleted'に更新する。
$sql="update tasks set type='deleted',modified=now() where id=:id";
$stmt=$dbh->prepare($sql);
$stmt->execute(array(":id"=> (int)$_POST['id']));