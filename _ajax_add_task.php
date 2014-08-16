<?php

//import CommonConfig & CommonFunction
require_once('config.php');
require_once('functions.php');

//Connect DB
$dbh=connectDb();

//Create SQLStatement(CheckSeqMax)
$sql="select max(seq)+1 from tasks where type !='deleted'";
$seq=$dbh->query($sql)->fetchColumn();

//Create SQLStatement(addTask)
$sql="insert into tasks (seq,title,created,modified) values(:seq,:title,now(),now())";
$stmt=$dbh->prepare($sql);
$stmt->execute(array(
	":seq"=>$seq,
	":title"=>$_POST['title']
	));

echo $dbh->lastInsertId();