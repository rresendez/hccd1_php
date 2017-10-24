<html>
<head>
 <title>HCDD No. 1 - AT LUNCH</title>
 <script>
var locktime = new Date();
function startTime() {
	var now = new Date();
	var distance = now - locktime;
	var counthr = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	counthr = addzero(counthr);
	var countmin = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	countmin = addzero(countmin);
    var countsec = Math.floor((distance % (1000 * 60)) / 1000);
	countsec = addzero(countsec);
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
    nDayW + " - " + nMonth + "/" + nDay + "/" + nYear + " - " + h + ":" + m + ":" + s  + " - - - - - - - - - - " + counthr + ":" + countmin + ":" + countsec;
	var t = setTimeout(startTime, 500);
}


function addzero(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
</script>

</head>



<body onload="startTime()" bgcolor="#FF8623">


 <div id="datetime" style="color:#FFFFFF"></div></p>

<?php

$nDay = date('Y-m-d');
$nTime = date('H:i:s',time()+(2*60*60));


//Variables for connecting to your database.
include "connection.php";
include 'rigo.html';


if(isset($_POST['new'])){
	$nTime = date('H:i:s',time()+(2*60*60));
	$UpdateQuery = "UPDATE HCDD1SWT SET TimeOut='".$nTime."' WHERE id='$_POST[hid]'";
	echo "<script>window.location = '/HCDD1_TStampn.php?uid=$_POST[hUID]'</script>";
	mysql_query($UpdateQuery, $con);
};

if(isset($_POST['addts'])){
	$AddTS = "INSERT INTO HCDD1SWT(User_ID, Proj_ID, PDesc, TimeDate, TimeIN) VALUES('" . $_POST[selectus1] ."','". $_POST[selectus2] ."','". $_POST[pdesc] ."','" .$nDay. "','".$nTime."')";
	mysql_query($AddTS, $con);
	echo "<script>window.location = '/HCDD1_TStampw.php?uid=$_POST[selectus1]'</script>";
};

$wuid = $_GET["uid"];
$wpid = $_GET["pid"];

	// Pull Down User
	echo "<form action=HCDD1_TStampl.php method=post>";

	//$strSQL = "SELECT * FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1SWT.User_ID=$wid ORDER BY id DESC LIMIT 1 ";
	$strSQLp1 = "SELECT User_ID, Concat (FirstName, ' ', LastName) AS UserName FROM HCDD1User WHERE HCDD1User.User_ID=$wuid LIMIT 1";
	$rsp1 = mysql_query($strSQLp1, $con);
	echo "<select name='selectus1' width='345' style='width: 345px'>";
	//echo "<option value='%'> </option>";
	while ($row = mysql_fetch_array($rsp1)){
	echo "<option value='" . $row['User_ID']."'>".$row['UserName']."</option>";
	echo "<input type=hidden name=hUID value=" . $row['User_ID'] .">";
	};
	echo "</select></p>";

	//Select Project from PUll Down for Filter
	$strSQLp2 = "SELECT Proj_id, Concat (Proj_id, ' - ', ProjOfficial, ' - ', ProjNo, ' - ', Owner, ' - ', Pct) AS Projlist FROM HCDD1Prj WHERE HCDD1Prj.Proj_id=$wpid LIMIT 1";
	//$strSQLp2 = "SELECT ProjOfficial FROM HCDD1Prj";
	$rsp2 = mysql_query($strSQLp2, $con);
	echo "<select name='selectus2' width='345' style='width: 345px'>";
	//echo "<option value='%'> </option>";
	while ($row = mysql_fetch_array($rsp2)){
	echo "<option value='" . $row['Proj_id']."'>".$row['Projlist']."</option>";
	};
	echo "</select></p>";



	echo "<textarea rows='1' cols='40' name=pdesc value=></textarea></p>";


	echo "<input type=submit name=addts value='Continue' style='width: 300px;'>";
	echo "<input type=submit name=new value=New>";
	echo "</form>";


?>

<!-- <h1>AT LUNCH</h1>  -->

</body>


</html>
