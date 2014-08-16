<?php
//アプリ共通関数

//DB接続設定
function connectDb(){
	try{
		return new PDO(DSN,DB_USER,DB_PASSWORD);
	} catch(PDOException $e){
		echo $e->getMessage();
		exit;
	}
}

//エスケープ関数
function h($s){
	return htmlspecialchars($s,ENT_QUOTES,"UTF-8");
}