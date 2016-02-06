<?php
require("common.php");

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
	$arr = parse_ini_file($GLOBALS["nsc_config_path"],false);
	$db_host = "";
	$db_user = "";
	$db_passwd = "";
	$db_port = 3306;
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
	}
	//echo "Connection = ".$db_user."@".$db_host." with db = ".$db_database;
	$conn = new mysqli($db_host, $db_user, $db_passwd, $db_database, $db_port);
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

function db_closeconn($conn)
{
	$conn->close();
	return;
}

function db_insertuser($uname,$passwd,$priv)
{
	$sql = "insert into users (uname,passwd,salt,priv,score) values (?,?,?,?,?)";
	$score = 0.0;
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
		db_closeconn($conn);
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
?>
