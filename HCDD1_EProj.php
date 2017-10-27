<html>
    <head>
	<title>HCDD#1 Database - Proj (A/E)</title>
	</head>






	<?php
  //Include the sql connection
	include "connection.php";
  // Includes the boot header which has shared css and the printing configuration
  include 'boot.html';
  //This is only an alert to let people know they are in my computer
  include 'rigo.html';
  $status= ['Status','Construction','Design', 'Hold','Pending','Drop','Completed'];
  $statusV=['%','const','desig','hold','pendi','drop','compl'];
  $filter=['Order by','Project Id','Project Number','Owner','Pct','Project Name','Engineer Name', 'Acct No','Contract No'. 'Start Date','Status'];
  $filterV=['Proj_id','Proj_id','ProjNo', 'Owner','Pct','ProjOfficial','EngName','AcctNo', 'ContractNo','StartDate','Status'];

  ?>
  <body>
  <style type="text/css">
    @media print {


      table th {
        -webkit-print-color-adjust: exact !important;
        background-color: red !important;
      }
    }
  </style>
    <div id="t1">
  <div class="jumbotron">
    <h1 style="color:#51b949">
      HCDD No. 1 Project List Editor
    </h1>
</div>
  <?php
  $orderBy='DESC';

  if (isset($_POST['check'])&&$_POST['check']=='yes'){
    $orderBy='ASC';
  }
  //Setup  filter by default which sets the criteria seraches are going to be done
  if(isset ($_POST['filter'])){
 $search_filter = $_POST['filter'];
 }else{
   $search_filter = 'Proj_id';
 }
	//Search all db for original page pupulation
	$strSQL = "SELECT * FROM HCDD1Prj ORDER BY $search_filter $orderBy";

//If serach button is pressed
	if (isset($_POST['rank0'])) {
		$select_1 = $_POST['selectowner'];
		//$select_rank = $_POST['selectrank'];
		$strSQL = "SELECT * FROM HCDD1Prj WHERE Owner LIKE '{$select_1}' ORDER BY $search_filter $orderBy";
		$rs = mysql_query($strSQL, $con);
	};

	if (isset($_POST['search'])) {

//Check if search term is empty , if so make it a wild card
		$search_term = $_POST['search_box'];
    if(strlen($search_term)<1){
      $search_term="%";
    }
//If post status is set and not equal wild card AKA default
   if(isset($_POST['statusF']) && $_POST['statusF']!="%"){



     $strSQL = "SELECT * FROM HCDD1Prj WHERE Owner LIKE '%{$search_term}%' AND (Status='{$_POST['statusF']}')OR ProjOfficial LIKE '%{$search_term}%'AND (Status='{$_POST['statusF']}') OR ProjDesc LIKE '%{$search_term}%'AND (Status='{$_POST['statusF']}') OR ProjNo LIKE '%$search_term%' AND (Status='{$_POST['statusF']}') ORDER BY $search_filter $orderBy";

   }
   //Else do a regular look up without status filter
   else{

     $strSQL = "SELECT * FROM HCDD1Prj WHERE Owner LIKE '%{$search_term}%' OR ProjOfficial LIKE '%{$search_term}%' OR ProjDesc LIKE '%{$search_term}%' OR ProjNo LIKE '%{$search_term}%' ORDER BY $search_filter $orderBy";

   }
  //Execute query and assign to result variable

		$rs = mysql_query($strSQL, $con);
	};
// If reseet button is pressed
	if (isset($_POST['Reset'])) {
		$strSQL = "SELECT * FROM HCDD1Prj ORDER BY $search_filter $orderBy";
		$rs = mysql_query($strSQL, $con);
	};
// IF and update button is pressedi it will update that row
	if	(isset($_POST['update'])){
		$UpdateQuery = "UPDATE HCDD1Prj SET Proj_id='$_POST[projid]', ProjNo='$_POST[projno]', Owner='$_POST[owner]', Pct='$_POST[pct]', ProjOfficial='$_POST[projofficial]', ProjDesc='$_POST[projdesc]', EngName='$_POST[engname]', Estimate='$_POST[estimate]', AcctNo='$_POST[acctno]', ContractNo='$_POST[contractno]', StartDate='$_POST[startdate]', EndDate='$_POST[enddate]',Status='$_POST[status]', PList='$_POST[plist]' WHERE Proj_id='$_POST[hidden]'";
		mysql_query($UpdateQuery, $con) or die(mysql_error());
	};
// If deleted button is pressesd go ahead an delete that entry
	if	(isset($_POST['delete'])){
		$DeleteQuery = "DELETE FROM HCDD1Prj WHERE Proj_id='$_POST[hidden]'";
		mysql_query($DeleteQuery, $con);
	};
// If add button is pressed fo ahead and add new entry
	if	(isset($_POST['add'])){
		$AddQuery = "INSERT INTO HCDD1Prj (ProjNo, Owner, Pct, ProjOfficial, ProjDesc, EngName, Estimate, AcctNo, ContractNo, StartDate, EndDate, Status, PList) VALUES ('$_POST[uprojno]','$_POST[uowner]','$_POST[upct]','$_POST[uprojofficial]','$_POST[uprojdesc]','$_POST[uengname]','$_POST[uestimate]','$_POST[uacctno]','$_POST[ucontractno]','$_POST[ustartdate]','$_POST[uenddate]','$_POST[status]','$_POST[uplist]')";
		mysql_query($AddQuery, $con);
	};
  // First level of search tools
	// Pull Down
	echo "<form action=HCDD1_EProj.php method=post class='form-group'>";

  echo "<input type='text' name='search_box'   class='form-control input-lg' align='center' placeholder=' Enter query' /> &nbsp; &nbsp; ";
  echo "<select name='statusF' class='form-control input-lg'>";
  for($i=0;$i<7;$i++){
       if(isset($_POST['statusF']) && $_POST['statusF']!="%" && $_POST['statusF']==$statusV[$i]){
    echo "<option value='$statusV[$i]' selected> $status[$i] </option> ";
  }else{
    echo "<option value='$statusV[$i]'> $status[$i] </option> ";
  }

  }

  echo"</select>";
  echo "<select name='filter' class='form-control input-lg'>";
  for($i=0;$i<10;$i++){
       if(isset($_POST['filter']) && $_POST['filter']==$filterV[$i]){
    echo "<option value='$filterV[$i]' selected> $filter[$i] </option> ";
  }else{
    echo "<option value='$filterV[$i]'> $filter[$i] </option> ";
  }

  }

  echo "</select> &nbsp;";
  if (isset($_POST['check'])&&$_POST['check']=='yes'){
  echo "<div class='checkbox'><label><input type='checkbox' name='check' value='yes' checked> Ascending </label></div>";
}else{
    echo "<div class='checkbox'><label><input type='checkbox' name='check' value='yes'> Ascending </label></div>";
}
	echo "<input type='submit' class='btn btn-default' name='search' value='Search'>&nbsp;";
	echo "<input type='submit' class='btn btn-default' name=Reset value=Reset><hr>";
    //Second line filter options
		//Select Pct to Pull Down for Filter
  echo"<div class='dropdown show'>";
	echo "<select name='selectowner' class='form-control input-lg'>";
	echo "<option value='%'>Owner </option>";
	echo "<option value='HCDD1'>HCDD1</option>";
	echo "<option value='Pct1'>Pct1</option>";
	echo "<option value='Pct2'>Pct2</option>";
	echo "<option value='Pct3'>Pct3</option>";
	echo "<option value='Pct4'>Pct4</option>";
	echo "<option value='Misc'>Misc</option>";
	echo "<option value='DR'>DR</option>";
	echo "</select>&nbsp; &nbsp;";

	/*	//Select Pct to Pull Down for Filter by table data
	$strSQLp1 = "SELECT Pct FROM HCDD1Prj ORDER BY $search_filter DESC";
	$rsp1 = mysql_query($strSQLp1, $con);
	echo "<select name='selectpct'>";
	echo "<option value='%'> </option>";
	while ($row = mysql_fetch_array($rsp1)){
	echo "<option value='" . $row['Pct']."'>".$row['Pct']."</option>";
	};
	echo "</select>";
	*/

//Filter button
	echo "<input type=submit name=rank0 value=Filter class='btn btn-default'><hr>";
	echo "</form>";
  echo"</div>";
  echo "<p style='float: right; margin-bottom:20; margin-top:20;'><button  class='btn btn-info' onclick='window.print();'>PRINT</button></p>";
  echo"</div>";

	//Result Table
	$rs = mysql_query($strSQL, $con);

	//Table Columns
  echo"<div id='t2'>";
	echo "<table  class='table table-hover' id='table1'>
	<tr style='color:white;' bgcolor='#275c24' class='row1'>
	<th  id='th1'>Proj ID</th>
	<th >Proj No</th>
	<th>Owner</th>
	<th>Pct</th>
	<th>Proj Name</th>
	<th>Proj Desc/Limits</th>
	<th>Eng Name</th>
	<th>Estimate</th>
	<th>Acct. No</th>
	<th>Contract No</th>
	<th >Start Date</th>
	<th>End Date</th>
  <th>Status</td>
	<th>List</th>
	<th></th>
	<th></th>
	</tr>";



	// add to db Last row
	echo "<form action=HCDD1_EProj.php method=post>";
	echo "<tr bgcolor='#b3b3b3'>";
	echo "<td ><input type=hidden name=uprojid></td>";
	echo "<td ><input type=text name=uprojno placeholder='Mandatory Field'></td>";
	echo "<td ><input type=text name=uowner placeholder='Mandatory Field'></td>";
	echo "<td ><input type=text name=upct></td>";
	echo "<td ><textarea rows='1' cols='40' name=uprojofficial value=></textarea></td>";
	echo "<td ><textarea rows='1' cols='40' name=uprojdesc value=></textarea></td>";
	echo "<td ><textarea rows='1' cols='40' name=uengname value=></textarea></td>";
	echo "<td ><input type=text name=uestimate placeholder='Only Decimal Number'></td>";
	echo "<td ><input type=text name=uacctno></td>";
	echo "<td ><input type=text name=ucontractno></td>";
	echo "<td ><input type=date name=ustartdate></td>";
	echo "<td ><input type=date name=uenddate></td>";
  	echo "<td  ><input type=text name=status maxlength='5' placeholder='Mandatory Field'></td>";
	echo "<td  ><input type=text name=uplist maxlength='1' placeholder='Mandatory Field'></td>";
	echo "<td><input type=hidden name=uhidden></td>";
	echo "<td>" . "<input type=submit name=add value=New" . "> </td>";
	echo "</tr>";
	echo "</form>";

	// Loop the recordset $rs  // Each row will be made into an array ($row) using mysql_fetch_array
	while($row = mysql_fetch_array($rs)){
	echo "<form action=HCDD1_EProj.php method=post>";
	echo "<tr>";
	echo "<td id='th1'>" . $row['Proj_id'] ."</td>";
	//echo "<td bgcolor=#CCCCCC>" . "<input type=text name=projid value=" . $row['Proj_id'] ." </td>";
	echo "<input type=hidden name=projid value=" . $row['Proj_id'] .">";
	echo "<td  bgcolor=white>" . "<input type=text  name=projno value=" . $row['ProjNo'] ."> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<input type=text  name=owner value=" . $row['Owner'] ."> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<input type=text name=pct value=" . $row['Pct'] ."> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<textarea rows='1' cols='40' name=projofficial value=>" . $row['ProjOfficial'] . "</textarea> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<textarea rows='1' cols='40' name=projdesc value=>" . $row['ProjDesc'] . "</textarea> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<textarea rows='1' cols='40' name=engname value=>" . $row['EngName'] . "</textarea> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<input type=text  name=estimate value=" . $row['Estimate'] . "> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<input type=text name=acctno value=" . $row['AcctNo'] . "> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<input type=text name=contractno value=" . $row['ContractNo'] . "> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<input type=date name=startdate value=" . $row['StartDate'] . "> </td>";
	echo "<td bgcolor=#FFFFFF>" . "<input type=date name=enddate value=" . $row['EndDate'] . "> </td>";
  echo "<td bgcolor=#FFFFFF > " . "<input type=text name=status maxlength='5' size='5' value=" . $row['Status'] . "> </td>";
	echo "<td bgcolor=#FFFFFF >" . "<input type=text  size='1' maxlength='1' name=plist value=" . $row['PList'] . ">  &nbsp;&nbsp;&nbsp;&nbsp;   <input type=submit name=update value=Update" . "> <input type=submit name=delete value=Del." . "> </td>";
	echo "<td>" . "<input type=hidden name=hidden value=" . $row['Proj_id'] ."> </td>";
	echo "</tr>";
	echo "</form>";
	}




	echo "</table>";
  echo"</div>";

	// Close the database connection
	mysql_close($con);
	?>
	</body>
  <script>
		/*
		 * HTML: Print Wide HTML Tables
		 * http://salman-w.blogspot.com/2013/04/printing-wide-html-tables.html
		 */
		$(function() {
			$("#print-button").on("click", function() {
				var table = $("#table1"),
					tableWidth = table.outerWidth(),
					pageWidth = 1200,
					pageCount = Math.ceil(tableWidth / pageWidth),
					printWrap = $("<div></div>").insertAfter(table),
					i,
					printPage;
				for (i = 0; i < pageCount; i++) {

					printPage = $("<div></div>").css({
						"overflow": "hidden",
						"width": pageWidth,
						"page-break-before": i === 0 ? "auto" : "always"
					}).appendTo(printWrap);
					table.clone().removeAttr("id").appendTo(printPage).css({
						"position": "relative",
						"left": -i * pageWidth
					});
				}
				table.hide();
				$(this).prop("disabled", true);
			});
		});
	</script>
	</html>
