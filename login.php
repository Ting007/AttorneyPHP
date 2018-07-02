<?php
session_start();
	//conncet to the database 
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
if (isset($_POST["username"]) && isset($_POST["password"])) {
	$username = preg_replace('#[^A-Za-z0-9]#i', '', $_POST["username"]); // filter everything but numbers and letters
    $password = preg_replace('#[^A-Za-z0-9]#i', '', $_POST["password"]); // filter everything but numbers and letters
	$query_users= "SELECT pid, role FROM personnel WHERE name = '$username' AND password = '$password' LIMIT 1"; 
	$users=mysqli_query($conn, $query_users);
	$existCount=mysqli_num_rows($users);
	echo $existCount;
	if($existCount == 1){
		while($row = mysqli_fetch_assoc($users)){ 
             $pid = $row["pid"];
			 echo $pid;
			 $position = $row["role"];
			 echo $position;
		 }
		 $_SESSION["pid"] = $pid;
		 $_SESSION["username"] = $username;
		 $_SESSION["password"] = $password;
		 $_SESSION["position"] = $position;
		 header("location:admin.php");
		 exit();
	}
	else
	{
		echo 'The username and password is incorrect. Try again.<a href="login.php">Click Here</a>';
		
	}
}
?>
<!--The following script tag downloads a font from the Adobe Edge Web Fonts server for use within the web page. We recommend that you do not modify it.-->
<script>var __adobewebfontsappname__="dreamweaver"</script>
<script src="http://use.edgefonts.net/abril-fatface:n4,i4:default.js" type="text/javascript"></script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin log in</title>
</head>
<body>
<div align="center" id = "mainWrapper">
  <div id="pageContent">Login</div>
  <div id="pageContent">Please enter the username and password:</div><br/><br/>
   <div style="margin-left:24px" align="left">
   
   <form align="center" id = "form 1" name = "form 1" method="post">
   User name:<br/>
     <input name = "username" type="text" id = "username" size="40"/>
     <br/><br/>
   Password: <br/>
     <input name = "password" type="password" id = "password" size="40"/>
     <br/><br/>
   <label>
     <input type='submit' name="button" value="Log in" />
   </label>
   </form>
     <p>&nbsp; </p>
   </div>
   <br/>
   <br/>
   <br/>
</div>
</div>   
</body>
</html>