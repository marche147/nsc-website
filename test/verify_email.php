<?php
require("db_conn.php");
require("safe_sql.php");//一种是用一个单独的函数对于所有数据库访问进行过滤，或者在db_conn中进行过滤；
if(!isset($_REQUEST["code"])||!isset($_REQUEST["email"])){
	exit();
}
$code=$_REQUEST["code"];
$email=$_REQUEST["email"];

if(db_query_user_by_email($email)["verified_code"]===$_REQUEST["code"]){

	execute_sql('update user set is_verified=1 where email="'.$email.'";',0);
	echo "success!";
}else{
	echo "<script language=\"javascript\">alert(\"Verify code wrong!\");history.back();</script>";
	exit();
}


?>