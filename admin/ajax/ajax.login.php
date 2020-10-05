<?php
require_once('../../common/assets/includes/session.php');

if(!isset($_SESSION['SecretKey']) || $_SESSION['SecretKey'] == ''){
	echo 'false,ปฎิเสธการใช้งาน';
}else{
    if(!isset($_POST['Username']) || !isset($_POST['Password']) || $_POST['Username'] == '' || $_POST['Password'] == ''){
		echo 'false,ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
	}else{
        require_once('../../common/assets/class/pdo.php');
        if(isset($Conn) && $Conn !== false){
            require_once('../../common/assets/includes/tables.php');
            $sql = "SELECT * FROM ".TABLE_ADMINISTRATOR." WHERE Username = '".$_POST['Username']."' AND Password = '".md5($_POST['Password'])."' AND IsActive = 'Yes' LIMIT 1";
			$result = $Conn->prepare($sql);
	        $result->execute();
	        if($result->rowCount() != 1){
                echo 'false,ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
            }else{
                $data = $result->fetchObject();
	        	if(md5($data->Username) != md5($_POST['Username'])){
	        		echo 'false,ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
	        	}else{
                    require_once('../../common/assets/class/client.php');
                    $Client = new Client();
    				$OSName = $Client->OSName($_SERVER['HTTP_USER_AGENT']);
    				$Browser = $Client->BrowserName($_SERVER['HTTP_USER_AGENT']);
    				$IPAddress = $Client->IP();
	            	$_SESSION['IsLock'] = 'No';
                    $_SESSION['AdminID'] = $data->AdminID;
                    $Chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    $_SESSION['AuthorizeKey'] = md5(substr(str_shuffle($Chars),0,6));
                    $sql = "UPDATE ".TABLE_ADMINISTRATOR." SET AuthorizeKey = '".$_SESSION['AuthorizeKey']."', SessionID = '".session_id()."', SessionStart = '".date("Y-m-d H:i:s")."' WHERE AdminID = '".$_SESSION['AdminID']."' AND IsActive = 'Yes' LIMIT 1";
                    $result = $Conn->prepare($sql);
                    $result->execute();
                    require_once('../../common/assets/class/logs.php');
                    $Logs = new Logs();
                    $LogsMessage = 'Admin,'.$_SESSION['AdminID'].','.date("Y-m-d H:i:s").','.session_id().','.$IPAddress.','.$OSName.','.$Browser;
                    $Logs->Write("../logs/admin-login/", "LogIn", $LogsMessage);
                    $Logs->Write("../logs/admin-login/".$_SESSION['AdminID']."/", "LogIn", $LogsMessage);
                    echo 'true,ดำเนินการเรียบร้อยแล้ว โปรดรอ...';
                }
            }
        }
    }
}
?>