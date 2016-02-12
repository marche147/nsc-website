<?php

/*1.参数过滤 2.邮箱验证 3.用户名邮箱格式检查 4.用户名邮箱查重 5.密码复杂度要求 6.验证码可以重复使用的问题*/
require("db_conn.php");
session_start();
if(!isset($_POST["uname"])||!isset($_POST["passwd"])||!isset($_POST["email"]))
{
	// redirect
	header("Location:register_sample.html");
	exit();
}
$uname = $_POST["uname"];
$passwd = $_POST["passwd"];
$email = $_POST["email"];
$captcha = "";
if(isset($_POST["captcha"]))
	$captcha = $_POST["captcha"];
if(strtoupper($captcha) != strtoupper($_SESSION["code"]))
{
	echo "<script language=\"javascript\">alert(\"Captcha wrong!\");self.location=\"register_sample.html\"</script>";
	exit();
}
$res = db_insert_user($uname,$passwd,$email,0);
echo $res;
if($res > 0)
{
	echo "<script language=\"javascript\">alert(\"Register success!\");self.location=\"register_sample.html\"</script>";
	//这里要跳转到login页面
	
	
}
else
{
	echo "<script language=\"javascript\">alert(\"Register failed!\");self.location=\"register_sample.html\"</script>";
}
?>
