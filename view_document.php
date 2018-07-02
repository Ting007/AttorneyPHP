<?php
session_start();

if (isset($_SESSION["username"])){
	$line = 'Username: ' .$_SESSION["username"]. '<br> Position: ' .$_SESSION["position"].'';
}
else{
	header("Location:index.php");
	echo "To view the information please login first!";	
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
$conn = mysqli_connect($db_host, $db_username, $db_pass, $db_name)or die("could not connect to mysql");
?>

<?php
//grabs the whole list for viewing
$company_list = "";
$companies = "SELECT DISTINCT c1 FROM legal_cases ORDER BY c1";
$sql = mysqli_query($conn, $companies);
$companyCount = mysqli_num_rows($sql);//count the output amount
if ($companyCount>0){
	while ($row = mysqli_fetch_array($sql)){
		$cname = $row["c1"];
		
		$company_list.= '<table width="100%" border="0" cellsspacing="0" cellpadding="6">
	<tr>
		<td width="17%" valign="top" align = "center"> </td>
		<td width="100%" valign="center">Company Name: &nbsp;' . $cname . '</br>
		<a href="view_document.php?id= ' . $cname . '">View Company Documents</a></td>
	</tr>
</table>';
		}
}
else {
	$company_list = "You have no company listed in your attorney yet";
}
?>

<?php
// Check to see the URL variable is set and that it exists in the database
$output_info = "";
if (isset($_GET['id'])) {
	$company_name = preg_replace('/[^A-Za-z0-9]/', '', $_GET['id']);
	
	// check if the lawyer is allowed to access the document of company_name
	$query_invalid_cases = "SELECT DISTINCT caseid FROM legal_cases WHERE c2 = '$company_name' AND pers_member_pid = '" .$_SESSION['pid']. "' AND ( complete = '0000-00-00' OR complete is NULL)"; 
	
	$invalid_cases = mysqli_query($conn, $query_invalid_cases);
	
	$existCount = mysqli_num_rows($invalid_cases);
	
	if($existCount > 0){
		$output_info = '<span style="color:red"> &nbsp &nbsp &nbsp You cannot access the document of Company '.$company_name. ' because of conflict of interest </span>'; 
		// send this violation to my_log table so as to inform ipo about this invalid access
		$query_pname = "SELECT name FROM personnel WHERE pid = '" .$_SESSION['pid']. "'";
		$pname = mysqli_query($conn, $query_pname);
		$row = mysqli_fetch_array($pname);
		$lawyer_name = $row['name'];
		
		$alert_text   = 'The lawyer searched the document of Company  ' .$company_name. '  which is the opponent of his/her current case';
		$lawyer_id = $_SESSION['pid'];
		date_default_timezone_set("America/New_York");
		$time_stamp = (string)date("Y-m-d G:i:sa");
		$insert_alert = "INSERT INTO my_log(lawyer_id, message , log_date) VALUES ('$lawyer_id', '$alert_text', '$time_stamp')";
		$sql = mysqli_query($conn, $insert_alert);
	}
	else // search document
	{
		$output_info .= '<h3>The documents for company ' .$company_name. ':</h3>';
		
		$query_documents = "SELECT caseid, doc_name, doc_content FROM doc_block WHERE company_name = '$company_name' ORDER BY caseid";
		$documents = mysqli_query($conn, $query_documents);
		
		$docCount = mysqli_num_rows($documents);
		if($docCount > 0){
			while ($row = mysqli_fetch_array($documents)){
				$caseid = $row['caseid'];
				$doc_name = $row['doc_name'];
				$doc_content = $row['doc_content'];
				
				$output_info .= '<h4>The document collected in case ' .$caseid. ' is:</h4> Document name: ' .$doc_name. ' '.$caseid.'<br> The content is : ' .$doc_content. '';
			}
		}
		else{
			$output_info .= 'There is no documents collected right now!</br>';
		}
			
	}

}


?>


<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Attorney View Documents</title>
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
</style>

</head>
<body>
<div id="first" align="left" style="margin-left:80px;">
 	<h2>Company List</h2>
 	<?php echo $company_list;?>
	<div class='awesomeText' align = 'left'><b><?php echo $line ?></b></div>
	<p align="left"><a href="admin.php">Go back to Main Page</a></p>
    </div>
<div id = "line"> </div>
<div id="second" align = "left" style = "margin-left:80px;">
	<h2>Output</h2>
	<?php echo $output_info;?>
	
</div>


</body>
</html>