<?php

//import CommonConfig & CommonFunction
require_once('config.php');
require_once('functions.php');

//Connect DB
$dbh=connectDb();

//Create SQLStatement
$sql="update tasks set type=if(type='done','notyet','done'),modified=now() where id=:id";

$stmt=$dbh->prepare($sql);
$stmt->execute(array(":id"=>(int)$_POST['id']));