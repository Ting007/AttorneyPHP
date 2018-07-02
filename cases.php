<?php 
session_start();
//you have to have login as a ipo or lawyer other wise we have to send you out
if (!isset($_SESSION["pid"])){
	header("location:login.php");
	exit();
	}
?>
<?php
$db_host = "localhost";
// Place the username for the MySQL database here
$db_username = "root";
// Place the password for the MySQL database here
$db_pass = "";
// Place the name for the MySQL database here
$db_name = "legal_office";
$conn= mysqli_connect($db_host, $db_username, $db_pass, $db_name)or die("could not connect to mysql");
?>

<?php
$pid = preg_replace('#[0-9]#i','',$_SESSION["pid"]);//the employee id for ipo is made of 0-9 only number
$username = $_SESSION["username"];//claim the variable 
$password = $_SESSION["password"];
$position = $_SESSION["position"];
$id_auth = "SELECT role from personnel WHERE name = '$username' AND password = '$password' LIMIT 1";
$sql=mysqli_query($conn, $id_auth); //query the person see if he/she in the username and password
while($row = mysqli_fetch_assoc($sql)){ 
			 $position = $row["role"];
}
if($position != "ipo"){//if the user is not ipo 
	echo "Your login session data is not on record in the IPO database.<a href=index.php>Click Here</a>";
	exit();
}
?>

<?php
//checking the case list and view all the cases
$case_list="";
$query="SELECT * FROM legal_cases";//select all the cases
$sql=mysqli_query($conn, $query);
$casesCount=mysqli_num_rows($sql);//count the output amount
if($casesCount>0){
	while($row = mysqli_fetch_array($sql)){
		$id = $row["caseid"];
		$lawyer = $row["pers_member_pid"];
		$find_name = "SELECT name FROM personnel WHERE pid = '$lawyer'";
	    $name=mysqli_query($conn, $find_name);
	    $row1=mysqli_fetch_assoc($name);
	    $lawyer_name=$row1["name"];
		$start = $row["start"];
		$complete = $row["complete"];
		$company1 = $row["c1"];
		$company2 = $row["c2"];
		$case_list .= '<table width="100%" border="0" cellsspacing="0" cellpadding="6">
	<tr>
		
		<td width = "10%" valign="top">Case ID: &nbsp;'.$id.'</td>
		<td width = "10%" valign="top" align = "left">Lawyer:&nbsp;' . $lawyer_name . '</td>
		<td width= "15%" valign = "top"> Start Date:&nbsp;' . $start . '</td>
		<td width= "15%" valign = "top"> Complete Date:&nbsp;' .$complete. ' &nbsp;&nbsp;&nbsp;
		<td width= "10%" valign = "top"> Company1:&nbsp;' .$company1. ' &nbsp;&nbsp;&nbsp;
		<td valign = "top"> Company2:&nbsp;' .$company2. ' &nbsp;&nbsp;&nbsp;
	</tr>
</table>';
		}
	}
	else{
		$case_list="The office has no cases yet";
		}
?>

<?php
//open the table and add item to list
if (isset($_POST['button1'])){
	$case_id = mysqli_real_escape_string($conn, $_POST['case_id']);//index id of cases
	$lawyer_name = mysqli_real_escape_string($conn, $_POST['lawyer_name']);//name of lawyer
	$start = mysqli_real_escape_string($conn, $_POST['date']);//start date of case
	$c1 = mysqli_real_escape_string($conn, $_POST['company1']);//company1 updated
	$c2 = mysqli_real_escape_string($conn, $_POST['company2']);//company2 updated
	//find out the pid by lawyer name
	$find_pid = "SELECT pid FROM personnel WHERE name = '$lawyer_name'";
	$pid=mysqli_query($conn, $find_pid);
	$row=mysqli_fetch_assoc($pid);
	$lawyer_id=$row["pid"];
	//check if this lawyer is qualified
	$qualified="SELECT pers_member_pid FROM legal_cases WHERE c1='$c2' AND pers_member_pid=$lawyer_id";//select the lawyer who descend for that c1 company before and being assigned against c2 now
	//if the violation happened pop a window and exit 
	$check_lawyer=mysqli_query($conn, $qualified);
	$violation=mysqli_num_rows($check_lawyer);//check if there exist violation
	if ($violation >0){
		echo "<script type='text/javascript'>alert('Failed! Violation of Chinese Wall!')</script>";
	}
	else{//there is no violation
		$insert = "INSERT INTO legal_cases(caseid, pers_member_pid, start, complete, c1, c2) VALUES ('$case_id', '$lawyer_id', '$start', 'NULL', '$c1', '$c2')";
	    $sql = mysqli_query($conn, $insert);
		if(!$sql)
		{
			echo "<script type='text/javascript'>alert('CaseiD already exists, cannot overwrite!')</script>";
		}
		else{
			echo "<script type='text/javascript'>alert('Created successfully!')</script>";
		}
	    
		header('Refresh:2;URL=cases.php');
	}
}		  
?>

<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html"  charset="utf-8" />
<title>Cases List</title>
<link rel="stylesheet" href="../style/style.css" type="text/css" media="screen" />
</head>

<div align="center" id="mainWrapper">
  <div id="pageContent"><br />
    <div align="right" style="margin-right:32px;"><a href="cases.php#newCasesForm">+ Add New Cases</a></div>
	<div align="right" style="margin-right:32px;"><a href="admin.php">Go Back To Manu Page</a></div>
<div align="left" style="margin-left:24px;">
      <h2>cases list</h2>
      <?php echo $case_list; ?>
    </div>
    <hr />
    <a name="newCasesForm" id="newCasesForm"></a>
    <h3>
    &darr; Add New Case Form &darr;
    </h3>
    <form action="cases.php" enctype="multipart/form-data" name="myForm" id="myform" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td align="right">Case ID</td>
        <td><label>
          <input name="case_id" type="text" id="case_id" size="5" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Start date</td>
        <td><label>
          <input name="date" type="date_create" id="date" size="12" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Company 1</td>
        <td><label>
          <input name="company1" type="text" id="company1" size="15" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Company 2</td>
        <td><label>
          <input name="company2" type="text" id="company2" size="15" />
        </label></td>
      </tr>
      <tr>
	    <td align="right">Lawyer</td>
        <td>
	    <select name = "lawyer_name" id = "lawyer_name">
	      <?php
     		  $id_auth= "SELECT name FROM personnel WHERE role <> 'ipo'";
//			  AND pid NOT IN (SELECT pers_member_pid FROM legal_cases WHERE c1='$c2')";//select the authorized lawyer who is qualified to take the cases
     	      $sql=mysqli_query($conn, $id_auth); 
              while($row = mysqli_fetch_assoc($sql)){ 
	      ?>
	      <option value="<?php echo $row["name"];?>"><?php echo $row["name"];?></option>
	      <?php
		      }
	      ?>
	    </select>
		</td>
	  </tr>      
      <tr>
        <td>&nbsp;</td>
        <td><label>
          <input type="submit" name="button1" id="button1" value="Create Case" />
        </label></td>
      </tr>
	</table>
    </form>
    <br />
  <br />
  </div>
</div>
</body>
</html>

