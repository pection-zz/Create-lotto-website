<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');
if(isset($Conn) && $Conn == true){
    require_once('../../common/assets/includes/tables.php');
    require_once('../assets/class/security.php');
    $Security = new Security();
    if($Security->Authorize($Conn) == true){
    	echo 'authorized';
    }else{
    	echo 'unauthorized';
    }
}
?>