<?php

//共通設定ファイル、共通関数ファイルを読み込み
require_once('config.php');
require_once('functions.php');

//DBに接続
$dbh=connectDb();

//チェックの更新のためのSQL文を作成
$sql="update tasks set type=if(type='done','notyet','done'),modified=now() where id=:id";

$stmt=$dbh->prepare($sql);
$stmt->execute(array(":id"=>(int)$_POST['id']));