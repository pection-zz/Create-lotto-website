<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');
if(isset($Conn) && $Conn == true){
    require_once('../../common/assets/includes/tables.php');
    require_once('../assets/class/security.php');
    $Security = new Security();
    if($Security->Authorize($Conn) == true){
        $sql = "DELETE FROM ".TABLE_ONLINE." WHERE UserID = '".$_SESSION['AdminID']."' AND AccessType = 'Admin' AND SessionID = '".session_id()."' LIMIT 1";
        $result = $Conn->prepare($sql);
        $result->execute();

        $sql = "UPDATE ".TABLE_ADMINISTRATOR." SET AuthorizeKey = '', SessionID = '' WHERE AdminID = '".$_SESSION['AdminID']."' AND IsActive = 'Yes' LIMIT 1";
        $result = $Conn->prepare($sql);
        $result->execute();
        $_SESSION['AdminID'] = '';
        $_SESSION['AuthorizeKey'] = '';
        
    	echo 'true,ดำเนินการเรียบร้อยแล้ว';
    }else{
    	echo 'false,ปฎิเสธการเข้าถึง';
    }
}
?>