<?php
session_start();
//you have to have login as a ipo or lawyer other wise we have to send you out
if (isset($_SESSION["username"])){
	$line = 'Username: ' .$_SESSION["username"]. '<br> Position: ' .$_SESSION["position"].'';
}
else{
	header("Location:login.php");
	echo "To view the information please login first!";	
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
$pid = preg_replace('#[0-9]#i','',$_SESSION["pid"]);//the employee id for manager is made of 0-9 only number
$username = $_SESSION["username"];//claim the variable 
$password = $_SESSION["password"];
$position = $_SESSION["position"];
$id_auth = "SELECT role from personnel WHERE name = '$username' AND password = '$password' LIMIT 1";
$sql=mysqli_query($conn, $id_auth); //query the person see if he/she in the username and password
while($row = mysqli_fetch_assoc($sql)){ 
			 $position = $row["role"];
}
if($position != "ipo"){
	echo $_SESSION["pid"];
	header("location:login.php");
}
?>

<?php
/* show tables */
// get results from database
$sql = "SELECT my_log.log_id, my_log.lawyer_id, personnel.name, my_log.message, my_log.log_date FROM my_log INNER JOIN personnel ON my_log.lawyer_id = personnel.pid ORDER BY log_date DESC";
$result = mysqli_query($conn, $sql) or die("select fail");
$all_property = array();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<title>Logs</title>
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
<div class='awesomeText' align = 'left'><b><?php echo $line ?></b></div>
<div align="center" id = "mainWrapper">
  <div id="pageContent"><h2>Logs</h2></div>
  <!-- showing property -->
	<table>
	<thead>

	<?php
	echo '<tr>';
	while($property = mysqli_fetch_field($result))
	{
		echo '<td>' .$property->name. '</td>';
		array_push($all_property, $property->name);
	}
	echo '</tr>';
	?>

	</thead>

	<tbody>
	<?php
	// showing all data
	while($row = mysqli_fetch_array($result))
	{
		echo '<tr>';
		foreach($all_property as $item)
		{
			echo '<td>' . $row[$item] . '</td>';
		}
		echo '</tr>';
	}
	?>
</div>

</tbody>

</table>

<p align="left"><a href="admin.php">Go back to Main Page</a></p>

</body>
</html>