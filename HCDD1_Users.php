<html>
    <head>
	<title>HCDD No. 1 Database - USERS</title>
	</head>
	<body>

<h2>
HCDD No. 1 User List
</h2>




	<?php
	include "connection.php";
  include 'rigo.html';

	//Search all db
	$strSQL = "SELECT * FROM HCDD1User ORDER BY User_ID DESC";


	if (isset($_POST['rank0'])) {
		$select_dept = $_POST['selectdept'];
		$select_rank = $_POST['selectrank'];
		$strSQL = "SELECT * FROM HCDD1User WHERE Dept LIKE '{$select_dept}' AND Rank LIKE '{$select_rank}' ORDER BY User_ID DESC";
		$rs = mysql_query($strSQL, $con);
	};



	if (isset($_POST['search'])) {
		$search_term = $_POST['search_box'];
		$strSQL = "SELECT * FROM HCDD1User WHERE Rank LIKE '{$search_term}' OR FirstName LIKE '{$search_term}' OR LastName LIKE '{$search_term}' OR UList LIKE '{$search_term}' ORDER BY User_ID DESC";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['Reset'])) {
		$strSQL = "SELECT * FROM HCDD1User ORDER BY User_ID DESC";
		$rs = mysql_query($strSQL, $con);
	};

	if(isset($_POST['update'])){
		$UpdateQuery = "UPDATE HCDD1User SET User_ID='$_POST[User_ID]', FirstName='$_POST[firstname]', LastName='$_POST[lastname]', Dept='$_POST[dept]', Rank='$_POST[rank]', UList='$_POST[ulist]' WHERE User_ID='$_POST[hidden]'";
		mysql_query($UpdateQuery, $con);
	};

	if(isset($_POST['delete'])){
		$DeleteQuery = "DELETE FROM HCDD1User WHERE User_ID='$_POST[hidden]'";
		mysql_query($DeleteQuery, $con);
	};

	if(isset($_POST['add'])){
		$AddQuery = "INSERT INTO HCDD1User (FirstName, LastName, Dept, Rank, UList) VALUES ('$_POST[ufirstname]','$_POST[ulastname]','$_POST[udept]','$_POST[urank]','$_POST[uulist]')";
		mysql_query($AddQuery, $con);
	};

	// Pull Down for Dept
	echo "<form action=HCDD1_Users.php method=post>";

	echo "<input type='text' name='search_box' value='' />";
	echo "<input type='submit' name='search' value='Search'>";
	echo "<input type=submit name=Reset value=Reset>";

	$strSQLp1 = "SELECT Dept FROM HCDD1User";
	$rsp1 = mysql_query($strSQLp1, $con);
	echo "<select name='selectdept'>";
	echo "<option value='%'> </option>";
	while ($row = mysql_fetch_array($rsp1)){
	echo "<option value='" . $row['Dept']."'>".$row['Dept']."</option>";
	};
	echo "</select>";

	$strSQLp2 = "SELECT Rank FROM HCDD1User";
	$rsp2 = mysql_query($strSQLp2, $con);
	echo "<select name='selectrank'>";
	echo "<option value='%'> </option>";
	while ($row = mysql_fetch_array($rsp2)){
	echo "<option value='" . $row['Rank']."'>".$row['Rank']."</option>";
	};
	echo "</select>";

	echo "<input type=submit name=rank0 value=Filter>";
	echo "</form>";



	//Result Table
	$rs = mysql_query($strSQL, $con);

	//Table Columns
	echo "<table border=0 bgcolor=#CCCCCC>
	<tr bgcolor=#CCCCCC>
	<th>User_ID</th>
	<th>First Name</th>
	<th>Last Name</th>
	<th>Dept</th>
	<th>Rank</th>
	<th>UList</th>
	<th></th>
	<th></th>
	<th></th>
	</tr>";

	// add to db Last row
	echo "<form action=HCDD1_Users.php method=post>";
	echo "<tr>";
	echo "<td bgcolor=#CCCCCC><input type=hidden name=uUser_ID></td>";
	echo "<td><input type=text name=ufirstname></td>";
	echo "<td><input type=text name=ulastname></td>";
	echo "<td><input type=text name=udept></td>";
	echo "<td><input type=text name=urank></td>";
	echo "<td><input type=text name=uulist></td>";
	echo "<td><input type=hidden name=uhidden></td>";
	echo "<td>" . "<input type=submit name=add value=New" . " </td>";
	echo "</tr>";
	echo "</form>";

	// Loop the recordset $rs  // Each row will be made into an array ($row) using mysql_fetch_array
	while($row = mysql_fetch_array($rs)){
	echo "<form action=HCDD1_Users.php method=post>";
	echo "<tr>";
	echo "<td>" . "<input type=text name=User_ID value=" . $row['User_ID'] ."> </td>";
	echo "<td>" . "<input type=text name=firstname value=" . $row['FirstName'] ."> </td>";
	echo "<td>" . "<input type=text name=lastname value=" . $row['LastName'] . "> </td>";
	echo "<td>" . "<input type=text name=dept value=" . $row['Dept'] . "> </td>";
	echo "<td>" . "<input type=text name=rank value=" . $row['Rank'] . "> </td>";
	echo "<td>" . "<input type=text name=ulist value=" . $row['UList'] . "> </td>";
	echo "<td>" . "<input type=hidden name=hidden value=" . $row['User_ID'] ."> </td>";
	echo "<td>" . "<input type=submit name=update value=Update" . "> </td>";
	echo "<td>" . "<input type=submit name=delete value=Del." . "> </td>";
	echo "</tr>";
	echo "</form>";
	}


	echo "</table>";

	// Close the database connection
	mysql_close($con);
	?>
	</body>
	</html>
