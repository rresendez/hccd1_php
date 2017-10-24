<html>
    <head>
	<title>HCDD No. 1 Database - Time Stamp Editor</title>
	</head>
	<body>

<h2>
HCDD No. 1 Time Stamp Editor
</h2>




	<?php
	include "connection.php";
    include 'rigo.html';

	//Search all db
	$strSQL = "SELECT *, TIMEDIFF (TimeOut, TimeIn) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id ORDER BY id DESC";
	//$strSQL = "SELECT *, SUM(TIMEDIFF (TimeOut, TimeIn)) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id Group BY HCDD1SWT.Proj_ID";


	if(isset($_POST['QReport'])){
		echo "<script>window.location = '/HCDD1_QReport.php'</script>";
	};

	if (isset($_POST['rank0'])) {
		$select_user = $_POST['selectus1'];
		$select_owner = $_POST['selectowner'];
		$select_pct = $_POST['selectpct'];
		$select_proj = $_POST['selectproj'];
		$strSQL = "SELECT *, TIMEDIFF (TimeOut, TimeIn) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1User.User_ID LIKE '{$select_user}' AND HCDD1Prj.Owner LIKE '{$select_owner}' AND HCDD1Prj.Pct LIKE '{$select_pct}' AND HCDD1Prj.Proj_id LIKE '{$select_proj}' ORDER BY id DESC";
		//$strSQL = "SELECT * FROM HCDD1SWT WHERE Dept LIKE '{$select_user}' AND Rank LIKE '{$select_rank}' ORDER BY User_ID";
		$rs = mysql_query($strSQL, $con);
	};

		if (isset($_POST['filterdate'])) {
		$select_user = $_POST['selectus1'];
		$select_owner = $_POST['selectowner'];
		$select_pct = $_POST['selectpct'];
		$select_proj = $_POST['selectproj'];
		$date_start = $_POST['dateS'];
		$date_end = $_POST['dateE'];
		$strSQL = "SELECT *, TIMEDIFF (TimeOut, TimeIn) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1User.User_ID LIKE '{$select_user}' AND HCDD1Prj.Owner LIKE '{$select_owner}' AND HCDD1Prj.Pct LIKE '{$select_pct}' AND HCDD1Prj.Proj_id LIKE '{$select_proj}' AND HCDD1SWT.TimeDate >= '{$date_start}' AND HCDD1SWT.TimeDate <= '{$date_end}'  ORDER BY id DESC";
		//$strSQL = "SELECT * FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1SWT.TimeDate >= '{$date_start}' AND HCDD1SWT.TimeDate <= '{$date_end}'  ORDER BY id DESC";
		//$strSQL = "SELECT * FROM HCDD1SWT WHERE Rank LIKE '{$search_term}' OR FirstName LIKE '{$search_term}' OR LastName LIKE '{$search_term}' ORDER BY User_ID";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['search'])) {
		$search_term = $_POST['search_box'];
		$strSQL = "SELECT *, TIMEDIFF (TimeOut, TimeIn) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1User.User_ID LIKE '{$search_term}' OR HCDD1User.FirstName LIKE '{$search_term}' OR HCDD1User.LastName LIKE '{$search_term}' OR HCDD1Prj.Proj_ID LIKE '{$search_term}' OR HCDD1Prj.ProjNo LIKE '{$search_term}' OR HCDD1Prj.Owner LIKE '{$search_term}' OR HCDD1Prj.Pct LIKE '{$search_term}' OR HCDD1Prj.ProjOfficial LIKE '{$search_term}' OR HCDD1SWT.id LIKE '{$search_term}' OR HCDD1SWT.PDesc LIKE '{$search_term}' ORDER BY id DESC";
		//$strSQL = "SELECT * FROM HCDD1SWT WHERE Rank LIKE '{$search_term}' OR FirstName LIKE '{$search_term}' OR LastName LIKE '{$search_term}' ORDER BY User_ID";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['Reset'])) {
		$strSQL = "SELECT *, TIMEDIFF (TimeOut, TimeIn) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id ORDER BY id DESC";
		$rs = mysql_query($strSQL, $con);
	};

	if(isset($_POST['update'])){
		$UpdateQuery = "UPDATE HCDD1SWT SET TimeDate='$_POST[TimeDate]', TimeIn='$_POST[TimeIn]', TimeOut='$_POST[TimeOut]' WHERE id='$_POST[hidden]'";
		mysql_query($UpdateQuery, $con);
	};

	if(isset($_POST['delete'])){
		$DeleteQuery = "DELETE FROM HCDD1SWT WHERE id='$_POST[hidden]'";
		mysql_query($DeleteQuery, $con);
	};

	/*if(isset($_POST['add'])){
		$AddQuery = "INSERT INTO HCDD1SWT (FirstName, LastName, Dept, Rank) VALUES ('$_POST[ufirstname]','$_POST[ulastname]','$_POST[udept]','$_POST[urank]')";
		mysql_query($AddQuery, $con);
	};*/

	// Pull Down for Dept
	echo "<form action=HCDD1_TSE.php method=post>";

	echo "<input type='text' name='search_box' value='' />";
	echo "<input type='submit' name='search' value='Search'>";
	echo "<input type=submit name=Reset value=Reset>";

	//Select User and add to Pull Down for Filter
	$strSQLp1 = "SELECT User_ID, Concat (FirstName, ' ', LastName, ' - ', User_ID) AS UserName FROM HCDD1User";
	$rsp1 = mysql_query($strSQLp1, $con);
	echo "<select name='selectus1' width='150' style='width: 150px'>";
	echo "<option value='%'>{User} </option>";
	while ($row = mysql_fetch_array($rsp1)){
	echo "<option value='" . $row['User_ID']."'>".$row['UserName']."</option>";
	};
	echo "</select>";

	//Select Pct to Pull Down for Filter
	echo "<select name='selectowner'>";
	echo "<option value='%'>{Owner} </option>";
	echo "<option value='DD1'>DD1</option>";
	echo "<option value='P1'>P1</option>";
	echo "<option value='P2'>P2</option>";
	echo "<option value='P3'>P3</option>";
	echo "<option value='P4'>P4</option>";
	echo "<option value='Misc'>Misc</option>";
	echo "<option value='DR'>DR</option>";
	echo "</select>";

		//Select Pct to Pull Down for Filter
	echo "<select name='selectpct'>";
	echo "<option value='%'>{Pct} </option>";
	echo "<option value='HCDD1'>HCDD1</option>";
	echo "<option value='1'>1</option>";
	echo "<option value='2'>2</option>";
	echo "<option value='3'>3</option>";
	echo "<option value='4'>4</option>";
	echo "<option value='Misc'>Misc</option>";
	echo "<option value='DR'>DR</option>";
	echo "</select>";


	//Select Project from PUll Down for Filter
	$strSQLp2 = "SELECT Proj_id, Concat (Proj_id, ' - ', ProjOfficial, ' - ', Owner, ' - ', Pct) AS Projlist FROM HCDD1Prj ORDER BY Proj_id DESC";
	//$strSQLp2 = "SELECT ProjOfficial FROM HCDD1Prj";
	$rsp2 = mysql_query($strSQLp2, $con);
	echo "<select name='selectproj'>";
	echo "<option value='%'>{Project} </option>";
	while ($row = mysql_fetch_array($rsp2)){
	echo "<option value='" . $row['Proj_id']."'>".$row['Projlist']."</option>";
	};
	echo "</select>";

	echo "<input type=submit name=rank0 value=Filter>";

	echo "<input type='month' name='dateS' value='' />";
	echo "<input type='month' name='dateE' value='' />";
	echo "<input type='submit' name='filterdate' value='Date Filter'>";
	echo "<input type='submit' name='Reset' value='Reset'>";
	echo "<input type='submit' name='QReport' value='Q Report'>";

	echo "</form>";


	//Result Table
	$rs = mysql_query($strSQL, $con);

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
	<th></th>
	<th></th>
	<th></th>
	</tr>";



	// Loop the recordset $rs  // Each row will be made into an array ($row) using mysql_fetch_array
	while($row = mysql_fetch_array($rs)){
	echo "<form action=HCDD1_TSE.php method=post>";
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
	echo "<td>" . "<input type=text name=TimeDate value=" . $row['TimeDate'] . "> </td>";
	echo "<td>" . "<input type=text name=TimeIn value=" . $row['TimeIn'] ."> </td>";
	echo "<td>" . "<input type=text name=TimeOut value=" . $row['TimeOut'] ."> </td>";
	echo "<td>" . $row['TotalTime'] ."</td>";
	echo "<td>" . "<input type=hidden name=hidden value=" . $row['id'] ."> </td>";
	//echo "<input type=hidden name=hidden value=" . $row['id'] .">";
	echo "<td>" . "<input type=submit name=update value=Update" . "> </td>";
	echo "<td>" . "<input type=submit name=delete value=Del." . "> </td>";
	echo "</tr>";
	echo "</form>";
	}


	// add to db Last row
	/*echo "<form action=HCDD1_TSE.php method=post>";
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
