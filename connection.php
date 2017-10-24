<?php
	$con = mysql_connect("localhost", "root", "B@ckd00r");
	if (!$con){
	die ("Can not connect: " . mysql_error ());
	}
	mysql_select_db("time", $con);
?>
