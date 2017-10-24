<html>
    <head>
	<title>HCDD#1 Database - Proj</title>
	</head>
	<body>

<h2>
HCDD#1 Proj. List
</h2>




	<?php
	include "connection.php";
    include 'rigo.html';

	//Search all db
	$strSQL = "SELECT * FROM HCDD1Prj ORDER BY Proj_id DESC";


	if (isset($_POST['rank0'])) {
		$select_1 = $_POST['selectowner'];
		//$select_rank = $_POST['selectrank'];
		$strSQL = "SELECT * FROM HCDD1Prj WHERE Owner LIKE '{$select_1}' ORDER BY Proj_id DESC";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['search'])) {
		$search_term = $_POST['search_box'];
		$strSQL = "SELECT * FROM HCDD1Prj WHERE Owner LIKE '{$search_term}' OR ProjOfficial LIKE '{$search_term}' OR ProjDesc LIKE '{$search_term}' ORDER BY Proj_id DESC";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['Reset'])) {
		$strSQL = "SELECT * FROM HCDD1Prj ORDER BY Proj_id DESC";
		$rs = mysql_query($strSQL, $con);
	};

	if	(isset($_POST['update'])){
		$UpdateQuery = "UPDATE HCDD1Prj SET Proj_id='$_POST[projid]', ProjDesc='$_POST[projdesc]', EngName='$_POST[engname]', Estimate='$_POST[estimate]', AcctNo='$_POST[acctno]', ContractNo='$_POST[contractno]', StartDate='$_POST[startdate]', EndDate='$_POST[enddate]' WHERE Proj_id='$_POST[hidden]'";
		mysql_query($UpdateQuery, $con);
	};

/*
	if	(isset($_POST['delete'])){
		$DeleteQuery = "DELETE FROM HCDD1Prj WHERE Proj_id='$_POST[hidden]'";
		mysql_query($DeleteQuery, $con);
	};
*/

/*
	if	(isset($_POST['add'])){
		$AddQuery = "INSERT INTO HCDD1Prj (ProjNo, Pct, ProjOfficial, ProjDesc, EngName, Estimate, AcctNo, ContractNo, StartDate, EndDate) VALUES ('$_POST[uprojno]','$_POST[upct]','$_POST[uprojofficial]','$_POST[uprojdesc]','$_POST[uengname]','$_POST[uestimate]','$_POST[uacctno]','$_POST[ucontractno]','$_POST[ustartdate]','$_POST[uenddate]')";
		mysql_query($AddQuery, $con);
	};
*/

	// Pull Down
	echo "<form action=HCDD1_Proj.php method=post>";

	echo "<input type='text' name='search_box' value='' />";
	echo "<input type='submit' name='search' value='Search'>";
	echo "<input type=submit name=Reset value=Reset>";

		//Select Pct to Pull Down for Filter
	echo "<select name='selectowner'>";
	echo "<option value='%'>{Owner} </option>";
	echo "<option value='HCDD1'>HCDD1</option>";
	echo "<option value='Pct1'>Pct1</option>";
	echo "<option value='Pct2'>Pct2</option>";
	echo "<option value='Pct3'>Pct3</option>";
	echo "<option value='Pct4'>Pct4</option>";
	echo "<option value='Misc'>Misc</option>";
	echo "<option value='DR'>DR</option>";
	echo "</select>";

	/*	//Select Pct to Pull Down for Filter by table data
	$strSQLp1 = "SELECT Pct FROM HCDD1Prj ORDER BY Proj_id DESC";
	$rsp1 = mysql_query($strSQLp1, $con);
	echo "<select name='selectpct'>";
	echo "<option value='%'> </option>";
	while ($row = mysql_fetch_array($rsp1)){
	echo "<option value='" . $row['Pct']."'>".$row['Pct']."</option>";
	};
	echo "</select>";
	*/

	echo "<input type=submit name=rank0 value=Filter>";
	echo "</form>";



	//Result Table
	$rs = mysql_query($strSQL, $con);

	//Table Columns
	echo "<table border=1 bgcolor=#CCCCCC>
	<tr bgcolor=#CCCCCC>
	<th>Proj ID</th>
	<th>Proj No</th>
	<th>Owner</th>
	<th>Pct</th>
	<th>Proj Name</th>
	<th>Proj Desc/Limits</th>
	<th>Eng Name</th>
	<th>Estimate</th>
	<th>Acct. No</th>
	<th>Contract No</th>
	<th>Start Date</th>
	<th>End Date</th>
	<th></th>
	<th></th>
	</tr>";


/*
	// add to db Last row
	echo "<form action=HCDD1_Proj.php method=post>";
	echo "<tr>";
	echo "<td bgcolor=#CCCCCC><input type=hidden name=uprojid></td>";
	echo "<td bgcolor=#FFFFFF><input type=text name=uprojno></td>";
	echo "<td bgcolor=#FFFFFF><input type=text name=upct></td>";
	echo "<td bgcolor=#FFFFFF><textarea rows='1' cols='40' name=uprojofficial value=></textarea></td>";
	echo "<td bgcolor=#FFFFFF><textarea rows='1' cols='40' name=uprojdesc value=></textarea></td>";
	echo "<td bgcolor=#FFFFFF><textarea rows='1' cols='40' name=uengname value=></textarea></td>";
	echo "<td bgcolor=#FFFFFF><input type=text name=uestimate></td>";
	echo "<td bgcolor=#FFFFFF><input type=text name=uacctno></td>";
	echo "<td bgcolor=#FFFFFF><input type=text name=ucontractno></td>";
	echo "<td bgcolor=#FFFFFF><input type=text name=ustartdate></td>";
	echo "<td bgcolor=#FFFFFF><input type=text name=uenddate></td>";
	echo "<td><input type=hidden name=uhidden></td>";
	echo "<td>" . "<input type=submit name=add value=New" . "> </td>";
	echo "</tr>";
	echo "</form>";
	*/

	// Loop the recordset $rs  // Each row will be made into an array ($row) using mysql_fetch_array
	while($row = mysql_fetch_array($rs)){
	echo "<form action=HCDD1_Proj.php method=post>";
	echo "<tr>";
	echo "<td>" . $row['Proj_id'] ."</td>";
	//echo "<td bgcolor=#CCCCCC>" . "<input type=text name=projid value=" . $row['Proj_id'] ." </td>";
	echo "<input type=hidden name=projid value=" . $row['Proj_id'] .">";
	echo "<td>" . $row['ProjNo'] ."</td>";
	echo "<td>" . $row['Owner'] ."</td>";
	echo "<td>" . $row['Pct'] ."</td>";
	echo "<td>" . $row['ProjOfficial'] ."</td>";
	echo "<td bgcolor=#FFFFFF>" . "<textarea rows='1' cols='40' name=projdesc value=>" . $row['ProjDesc'] . "</textarea> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<textarea rows='1' cols='40' name=engname value=>" . $row['EngName'] . "</textarea> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<input type=text name=estimate value=" . $row['Estimate'] . "> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<input type=text name=acctno value=" . $row['AcctNo'] . "> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<input type=text name=contractno value=" . $row['ContractNo'] . "> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<input type=text name=startdate value=" . $row['StartDate'] . "> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<input type=text name=enddate value=" . $row['EndDate'] . "> </td>";
	echo "<td>" . "<input type=hidden name=hidden value=" . $row['Proj_id'] ."> </td>";
	echo "<td>" . "<input type=submit name=update value=Update" . "> </td>";
	//echo "<td>" . "<input type=submit name=delete value=Del." . "> </td>";
	echo "</tr>";
	echo "</form>";
	}




	echo "</table>";

	// Close the database connection
	mysql_close($con);
	?>
	</body>
	</html>
