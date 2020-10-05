<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        $sql = "SELECT * FROM ".TABLE_AGENT." WHERE AgentID = '".$_SESSION['AgentID']."' AND Password = '".md5($_POST['CurrentPassword'])."' LIMIT 1";
        $result = $Conn->prepare($sql);
        $result->execute();
        if($result->rowCount() != 1){
            echo 'false,ผิดพลาด รหัสผ่านปัจจุบันไม่ถูกต้อง';
        }else{
            if(md5($_POST['NewPassword1']) != md5($_POST['NewPassword2'])){
                echo 'false,ผิดพลาด รหัสผ่านชุดใหม่ไม่ตรงกัน';
            }else{
                if($_POST['NewPassword1'] == '' || $_POST['NewPassword2'] == ''){
                    echo 'false,ผิดพลาด รหัสผ่านชุดใหม่ไม่ถูกต้อง';
                }else{
                    $sql = "UPDATE ".TABLE_AGENT." SET Password = '".md5($_POST['NewPassword1'])."' WHERE AgentID = '".$_SESSION['AgentID']."' LIMIT 1";
                    $result = $Conn->prepare($sql);
                    $result->execute();
                    echo 'true,สำเร็จ เปลี่ยนรหัสผ่านเรียบร้อยแล้ว';
                }
            }
        }
    }
}
?>