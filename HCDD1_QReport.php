<html>
    <head>
	<title>HCDD No. 1 Database - Quick Report</title>
  <link rel="stylesheet" href="./styles.css">

  <?php
  include "connection.php";
  include "boot.html";
  include "nav.html";
  include 'rigo.html';
  $select_user = "";
  $select_owner = "";
  $select_pct = "";
  $select_proj = "";
  $date_start ="";
  $date_end = "";

  function minutes2H($time){
    if($time < 1){
      return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return($hours.":".$minutes);
  }

  ?>



	</head>
	<body>
    <div id="t1">
<div class="jumbotron" >

<h1 style='color:#51b949' >
HCDD No. 1 Quick Report
</h1>
</div>




	<?php


	//Search all db
	$strSQL = "SELECT *, TIMESTAMPDIFF (MINUTE,TimeIn, TimeOut) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id ORDER BY id DESC";
	//$strSQL = "SELECT *, SUM(TIMESTAMPDIFF (MINUTE,TimeIn, TimeOut)) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id Group BY HCDD1SWT.Proj_ID";


	if(isset($_POST['tse'])){
		echo "<script>window.location = 'http://www.jagzce.com/HCDD1/HCDD1_TSE.php'</script>";
	};

	if (isset($_POST['QRUser'])) {
		$select_user = $_POST['selectus1'];
		$select_owner = $_POST['selectowner'];
		$select_pct = $_POST['selectpct'];
		$select_proj = $_POST['selectproj'];
    $select_projPN = $_POST['selectPN'];
    $select_status = $_POST['status'];
		$strSQL = "SELECT *, SUM(TIMESTAMPDIFF (MINUTE,TimeIn, TimeOut)) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1User.User_ID LIKE '{$select_user}' AND HCDD1Prj.Owner LIKE '{$select_owner}' AND HCDD1Prj.Pct LIKE '{$select_pct}' AND HCDD1Prj.Proj_id LIKE '{$select_proj}' AND HCDD1Prj.ProjNo LIKE '{$select_projPN}'AND HCDD1Prj.Status LIKE '{$select_status}' AND HCDD1Prj.Status LIKE '{$select_status}'  GROUP BY HCDD1SWT.User_ID, HCDD1SWT.Proj_ID";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['QRProj'])) {
		$select_user = $_POST['selectus1'];
		$select_owner = $_POST['selectowner'];
		$select_pct = $_POST['selectpct'];
		$select_proj = $_POST['selectproj'];
    $select_projPN = $_POST['selectPN'];
    $select_status = $_POST['status'];
		//$strSQL = "SELECT *, SUM(TIMESTAMPDIFF (MINUTE,TimeIn, TimeOut)) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1User.User_ID LIKE '{$select_user}' AND HCDD1Prj.Pct LIKE '{$select_pct}' AND HCDD1Prj.Proj_id LIKE '{$select_proj}' GROUP BY HCDD1SWT.Proj_ID";
		$strSQL = "SELECT *, SUM(TIMESTAMPDIFF (MINUTE,TimeIn, TimeOut)) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1User.User_ID LIKE '{$select_user}' AND HCDD1Prj.Owner LIKE '{$select_owner}' AND HCDD1Prj.Pct LIKE '{$select_pct}' AND HCDD1Prj.Proj_id LIKE '{$select_proj}'  AND HCDD1Prj.ProjNo LIKE '{$select_projPN}'AND HCDD1Prj.Status LIKE '{$select_status}'  GROUP BY HCDD1SWT.Proj_ID, HCDD1SWT.User_ID with ROLLUP";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['rank0'])) {
		$select_user = $_POST['selectus1'];
		$select_owner = $_POST['selectowner'];
		$select_pct = $_POST['selectpct'];
		$select_proj = $_POST['selectproj'];
    $select_projPN = $_POST['selectPN'];
    $select_status = $_POST['status'];
		$strSQL = "SELECT *, TIMESTAMPDIFF (MINUTE,TimeIn, TimeOut) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1User.User_ID LIKE '{$select_user}' AND HCDD1Prj.Owner LIKE '{$select_owner}' AND HCDD1Prj.Pct LIKE '{$select_pct}' AND HCDD1Prj.Proj_id LIKE '{$select_proj}' AND HCDD1Prj.ProjNo LIKE '{$select_projPN}'AND HCDD1Prj.Status LIKE '{$select_status}'  ORDER BY id DESC";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['filterdate'])) {
		$select_user = $_POST['selectus1'];
		$select_owner = $_POST['selectowner'];
		$select_pct = $_POST['selectpct'];
		$select_proj = $_POST['selectproj'];
    $select_status = $_POST['status'];
		$date_start = $_POST['dateS'];
		$date_end = $_POST['dateE'];
		$strSQL = "SELECT *, TIMESTAMPDIFF (MINUTE,TimeIn, TimeOut) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1User.User_ID LIKE '{$select_user}' AND HCDD1Prj.Owner LIKE '{$select_owner}' AND HCDD1Prj.Pct LIKE '{$select_pct}' AND HCDD1Prj.Proj_id LIKE '{$select_proj}' AND HCDD1Prj.ProjNo LIKE '{$select_projPN}'AND HCDD1Prj.Status LIKE '{$select_status}'  AND HCDD1SWT.TimeDate >= '{$date_start}' AND HCDD1SWT.TimeDate <= '{$date_end}'  ORDER BY id DESC";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['filterdateuser'])) {
		$select_user = $_POST['selectus1'];
		$select_owner = $_POST['selectowner'];
		$select_pct = $_POST['selectpct'];
		$select_proj = $_POST['selectproj'];
    $select_status = $_POST['status'];
		$date_start = $_POST['dateS'];
		$date_end = $_POST['dateE'];
		$strSQL = "SELECT *, SUM(TIMESTAMPDIFF (MINUTE,TimeIn, TimeOut)) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1User.User_ID LIKE '{$select_user}' AND HCDD1Prj.Owner LIKE '{$select_owner}' AND HCDD1Prj.Pct LIKE '{$select_pct}' AND HCDD1Prj.Proj_id LIKE '{$select_proj}' AND HCDD1Prj.ProjNo LIKE '{$select_projPN}'AND HCDD1Prj.Status LIKE '{$select_status}'  AND HCDD1SWT.TimeDate >= '{$date_start}' AND HCDD1SWT.TimeDate <= '{$date_end}'  GROUP BY HCDD1SWT.User_ID, HCDD1SWT.Proj_ID";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['filterdateproj'])) {
		$select_user = $_POST['selectus1'];
		$select_owner = $_POST['selectowner'];
		$select_pct = $_POST['selectpct'];
		$select_proj = $_POST['selectproj'];
    $select_status = $_POST['status'];
		$date_start = $_POST['dateS'];
		$date_end = $_POST['dateE'];
		$strSQL = "SELECT *, SUM(TIMESTAMPDIFF (MINUTE,TimeIn, TimeOut)) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1User.User_ID LIKE '{$select_user}' AND HCDD1Prj.Owner LIKE '{$select_owner}' AND HCDD1Prj.Pct LIKE '{$select_pct}' AND HCDD1Prj.Proj_id LIKE '{$select_proj}' AND HCDD1Prj.ProjNo LIKE '{$select_projPN}' AND HCDD1Prj.Status LIKE '{$select_status}' AND HCDD1SWT.TimeDate >= '{$date_start}' AND HCDD1SWT.TimeDate <= '{$date_end}'  GROUP BY HCDD1SWT.Proj_ID, HCDD1SWT.User_ID with ROLLUP";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['search'])) {
		$search_term = $_POST['search_box'];
		$strSQL = "SELECT *, TIMESTAMPDIFF (MINUTE,TimeIn, TimeOut) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id WHERE HCDD1User.User_ID LIKE '%{$search_term}%' OR HCDD1User.FirstName LIKE '%{$search_term}%' OR HCDD1User.LastName LIKE '%{$search_term}%' OR HCDD1Prj.Proj_ID LIKE '%{$search_term}%' OR HCDD1Prj.ProjNo LIKE '%{$search_term}%' OR HCDD1Prj.Owner LIKE '%{$search_term}%' OR HCDD1Prj.Pct LIKE '%{$search_term}%' OR HCDD1Prj.ProjOfficial LIKE '%{$search_term}%' OR HCDD1SWT.id LIKE '%{$search_term}%' OR HCDD1SWT.PDesc LIKE '%{$search_term}%' ORDER BY id DESC";
		//$strSQL = "SELECT * FROM HCDD1SWT WHERE Rank LIKE '%{$search_term}%' OR FirstName LIKE '%{$search_term}%' OR LastName LIKE '%{$search_term}%' ORDER BY User_ID";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['Reset'])) {
		$strSQL = "SELECT *, TIMESTAMPDIFF (MINUTE,TimeIn, TimeOut) AS TotalTime FROM HCDD1SWT LEFT JOIN HCDD1User ON HCDD1SWT.User_ID=HCDD1User.User_ID LEFT JOIN HCDD1Prj ON HCDD1SWT.Proj_ID=HCDD1Prj.Proj_id ORDER BY id DESC";
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

	/*
	if(isset($_POST['add'])){
		$AddQuery = "INSERT INTO HCDD1SWT (FirstName, LastName, Dept, Rank) VALUES ('$_POST[ufirstname]','$_POST[ulastname]','$_POST[udept]','$_POST[urank]')";
		mysql_query($AddQuery, $con);
	};*/

	// Pull Down for Dept
	echo "<form  class='form-group' action=HCDD1_QReport.php method=post  >";

	echo "<input type='text' name='search_box'   class='form-control input-lg' align='center' placeholder=' Enter query' /> </br></br></br>";
	echo "<input type='submit' class='btn btn-default' name='search' value='Search'>";
	echo "<input type='submit' class='btn btn-default' name=Reset value=Reset><hr>";


	//Select User and add to Pull Down for Filter
	$strSQLp1 = "SELECT User_ID, Concat (FirstName, ' ', LastName, ' - ', User_ID) AS UserName FROM HCDD1User";
	$rsp1 = mysql_query($strSQLp1, $con);
  echo"<div class='dropdown show'>";
	echo "<select name='selectus1' class='form-control'>";
  echo "<option value='%'>User </option>";
	while ($row = mysql_fetch_array($rsp1)){
	echo "<option value='" . $row['User_ID']."'>".$row['UserName']."</option>";
	};
	echo "</select>";
  echo "</div>";


	//Select Pct to Pull Down for Filter
	echo "<select name='selectowner' class='form-control'>";
	echo "<option value='%'>Owner </option>";
	echo "<option value='DD1'>DD1</option>";
	echo "<option value='P1'>P1</option>";
	echo "<option value='P2'>P2</option>";
	echo "<option value='P3'>P3</option>";
	echo "<option value='P4'>P4</option>";
	echo "<option value='Misc'>Misc</option>";
	echo "<option value='DR'>DR</option>";
	echo "</select>";

		//Select Pct to Pull Down for Filter
	echo "<select name='selectpct' class='form-control'>";
	echo "<option value='%'>Precint </option>";
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
  echo "<select name='selectproj' class='form-control'>";
	echo "<option value='%'>Project </option>";
	while ($row = mysql_fetch_array($rsp2)){
	echo "<option value='" . $row['Proj_id']."'>".$row['Projlist']."</option>";
	};
	echo "</select> ";
  //Project number selsect menu
  $strSQLp2 = "SELECT * FROM HCDD1Prj ORDER BY Proj_id DESC";
  $rsp2 = mysql_query($strSQLp2, $con);
  echo "<select name='selectPN' class='form-control'>";
	echo "<option value='%'>Project Number </option>";

	while ($row = mysql_fetch_array($rsp2)){
    if(strlen($row['ProjNo'])>1){
	echo "<option value='".$row['ProjNo']."'>".$row['ProjNo']."</option>";
                                }
	};
	echo "</select>";
  echo "<select name='status' class='form-control'>";
  echo "<option value='%'>Status </option>";
  echo "<option value='pen'>Pending </option>";
  echo "<option value='com'>Completed </option>";
  echo "<option value='dro'>Droped </option>";
  echo "</select> ";
  echo"</br></br>";

	echo "<input type=submit name=rank0 value='Filter' class='btn btn-default'>";
	echo "<input type=submit name=QRUser value='Filter User' class='btn btn-default'>";
	echo "<input type=submit name=QRProj value='Filter Project' class='btn btn-default'><hr style='border-width:5px'>";



	echo "<input type='month' name='dateS' value='' class='form-control' />";
	echo "<input type='month' name='dateE' value=''class='form-control' /></br></br>";
	echo "<input type='submit' name='filterdate' value='Date Filter'  class='btn btn-default'>";
	echo "<input type='submit' name='filterdateuser' value='Date Filter User'  class='btn btn-default'>";
	echo "<input type='submit' name='filterdateproj' value='Date Filter Project Total'  class='btn btn-default'>";


	echo "</form><hr>";


	//Result Table
	$rs = mysql_query($strSQL, $con);

	echo "<h2 style='color:51b949;font-weight:bold'>User: {$select_user} | Owner: {$select_owner} |   Pct: {$select_pct} |  Proj: {$select_proj}  | Start Date: {$date_start} |  End Date: {$date_end}</h2>"  ;
  echo "<p style='float: right; margin-bottom:20; margin-top:20;'><button  class='btn btn-info' onclick='window.print();'>PRINT</button></p>";
  echo "</div>";

	//Table Columns
  echo "<div >
	<table class='table table-hover table-inverse' id='t2'>
	<tr bgcolor=black>
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
  <th>Status</th>
	<th>Hours</th>
	</tr>";
  	echo "<form action=HCDD1_QReport.php method=post>";

//Variables for total time calcultaion
$total=0;
$currentID=0;
$totalU=0;
$currentP=0;
	// Loop the recordset $rs  // Each row will be made into an array ($row) using mysql_fetch_array
	while($row = mysql_fetch_array($rs)){
    $timeInM=minutes2H($row['TotalTime']);

      //Calculate the total time for user filter
       $total = $row['TotalTime']+$total;
       if (isset($_POST['QRUser'])||isset($_POST['filterdateuser'])) {
          if($currentID==0){
            $currentID=$row['User_ID'];
          }
          if($currentID==$row['User_ID']){
            $totalU=$row['TotalTime']+$totalU;

          }else{
            $totalU=minutes2H($totalU);
            echo "<tr style='background-color:#62676a;' ><td colspan='15' align='center'><b>Total Hours For User: ".$totalU."</b></td></tr>";
            $totalU=$row['TotalTime'];
            $currentID=$row['User_ID'];
          }
                                    }
        //Calculate the total time for project filter
        	if (isset($_POST['QRProj'])||isset($_POST['filterdateproj'])) {
            if($currentID==0){
              $currentID=$row['Proj_ID'];
            }
            if($currentID==$row['Proj_ID']){
              $totalU=$row['TotalTime']+$totalU;

            }else{
              $totalU=minutes2H($totalU);
              echo "<tr  style='background-color:#62676a;' ><td colspan='15' align='center'><b>Total Hours For Project: ".$totalU."</b></td></tr>";
              $totalU=$row['TotalTime'];
              $currentID=$row['Proj_ID'];
            } }


if (isset($_POST['QRProj'])==false&&isset($_POST['filterdateproj'])==false) {


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
  echo "<td>" . $row['Status'] ."</td>";
	echo "<td>" . $timeInM ."</td>";
	//echo "<td>" . "<input type=hidden name=hidden value=" . $row['id'] ."> </td>";
	//echo "<td>" . "<input type=submit name=update value=Update" . "> </td>";
	//echo "<td>" . "<input type=submit name=delete value=Del." . "> </td>";
	echo "</tr>";
}else{
  if($currentP==0){
    $currentP= $row['Proj_ID'];
    echo "<tr>";
    echo "<td>" . "NA" ."</td>";
    echo "<td>" . "NA"."</td>";
    echo "<td>" . "NA" ."</td>";
    echo "<td>" . "NA" ."</td>";
    echo "<td>" . $row['Proj_ID'] ."</td>";
    echo "<td>" . $row['ProjNo'] ."</td>";
    echo "<td>" . $row['Owner'] ."</td>";
    echo "<td>" . $row['Pct'] ."</td>";
    echo "<td>" . $row['ProjOfficial'] ."</td>";
    echo "<td>" . $row['PDesc'] ."</td>";
    echo "<td>" ."NA" ."</td>";
    echo "<td>" ."NA" ."</td>";
    echo "<td>" ."NA" ."</td>";
    echo "<td>" . $row['Status'] ."</td>";
    echo "<td>" ."NA"."</td>";
  }
  if($currentP!=$row['Proj_ID']){
    echo "<tr>";
    echo "<td>" ."NA" ."</td>";
    echo "<td>" ."NA" ."</td>";
    echo "<td>" ."NA" ."</td>";
    echo "<td>" . "NA" ."</td>";
    echo "<td>" . $row['Proj_ID'] ."</td>";
    echo "<td>" . $row['ProjNo'] ."</td>";
    echo "<td>" . $row['Owner'] ."</td>";
    echo "<td>" . $row['Pct'] ."</td>";
    echo "<td>" . $row['ProjOfficial'] ."</td>";
    echo "<td>" . $row['PDesc'] ."</td>";
    echo "<td>" ."NA" ."</td>";
    echo "<td>" . "NA" ."</td>";
    echo "<td>" . "NA" ."</td>";
    echo "<td>" . $row['Status'] ."</td>";
    echo "<td>" . "NA" ."</td>";
    $currentP=$row['Proj_ID'];

  }

}



}
if (isset($_POST['QRUser'])||isset($_POST['filterdateuser'])) {

  $totalU = minutes2H($totalU);
  echo "<tr style='background-color:#62676a;' ><td colspan='15' align='center' ><b>Total Hours For User: ".$totalU."</b></td></tr>";
}
if (isset($_POST['QRProj'])||isset($_POST['filterdateproj'])) {

  $totalU = minutes2H($totalU);
  echo "<tr style='background-color:#62676a;' ><td colspan='15' align='center'><b>Total Hours For Project: ".$totalU."</b></td></tr>";
}
$total = minutes2H($total);
  echo "<tr  style='background-color:#313335;' ><td colspan='15' align='right'><b>Total Hours: ".$total."</b></td></tr>";
  echo "</form>";


	// add to db Last row
	/*echo "<form action=HCDD1_QReport.php method=post>";
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
  echo "</div>";



	// Close the database connection
	mysql_close($con);
	?>
	</body>
	</html>
