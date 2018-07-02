<?php
session_start();
if (!isset($_SESSION["pid"])){
	header("location:login.php");//send to login page
	exit();
}
//you have to have login as a ipo or lawyer other wise we have to send you out
$db_host = "localhost";
// Place the username for the MySQL database here
$db_username = "root";
// Place the password for the MySQL database here
$db_pass = "";
// Place the name for the MySQL database here
$db_name = "legal_office";
$conn= mysqli_connect($db_host, $db_username, $db_pass, $db_name)or die("could not connect to mysql");
?>

<?php//check if the lawyer is logged in legally
$username = $_SESSION["username"];//claim the variable 
$password = $_SESSION["password"];
$position = $_SESSION["position"];
$id_auth = "SELECT role from personnel WHERE name = '$username' AND password = '$password' LIMIT 1";
$sql=mysqli_query($conn, $id_auth); //query the person see if he/she in the username and password
while($row = mysqli_fetch_assoc($sql)){ 
			 $position = $row["role"];
	if ($position!='lawyer'){
		echo 'The user is not recored in the lawyer database';
		exit();
	}
}
?>

<?php
$pid = $_SESSION["pid"];
//checking the case list and view all the cases
$case_list="";
$query="SELECT * FROM legal_cases WHERE pers_member_pid =$pid";//select all the cases
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
	$case_list="The lawyer has no cases yet";
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
<div align="left" style="margin-left:24px;">
      <h2>cases list</h2>
      <?php echo $case_list; ?>
    </div>
    <hr />
    <br />
  <br />
  </div>
  <p align="left"><a href="admin.php">Go back to Main Page</a></p>
</div>
</body>
</html>