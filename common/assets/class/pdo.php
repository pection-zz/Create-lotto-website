<?php
define("DB_TYPE", "MySQL");
define("DB_HOST", "localhost");
define("DB_PORT", "3306");
define("DB_USERNAME", "h514365_THA");
define("DB_PASSWORD", "@06850db0488A");
define("DB_NAME", "h514365_THA");
define("DB_PATH", "");

try{
	switch(DB_TYPE){
		case "MySQL":
			$Conn = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8", DB_USERNAME, DB_PASSWORD);
			break;
		case "Access":
			$Conn = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=".DB_PATH."; Uid=".DB_USERNAME."; Pwd=".DB_PASSWORD.";");
		    break;
		case "PosgreSQL":
			$Conn = new PDO("pgsql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";", DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
		    break;
		case "MongoDB":
			$Conn = new PDO("mongodb://".DB_USERNAME.":".DB_PASSWORD."@".DB_HOST.":".DB_PORT."/".DB_NAME);
		    break;
		case "MSSQL":
			$Conn = new PDO("sqlsrv:Server=".DB_HOST.";Database=".DB_NAME."", DB_USERNAME, DB_PASSWORD);
		    break;
		case "Oracle":
			$Conn = new PDO("oci:host=//".DB_HOST.":port=".DB_PORT."/".DB_NAME.";charset=UTF8", DB_USERNAME, DB_PASSWORD);
		    break;
		default:
			$Conn = "";
			break;
	}
	$Conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	$Conn = false;
}

date_default_timezone_set('Asia/Bangkok');
?>