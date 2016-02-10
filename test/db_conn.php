<?php
require("common.php");
require("./config/db.conf.php");
echo $db_host;
//enable_error_reporting();
/*
 * User table :
+--------+-------------+------+-----+---------+----------------+
| Field  | Type        | Null | Key | Default | Extra          |
+--------+-------------+------+-----+---------+----------------+
| uid    | int(4)      | NO   | PRI | NULL    | auto_increment |
| uname  | varchar(40) | YES  | UNI | NULL    |                |
| passwd | varchar(40) | YES  |     | NULL    |                |
| salt   | varchar(40) | YES  |     | NULL    |                |
| priv   | int(10)     | YES  |     | NULL    |                |
| score  | float       | YES  |     | NULL    |                |
+--------+-------------+------+-----+---------+----------------+
 */

function db_startconn()
{
	/*$arr = parse_ini_file($GLOBALS["nsc_config_path"]."db.conf.php",false);
	$db_host = "";
	$db_user = "";
	$db_passwd = "";
	$db_port = "";
	$db_database = "";
	foreach($arr as $key => $value)
	{
		if($key == "db_host")
			$db_host = $value;
		else if($key == "db_user")
			$db_user = $value;
		else if($key == "db_passwd")
			$db_passwd = $value;
		else if($key == "db_port")
			$db_port = (int)$value;
		else if($key == "db_database")
			$db_database = $value;
		//$$key=$value;
	}
	*/
	//echo "Connection = ".$db_user."@".$db_host." with db = ".$db_database;
	$conn = new mysqli($GLOBALS["db_host"], $GLOBALS["db_user"], $GLOBALS["db_passwd"], $GLOBALS["db_database"], $GLOBALS["db_port"]);
	if($conn->connect_errno) {
		// error handling
		if(isset($GLOBALS["nsc_err_dbg"])&&$GLOBALS["nsc_err_dbg"] == true)
		{
			echo "Cannot connect to ".$db_user."@".$db_host;
		}
		return;
	}
	//echo $conn->host_info."\n";
	// set to utf8
	$conn->query("set names utf8;");
	return $conn;
}

function db_close_conn($conn)
{
	$conn->close();
	return;
}

function execute_sql($query,$is_output){   //用于测试和内部管理功能，不需要函数过滤
	$conn = db_startconn();
	if(!$conn)
	{
		// error handling
		return -1;
	}
	$result=$conn->query($query);
	if($is_output){
			if(isset($result)){
				echo "success!<br>";
				$row_b=array();
    			while($row=@mysql_fetch_row($result)){
					$count=count($row);
					for($i=0;$i<($count);$i++){
						echo $row[$i];
						echo " ";			
					}
				echo "<br>";
				}
			}else
			{
			echo "failed!<br>";
			}
	}
	$conn->close();
	return $row;
}



function db_insert_user($uname,$passwd,$priv)
{	
	//get current user num
	$result=execute_sql("select user_number from db_status;",1);
	echo $result;
	$sql = "insert into users (uname,passwd,salt,priv,score) values (?,?,?,?,?);";	
	$score = 0;
	$salt = "";
	$enc_passwd = "";
	$conn = db_startconn();
	if(!$conn)
	{
		// error handling
		return -1;
	}
	// make passwd
	$salt = generate_salt($GLOBALS["nsc_salt_len"]);
	$enc_passwd = passwd_encrypt($passwd,$salt);
	// using prepared statement to prevent sql injection
	if(!($stmt = $conn->prepare($sql)))
	{
		// error handling
		db_close_conn($conn);
		return -2;
	}
	// bind parameters
	if(!($stmt->bind_param("sssid",$uname,$enc_passwd,$salt,$priv,$score)))
	{
		// error handling
		db_closeconn($conn);
		$stmt->close();
		return -2;
	}
	if(!($stmt->execute()))
	{
		// error handling
		db_closeconn($conn);
		$stmt->close();
		return -3;
	}
	$stmt->close();
	db_closeconn($conn);
	return 1;
}

function db_query_user_by_name($uname)
{
	$sql = "select uid,passwd,salt,priv,score from users where uname = ?";
	$conn = db_startconn();
	$uid = 0;
	$passwd = "";
	$salt = "";
	$priv = 0;
	$score = 0.0;
	$res = array();
	if(!$conn)
	{
		// error handling
		return;
	}
	if(!($stmt = $conn->prepare($sql)))
	{
		// error handling
		db_closeconn($conn);
		return;
	}
	if(!($stmt->bind_param("s",$uname)))
	{
		// error handling
		goto cleanup;
	}
	if(!($stmt->bind_result($uid,$passwd,$salt,$priv,$score)))
	{
		// error handling
		goto cleanup;
	}
	if(!($stmt->execute()))
	{
		// error handling
		goto cleanup;
	}
	if(!($stmt->fetch()))
	{
		goto cleanup;
	}
	$res["uid"] = $uid;
	$res["uname"] = $uname;
	$res["passwd"] = $passwd;
	$res["salt"] = $salt;
	$res["priv"] = $priv;
	$res["score"] = $score;
	$stmt->free_result();
cleanup:
	$stmt->close();
	db_closeconn($conn);
	return $res;
}
?>
