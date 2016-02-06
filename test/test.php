<?php
// add test code here
require("db_conn.php");
enable_error_reporting();
//$conn = db_startconn();
//db_closeconn($conn);
db_insertuser("admin","admin",0xffff);
?>
