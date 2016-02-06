<?php
session_start();
include('verify_code.php');
$code = new VerifyCode();
$_SESSION['code'] = $code->getCode();
$code->output();
?>
