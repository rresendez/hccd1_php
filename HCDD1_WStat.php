<html>
    <head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>HCDD No. 1 Database - Work Status</title>
	</head>
	<body>


<h2>
HCDD No. 1 - Work Status
</h2>




	<?php
	$nDay = date('Y-m-d');
	//$nDay = date('2017-04-07');
	include "connection.php";
  include 'rigo.html';

	//Search all db
	$strSQL = "SELECT *, TIMEDIFF (TimeOut, TimeIn) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1SWT.TimeDate='{$nDay}' AND HCDD1SWT.TimeOut IS NULL ORDER BY id DESC";


	if (isset($_POST['filter1'])) {
		$select_user = $_POST['selectus1'];
		$strSQL = "SELECT *, TIMEDIFF (TimeOut, TimeIn) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1SWT.TimeDate='{$nDay}' AND HCDD1SWT.TimeOut IS NULL AND HCDD1User.User_ID LIKE '{$select_user}' ORDER BY id DESC";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['filter2'])) {
		$select_user = $_POST['selectus1'];
		$strSQL = "SELECT *, TIMEDIFF (TimeOut, TimeIn) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1SWT.TimeDate='{$nDay}' AND HCDD1User.User_ID LIKE '{$select_user}' Order BY HCDD1SWT.User_ID";
		$rs = mysql_query($strSQL, $con);
	};


	if (isset($_POST['Reset'])) {
		$strSQL = "SELECT *, TIMEDIFF (TimeOut, TimeIn) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1SWT.TimeDate='{$nDay}' AND HCDD1SWT.TimeOut IS NULL ORDER BY id DESC";
		$rs = mysql_query($strSQL, $con);
	};


	// Pull Down for Dept
	echo "<form action=HCDD1_WStat.php method=post>";


	//Select User and add to Pull Down for Filter
	$strSQLp1 = "SELECT User_ID, Concat (FirstName, ' ', LastName, ' - ', User_ID) AS UserName FROM HCDD1User";
	$rsp1 = mysql_query($strSQLp1, $con);
	echo "<select name='selectus1' width='150' style='width: 150px'>";
	echo "<option value='%'>{User} </option>";
	while ($row = mysql_fetch_array($rsp1)){
	echo "<option value='" . $row['User_ID']."'>".$row['UserName']."</option>";
	};
	echo "</select>";


	echo "<input type=submit name=filter1 value='Filter'>";
	echo "<input type=submit name=filter2 value='All Day'>";
	echo "<input type='submit' name='Reset' value='Reset'></p>";

	echo "</form>";


	//Result Table
	$rs = mysql_query($strSQL, $con);

	echo "User={$select_user}";
	echo " , Date={$nDay}";


	//Table Columns
	echo "<table border=1 bgcolor=#CCCCCC>
	<tr bgcolor=#CCCCCC>
	<th>Stamp ID</th>
	<th>User ID</th>
	<th>User FN</th>
	<th>User LN</th>
	<th>Proj ID</th>
	<th>Proj No</th>
	<th>Owner</th>
	<th>Pct</th>
	<th>Proj Name</th>
	<th>PDesc</th>
	<th>Date</th>
	<th>Time In</th>
	<th>Time Out</th>
	<th>Time(s)</th>
	</tr>";



	// Loop the recordset $rs  // Each row will be made into an array ($row) using mysql_fetch_array
	while($row = mysql_fetch_array($rs)){
	echo "<form action=HCDD1_WStat.php method=post>";
	echo "<tr>";
	echo "<td>" . $row['id'] ."</td>";
	echo "<td>" . $row['User_ID'] ."</td>";
	echo "<td>" . $row['FirstName'] ."</td>";
	echo "<td>" . $row['LastName'] ."</td>";
	echo "<td>" . $row['Proj_ID'] ."</td>";
	echo "<td>" . $row['ProjNo'] ."</td>";
	echo "<td>" . $row['Owner'] ."</td>";
	echo "<td>" . $row['Pct'] ."</td>";
	echo "<td>" . $row['ProjOfficial'] ."</td>";
	echo "<td>" . $row['PDesc'] ."</td>";
	echo "<td>" . $row['TimeDate'] ."</td>";
	echo "<td>" . $row['TimeIn'] ."</td>";
	echo "<td>" . $row['TimeOut'] ."</td>";
	echo "<td>" . $row['TotalTime'] ."</td>";
	//echo "<td>" . "<input type=hidden name=hidden value=" . $row['id'] ."> </td>";
	//echo "<td>" . "<input type=submit name=update value=Update" . "> </td>";
	//echo "<td>" . "<input type=submit name=delete value=Del." . "> </td>";
	echo "</tr>";
	echo "</form>";
	}


	// add to db Last row
	/*echo "<form action=HCDD1_WStat.php method=post>";
	echo "<tr>";
	echo "<td bgcolor=#CCCCCC><input type=hidden name=uUser_ID></td>";
	echo "<td><input type=text name=ufirstname></td>";
	echo "<td><input type=text name=ulastname></td>";
	echo "<td><input type=text name=udept></td>";
	echo "<td><input type=text name=urank></td>";
	echo "<td><input type=hidden name=uhidden></td>";
	echo "<td>" . "<input type=submit name=add value=New" . " </td>";
	echo "</tr>";
	echo "</form>";

	*/
	echo "</table>";



	// Close the database connection
	mysql_close($con);
	?>
	</body>
	</html>
