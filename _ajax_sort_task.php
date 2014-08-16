<?php

//import CommonConfig & CommonFunction
require_once('config.php');
require_once('functions.php');

//Connect DB
$dbh=connectDb();

parse_str($_POST['task']); //$task

foreach($task as $key=>$val){
	$sql="update tasks set seq=:seq where id=:id";
	$stmt =$dbh->prepare($sql);
	$stmt->execute(array(
		":seq"=> $key,
		":id" => $val
		));
}