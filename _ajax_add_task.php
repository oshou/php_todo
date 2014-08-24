<?php

//import CommonConfig & CommonFunction
require_once('config.php');
require_once('functions.php');
require_once('cconsole.php');

//Connect DB
$dbh=connectDb();

//Create SQLStatement(CheckSeqMax)
$seq=0;
$sql="select max(seq)+1 from tasks where type !='deleted'";
$seq=$dbh->query($sql)->fetchColumn();
if (is_null($seq)){
	$seq=0;
}

//Create SQLStatement(addTask)
$sql="insert into tasks (seq,title,plan,created,modified) values(:seq,:title,:plan,now(),now())";
$stmt=$dbh->prepare($sql);
$stmt->execute(array(
	":seq"=>$seq,
	":title"=>$_POST['title'],
	":plan"=>$_POST['plan']
	));

echo $dbh->lastInsertId();