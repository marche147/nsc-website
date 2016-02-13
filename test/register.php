<?php

/*1.参数过滤 2.邮箱验证 3.用户名邮箱格式检查 4.用户名邮箱查重 5.密码复杂度要求 6.验证码可以重复使用的问题*/
require("db_conn.php");
session_start();
$email_pattern = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";   //邮箱正则表达式
$uname_pattern="/^[a-z0-9_\x{4e00}-\x{9fa5}]{1,16}$/ui";                                     //用户名正则表达式
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


//验证码判断
if(strtoupper($captcha) != strtoupper($_SESSION["code"]))
{
	echo "<script language=\"javascript\">alert(\"Captcha wrong!\");history.back();</script>";
	exit();
}

//邮箱规则判断
else if (!preg_match_all( $email_pattern, strtolower($email) ) )
{
	echo "<script language=\"javascript\">alert(\"Your email is illegal!\");history.back();</script>";
	exit();
}

//用户名规则判断
//待测$text必须是utf-8的格式！用户名只能包括中文，英文字母，数字，下划线 ( _ ) 不能含空格6-16位
else if(!preg_match($uname_pattern,strtolower($uname)))
{
	echo "<script language=\"javascript\">alert(\"Your name is illegal!\");history.back();</script>";
	exit();
}
//用户名重复判断
else if(db_query_user_by_name($uname))
{
	echo "<script language=\"javascript\">alert(\"The name had been used!\");history.back();</script>";
	exit();
}
//邮箱重复判断
else if(db_query_user_by_email($email))
{
	echo "<script language=\"javascript\">alert(\"The email had been used!\");history.back();</script>";
	exit();
}

//没有错误则执行插入操作
else
{
	$res = db_insert_user($uname,$passwd,$email,0);
	//echo $res;
	if($res > 0)
	{
		echo "<script language=\"javascript\">alert(\"Register success!\");</script>";
		//这里要跳转到login页面

	}
	else
	{
		echo "<script language=\"javascript\">alert(\"Register failed!\");history.back();</script>";
	}
}

?>
