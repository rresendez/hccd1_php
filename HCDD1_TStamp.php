<html>
<head>
 <title>HCDD No. 1 - Time Log</title>
 <script>
function startTime() {
    var today = new Date();
    var nDayW = today.getDay();
    nDayW = addzero(nDayW);
    var nMonth = today.getMonth();
    nMonth = addzero(nMonth);
    var nDay = today.getDate();
    nDay = addzero(nDay);
    var nYear = today.getFullYear();
    var h = today.getHours();
    var m = today.getMinutes();
    m = addzero(m);
    var s = today.getSeconds();
    s = addzero(s);
    document.getElementById('datetime').innerHTML =
    nDayW + " - " + nMonth + "/" + nDay + "/" + nYear + " - " + h + ":" + m + ":" + s;
	var t = setTimeout(startTime, 500);
}
function addzero(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
</script>

</head>



<body onload="startTime()" bgcolor="#217725">


 <div id="datetime" style="color:#FFFFFF"></div></p>

<?php

$nDay = date('Y-m-d');
$nTime = date('H:i:s',time()+(2*60*60));


//Variables for connecting to your database.
include "connection.php";
include 'rigo.html';


if(isset($_POST['addts'])){
	$AddTS = "INSERT INTO HCDD1SWT(User_ID, Proj_ID, PDesc, TimeDate, TimeIN) VALUES('" . $_POST[selectus1] ."','". $_POST[selectus2] ."','". $_POST[pdesc] ."','" .$nDay. "','".$nTime."')";
	mysql_query($AddTS, $con);
	echo "<script>window.location = '/HCDD1_TStampw.php?uid=$_POST[selectus1]'</script>";
};


	// Pull Down User
	echo "<form action=HCDD1_TStamp.php method=post>";

	$strSQLp1 = "SELECT User_ID, Concat (FirstName, ' ', LastName) AS UserName FROM HCDD1User WHERE UList='Y'";
	$rsp1 = mysql_query($strSQLp1, $con);
	echo "<select name='selectus1' width='345' style='width: 345px'>";
	echo "<option value='%'> </option>";
	while ($row = mysql_fetch_array($rsp1)){
	echo "<option value='" . $row['User_ID']."'>".$row['UserName']."</option>";
	};
	echo "</select></p>";

	//Select Project from PUll Down for Filter
	$strSQLp2 = "SELECT Proj_id, Concat (Proj_id, ' - ', ProjOfficial, ' - ', ProjNo, ' - ', Owner, ' - ', Pct) AS Projlist FROM HCDD1Prj WHERE PList='Y' ORDER BY Proj_id DESC";
	//$strSQLp2 = "SELECT ProjOfficial FROM HCDD1Prj";
	$rsp2 = mysql_query($strSQLp2, $con);
	echo "<select name='selectus2' width='345' style='width: 345px'>";
	echo "<option value='%'> </option>";
	while ($row = mysql_fetch_array($rsp2)){
	echo "<option value='" . $row['Proj_id']."'>".$row['Projlist']."</option>";
	};
	echo "</select></p>";



	/*
	// Pull Down Proj
	$strSQLp2 = "SELECT Proj_id, Concat (Pct, ' ', ProjOfficial) AS ProjName FROM HCDD1Prj";
	$rsp2 = mysql_query($strSQLp2, $con);
	echo "<select name='selectus2' width='345' style='width: 345px'>";
	echo "<option value='%'> </option>";
	while ($row = mysql_fetch_array($rsp2)){
	echo "<option value='" . $row['Proj_id']."'>".$row['ProjName']."</option>";
	};
	echo "</select></p>";
	*/


	echo "<textarea rows='1' cols='40' name=pdesc value=></textarea></p>";


	echo "<input type=submit name=addts value='Start Time'>";
	echo "</form>";


?>



</body>


</html>
