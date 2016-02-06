<?php
$nsc_config_path = "/var/www/nsc.conf";  //config file path
$nsc_salt_charset = "abcdefghijklmnopqrstuvwxyz0123456789~!@#$%^&*()_+-=";
$nsc_salt_len = 6;

function enable_error_reporting()
{
	error_reporting(E_ALL);
	ini_set("display_errors","1");
	return;
}

function generate_salt($len)
{
	$salt = "";
	$charset_len = strlen($GLOBALS["nsc_salt_charset"]);
	for($i=0;$i<$len;$i++)
	{
		$salt .= $GLOBALS["nsc_salt_charset"][mt_rand(0,$charset_len-1)];
	}
	return $salt;
}

function passwd_encrypt($passwd,$salt)
{
	return md5(md5($passwd.$salt));
}
?>
