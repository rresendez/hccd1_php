<html>
<head>
 <title>HCDD No. 1 - Working</title>
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



<body onload="startTime()" onload="startTime2()" bgcolor="#008EC1">


 <div id="datetime" style="color:#FFFFFF"></div></p>


<?php

//$nDay = date('Y-m-d');
//$nTime = date('H:i:s');

//Variables for connecting to your database.
include "connection.php";
include 'rigo.html';

if(isset($_POST['new'])){
	$nTime = date('H:i:s',time()+(2*60*60));
	$UpdateQuery = "UPDATE HCDD1SWT SET TimeOut='".$nTime."' WHERE id='$_POST[hid]'";
	echo "<script>window.location = '/HCDD1_TStampn.php?uid=$_POST[hUID]'</script>";
	mysql_query($UpdateQuery, $con);
};

if(isset($_POST['break'])){
	$nTime = date('H:i:s',time()+(2*60*60));
	$UpdateQuery = "UPDATE HCDD1SWT SET TimeOut='".$nTime."' WHERE id='$_POST[hid]'";
	echo "<script>window.location = '/HCDD1_TStampb.php?uid=$_POST[hUID]&pid=$_POST[hPID]'</script>";
	mysql_query($UpdateQuery, $con);
};

if(isset($_POST['lunch'])){
	$nTime = date('H:i:s',time()+(2*60*60));
	$UpdateQuery = "UPDATE HCDD1SWT SET TimeOut='".$nTime."' WHERE id='$_POST[hid]'";
	echo "<script>window.location = '/HCDD1_TStampl.php?uid=$_POST[hUID]&pid=$_POST[hPID]'</script>";
	mysql_query($UpdateQuery, $con);
};

$wuid = $_GET["uid"];
//$wpid = $_GET["pid"];
// SQL query
//$strSQL = "SELECT * FROM `HCDD1SWT` WHERE `User_ID`=$wid ORDER BY id DESC LIMIT 1";
$strSQL = "SELECT * FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1SWT.User_ID=$wuid ORDER BY id DESC LIMIT 1 ";
// Execute the query (the recordset $rs contains the result)
$rs = mysql_query($strSQL, $con);

// Loop the record set $rs  // Each row will be made into an array ($row) using mysql_fetch_array
while($row = mysql_fetch_array($rs)){
echo "<table width='345' border=1>";
echo "<form action=HCDD1_TStampw.php method=post>";
echo "<tr align='center'><td><h3>Working On Project</h3></td></tr>";
echo "<tr align='center'><td><i>"."TSID=".$row['id']." UID=".$row['User_ID']." PID=".$row['Proj_ID']."</i></td></tr>";
echo "<tr><td>".$row['FirstName']." ".$row['LastName']."</td></tr>";
echo "<tr><td>".$row['ProjNo'].", ".$row['Owner'].", ".$row['Pct']."</td></tr>";
echo "<tr><td>".$row['ProjOfficial']."</td></tr>";
echo "<tr><td>". $row['PDesc']."</td></tr>";
echo "<tr><td>". $row['TimeDate']."</td></tr>";
echo "<tr><td>". $row['TimeIn']."</td></tr>";
echo "<input type=hidden name=hid value=" . $row['id'] .">";
//echo "<tr><td>" . "<input type=hidden name=hidden value=" . $row['id'] ."> </td></tr>";
echo "<input type=hidden name=hUID value=" . $row['User_ID'] .">";
//echo "<tr><td>" . "<input type=hidden name=hUID value=" . $row['User_ID'] ."> </td></tr>";
echo "<input type=hidden name=hPID value=" . $row['Proj_ID'] .">";
echo "<tr align='center'><td>";
echo "<input type=submit name=new value=New>";
echo "<input type=submit name=break value=Break>";
echo "<input type=submit name=lunch value=Lunch>";
echo "</td></tr>";
echo "</form>";
echo "</table>";
}


// Close the database connection
mysql_close($con);




?>



</body>


</html>
