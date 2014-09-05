<?php
//import CommonConfig & CommonFunction
require_once('config.php');
require_once('functions.php');
$p=$_GET['p'];
switch ($p){
	case "today":
		require_once('selectTaskToday.php');
		break;
	case "week":
		require_once('selectTaskWeek.php');
		break;
	case "done":
		require_once('selectTaskDone.php');
		break;
	default :
		require_once('selectTaskAll.php');
}
require_once('template.php');
?>