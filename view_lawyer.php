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
		<a href="view_lawyer.php?id= ' . $pid . '">Send Message</a></td>
	</tr>
</table>';
		}
}
else {
	$lawyer_list = "You have no lawyer listed in your attorney yet";
}
?>

<?php
$output_info = "";
if (isset($_GET['id'])) {
	$receiver_pid = preg_replace('#[^0-9]#i', '', $_GET['id']); //this pid is the receiver 
	
	$check="SELECT pers_member_pid FROM legal_cases WHERE pers_member_pid = '$receiver_pid' AND c1 IN (SELECT c2 FROM legal_cases WHERE pers_member_pid='" .$_SESSION["pid"]. "' AND (complete=0000-00-00 OR complete is NULL))";
	$check_lawyer=mysqli_query($conn, $check);
	$violation=mysqli_num_rows($check_lawyer);//check if there exist violation
	if ($violation >0){
		$output_info = '<br><br><br><br><br><span style="color:red"> &nbsp &nbsp &nbsp  Failed! Violation of Chinese Wall! You cannot contact this lawyer! </span>';
		
		// insert into log
		$query_pname = "SELECT name FROM personnel WHERE pid = '" .$_SESSION['pid']. "'";
		$pname = mysqli_query($conn, $query_pname);
		$row = mysqli_fetch_array($pname);
		$lawyer_name = $row['name'];
		
		$alert_text   = 'The lawyer tried to contact the lawyer with id:  ' .$receiver_pid. '  who has represented the opponent of his/her current case';
		$lawyer_id = $_SESSION['pid'];
		date_default_timezone_set("America/New_York");
		$time_stamp = (string)date("Y-m-d G:i:s");
		$insert_alert = "INSERT INTO my_log(lawyer_id, message , log_date) VALUES ('$lawyer_id', '$alert_text', '$time_stamp')";
		$sql = mysqli_query($conn, $insert_alert);
		
	}else{
	    $query_lawyers_info = "SELECT pid,name,role,email,phone FROM personnel WHERE pid = '$receiver_pid'"; 
	
	    $lawyers_info = mysqli_query($conn, $query_lawyers_info)or die("select fail");
		
		$row = mysqli_fetch_array($lawyers_info);
		$row_pid = $row['pid'];
		$row_name = $row['name'];
		$row_role = $row['role'];
		$row_email = $row['email'];
		$row_phone = $row['phone'];
		
		$output_info .= '<br><br><br><br><br>';
		$output_info .= '<table id = "table" > ';
		$output_info .= '<thead> <tr id = "tr"> <td = "td"> LawyerID </td> <td> Name </td> <td> Role </td> <td> Email </td> <td> Phone </td> </tr> </thead>';
		$output_info .= '<tbody> <tr> <td>' .$row_pid. '</td> <td> ' .$row_name. '</td> <td>' .$row_role. '</td> <td>' . $row_email . '</td> <td>' . $row_phone . '</td> </tr> </tbody>';
		$output_info .= '</table>';
		
		$output_info .= '<br><br><br><div id = "pageContent" align = "center" >Enter text here...</div><br/>';
		$output_info .= '<div style = "margin-left:24px">';
		$output_info .= '<form align = "center" id = "form 1" name = "form 1" method="post">';
		$output_info .= '<input name = "message" type="text" id = "message" style="font-size:10pt; width:200px;height:100px"/>';
		$output_info .= '<br/><br/>';
		$output_info .= '<label> <input type="submit" name="button" value="Send" /> </label>';
		$output_info .= '</form> <p>&nbsp; </p> </div>';
		
	}
}
 
?>

<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Attorney Communication</title>
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
#wrapper {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden; /* will contain if #first is longer than #second */
}
#first {
    width: 60%;
    float:left; /* add this */
}
#line {content: "";
  background-color: #000;
  position: absolute;
  width: 2px;
  height: 85%;
  top: 20px;
  left: 60%;
  display: block;
}
#second {
    overflow: hidden; /* if you don't want #second to wrap below #first */
}


#table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 75%;
}

#td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

#tr:nth-child(odd) {
    background-color: #dddddd;
}
</style>

</head>

<body>
<div id="first" align="left" style="margin-left:80px;">
 	<h2>Lawyers List</h2>
 	<?php echo $lawyer_list;?>
	<div class='awesomeText' align = 'left'><b><?php echo $line ?></b></div>
	<p align="left"><a href="admin.php">Go back to Main Page</a></p>
    </div>
<div id="second" align = "left" style = "margin-left:80px;">
	<?php echo $output_info;?>
	
</div>


</body>

</html>
