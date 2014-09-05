<?php
//import CommonConfig & CommonFunction
require_once('config.php');
require_once('functions.php');

//Connect DB
$dbh=connectDb();

//Create TaskArray
$tasks=array();

//Create SQLStatement(ShowAllTasks)
$sql="SELECT * FROM tasks WHERE type != 'deleted' ORDER BY seq,plan";

foreach($dbh->query($sql) as $row){
	array_push($tasks,$row);
}