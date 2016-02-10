<?php
require("db_conn.php");
session_start();
if(!isset($_POST["uname"])||!isset($_POST["passwd"]))
{
	// redirect
	header("Location:register_sample.html");
	exit();
}
$uname = $_POST["uname"];
$passwd = $_POST["passwd"];
$captcha = "";
if(isset($_POST["captcha"]))
	$captcha = $_POST["captcha"];
if(strtoupper($captcha) != strtoupper($_SESSION["code"]))
{
	echo "<script language=\"javascript\">alert(\"Captcha wrong!\");self.location=\"register_sample.html\"</script>";
	exit();
}
$res = db_insert_user($uname,$passwd,0);
if($res > 0)
{
	echo "<script language=\"javascript\">alert(\"Register success!\");self.location=\"register_sample.html\"</script>";
}
else
{
	echo "<script language=\"javascript\">alert(\"Register failed!\");self.location=\"register_sample.html\"</script>";
}
?>
