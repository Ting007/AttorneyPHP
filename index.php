<?php
session_start();

if (isset($_SESSION["username"])){
	$line = 'Welcome to our attorney! <br> <br> Username: ' .$_SESSION["username"]. '<br> Position: ' .$_SESSION["position"]. '<br>';
	echo "<div class='awesomeText' align = 'right'>$line</div>";
}
else{
	$line = '<h2>To view the information please login first!</h2>';
}

?>



<?php
//conncet to the database 
$db_host = "localhost";
// Place the username for the MySQL database here
$db_username = "root";
// Place the password for the MySQL database here
$db_pass = "";
// Place the name for the MySQL database here
$db_name = "legal_office";
$conn= mysqli_connect($db_host, $db_username, $db_pass, $db_name)or die("could not connect to mysql");


//grabs the whole list for viewing
$lawyer_list = "";
$lawyers= "SELECT * FROM personnel WHERE role = 'lawyer' ORDER BY name";
$sql = mysqli_query($conn, $lawyers);
$personCount = mysqli_num_rows($sql);//count the output amount
if ($personCount>0){
	while ($row = mysqli_fetch_array($sql)){
		$pid = $row["pid"];
		$pname = $row["name"];
		
		$lawyer_list.= '<table width="100%" border="0" cellsspacing="0" cellpadding="6">
	<tr>
		<td width="17%" valign="top" align = "center"><a href ="lawyer.php?id= ' . $pid . '"></a></td>
		<td width="100%" valign="center">' . $pname . '<br/>
		lawyer ID: &nbsp;' . $pid . '</br>
		<a href="lawyer_info.php?id= ' . $pid . '">View lawyer Details</a></td>
	</tr>
</table>';
		}
}
else {
	$lawyer_list = "You have no lawyer listed in your attorney yet";
}

?>

<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Attorney Home Page</title>
<link href="/style/stylesheet.css" rel="stylesheet" type= "text/css" media="screen"/>

<!--The following script tag downloads a font from the Adobe Edge Web Fonts server for use within the web page. We recommend that you do not modify it.-->
<script>var __adobewebfontsappname__="dreamweaver"</script>
<script src="http://use.edgefonts.net/abril-fatface:n4,i4:default.js" type="text/javascript"></script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<style>

</style>

</head>
<body>
<div>
<?php echo $line;?>
</div>
<div align = "center" id="pageHeader" class="catnavigation" ><img src="http://localhost/attorney/logo_image/logo.jpg" width="300" height="200" alt=""/><img src="http://localhost/attorney/logo_image/lawyer.png" width="200" height="160" alt=""/>
</div>


<div style="width: 100%; overflow: hidden;">
    <div style="width: 60%; float: left; margin-left:80px;" align ="left;"> 
	<h2>Lawyers List</h2>
 	<?php echo $lawyer_list;?>
	</div>
    <div align = "right;"> 
		<div align = "right" class="catnavigation"><a href="http://localhost/attorney/login.php"><img src="http://localhost/attorney/logo_image/login-button.png" width="120" height="100" /></a>
		</div>
		<div align = "right" class="catnavigation"><a href=http://localhost/attorney/logout.php><img src="http://localhost/attorney/logo_image/logout.jpg" width="110" height="40" /> </a> 
		</div>
	</div>
</div>

	
</body>




	
</html>
