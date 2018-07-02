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
	header("location:lawyer.php");
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Legal office Admin Page</title>
<link href="../style/stylesheet.css" rel="stylesheet" type= "text/css" media="screen"/>
</head>
<!--The following script tag downloads a font from the Adobe Edge Web Fonts server for use within the web page. We recommend that you do not modify it.-->
<script>var __adobewebfontsappname__="dreamweaver"</script>
<script src="http://use.edgefonts.net/abril-fatface:n4,i4:default.js" type="text/javascript"></script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<body>
<div align="center" id = "mainWrapper">
  <div id="pageContent"><h2>List For IPO</h2></div>
  <div class='awesomeText' align = 'left'><b><?php echo $line ?></b></div>
   <div style="margin-left:24px" align="left">
     <p>Office Administration System</p>
     <p><a href="cases.php">Manage Cases</a></p>
     <p><a href="view_lawyer.php">View Lawyer Information</p>
	 <p><a href="view_document.php">View Documents</a></p>
	 <p><a href="logs.php">View Log</a></p>
   </div>
</div>
<label>
     <a href="logout.php"><input type="submit" name="button" value="Log out"/></a>

   </label>
</body>
</html>