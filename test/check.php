<?php
session_start();
if(!isset($_POST["verify_code"]))
{
	die("Invalid input!");
}
if(!isset($_SESSION["code"]))
{
	die("Invalid session!");
}
$verify = $_POST["verify_code"];
?>
<html>
<head>
	<title>Check</title>
</head>
<body>
<?php
if(strtolower($verify) != strtolower($_SESSION["code"]))
{
	print "<script language=\"javascript\">\nalert(\"Wrong!\");</script>";
}
else
{
	print "<script language=\"javascript\">\nalert(\"Correct!\");</script>";
}
?>
</body>
</html>
