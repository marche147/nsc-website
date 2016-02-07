<?php
require("db_conn.php");
session_start();
if(!isset($_POST["uname"])||!isset($_POST["passwd"]))
{
	header("Location:login_sample.html");
	exit();
}
$uname = $_POST["uname"];
$passwd = $_POST["passwd"];
$captcha = isset($_POST["captcha"]) ? $_POST["captcha"] : "";
if(strtoupper($captcha) != strtoupper($_SESSION["code"]))
{
	echo "<script language=\"javascript\">alert(\"Captcha wrong!\");self.location=\"login_sample.html\";</script>";
	exit();
}
$info = db_queryuserbyname($uname);
if(empty($info))
{
	echo "<script language=\"javascript\">alert(\"User does not exist!\");self.location=\"login_sample.html\";</script>";
	exit();
}
if(md5(md5($passwd.$info["salt"])) === $info["passwd"])
{
	echo "<script language=\"javascript\">alert(\"Login success!\");self.location=\"login_sample.html\";</script>";
	// TODO: set session here
	exit();
}
else
{
	echo "<script language=\"javascript\">alert(\"Password wrong!\");self.location=\"login_sample.html\";</script>";
	exit();
}
?>
