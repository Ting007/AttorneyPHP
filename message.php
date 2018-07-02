<?php
session_start();
//you have to have login as a ipo or lawyer other wise we have to send you out
if (!isset($_SESSION["pid"])){//get the pid of sender
	header("location:login.php");//send to login page
	exit();
}
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
// Check to see the URL variable is set and that it exists in the database
//SELECT pers_member_id FROM legal_cases WHERE pers_member_id = $receiver_pid AND c1 IN (SELECT c2 FROM legal_cases WHERE pers_member_id = $send_pid)
$sender_id = $_SESSION["pid"];
if (isset($_GET['id'])) {
	$receiver_pid = preg_replace('#[^0-9]#i', '', $_GET['id']); //this pid is the receiver 
	
	$check="SELECT pers_member_pid FROM legal_cases WHERE pers_member_pid = '$receiver_pid' AND c1 IN (SELECT c2 FROM legal_cases WHERE pers_member_pid='$sender_id' AND complete=0000-00-00)";
	$check_lawyer=mysqli_query($conn, $check);
	$violation=mysqli_num_rows($check_lawyer);//check if there exist violation
	if ($violation >0){
		header('Refresh:0;URL=view_lawyer.php');
		echo "<script type='text/javascript'>alert('Failed! Violation of Chinese Wall! Please choose another lawyer!')</script>";
	}else{
	    $query_lawyers_info = "SELECT pid,name,role,email,phone FROM personnel WHERE pid = '$receiver_pid'"; 
	
	    $lawyers_info = mysqli_query($conn, $query_lawyers_info)or die("select fail");
	}
}


?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<title>LawyerInfo</title>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 75%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(odd) {
    background-color: #dddddd;
}

</style>
</head>
<body>
<div align="center" id = "mainWrapper">
  <div id="pageContent">LawyerInfo</div>
  <!-- showing property -->
	<table>
	<thead>


	<tr>
	<td> LawyerID </td> <td> Name </td> <td> Role </td> <td> Email </td> <td> Phone </td>
	</tr>


	</thead>

	<tbody>
	<?php
	// showing all data
	if($row = mysqli_fetch_array($lawyers_info))
	{
		echo '<tr>';
	
		echo '<td>' . $row["pid"] . '</td> <td>' . $row["name"] . '</td> <td>' . $row["role"] . '</td> <td>' . $row["email"] . '</td> <td>' . $row["phone"] . '</td>';
	
		echo '</tr>';
	}
	?>
	</tbody>

	</table>
    </br>
</div>
<div align="right" style="margin-right:32px;"><a href="lawyer.php">Go Back To Main Page</a></div>
<div align="center" id = "mainWrapper">
  <div id="pageContent">Enter text here...</div><br/>
   <div style="margin-left:24px" align="left">
   <form align="center" id = "form 1" name = "form 1" method="post">
     <input name = "message" type="text" id = "message" style="font-size:10pt; width:200px;height:100px"/>
     <br/><br/>
   <label>
     <input type='submit' name="button" value="Send" />
   </label>
   </form>
     <p>&nbsp; </p>
   </div>
   <br/>
   <br/>
   <br/>
</div>

</body>
</html>