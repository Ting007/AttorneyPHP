<?php
session_start();
//you have to have login as a ipo or lawyer other wise we have to send you out
if (!isset($_SESSION["pid"])){
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
if (isset($_GET['id'])) {
	$pid = preg_replace('#[^0-9]#i', '', $_GET['id']); 
	
	$query_lawyers_info = "SELECT pid,name,role,email,phone FROM personnel WHERE pid = '$pid'"; 
	
	$lawyers_info = mysqli_query($conn, $query_lawyers_info)or die("select fail");
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
	while($row = mysqli_fetch_array($lawyers_info))
	{
		echo '<tr>';
	
		echo '<td>' . $row["pid"] . '</td> <td>' . $row["name"] . '</td> <td>' . $row["role"] . '</td> <td>' . $row["email"] . '</td> <td>' . $row["phone"] . '</td>';
	
		echo '</tr>';
	}
	?>
	</tbody>

	</table>
  
</div>



</body>
</html>