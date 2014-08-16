<?php

//アプリ共通設定ファイル、共通関数ファイル
require_once('config.php');
require_once('functions.php');

//DBに接続
$dbh=connectDb();

//var_dump($_POST['task']);

parse_str($_POST['task']); //$task

//var_dump($task);

foreach($task as $key=>$val){
	$sql="update tasks set seq=:seq where id=:id";
	$stmt =$dbh->prepare($sql);
	$stmt->execute(array(
		":seq"=> $key,
		":id" => $val
		));
}