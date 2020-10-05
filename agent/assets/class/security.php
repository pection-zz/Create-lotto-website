<?php
class Security{
	function Authorize($Conn){
        if(isset($_SESSION['AuthorizeKey']) && isset($_SESSION['AgentID']) && $_SESSION['AuthorizeKey'] != '' && $_SESSION['AgentID'] != ''){
            $sql = "SELECT * FROM ".TABLE_AGENT." WHERE AgentID = '".$_SESSION['AgentID']."' AND SessionID = '".session_id()."' AND AuthorizeKey = '".$_SESSION['AuthorizeKey']."' AND IsActive = 'Yes' LIMIT 1";
            $result = $Conn->prepare($sql);
            $result->execute();
            if($result->rowCount() == 1){
                return true;
            }else{
                $_SESSION['SecretKey'] = null;
                $_SESSION['AuthorizeKey'] = null;
                $_SESSION['AgentID'] = null;
                $_SESSION['IsLock'] = null;
                session_destroy();
                return false;
            }
        }else{
            return false;
        }
	}

	function UserOnline($Conn){
        $URL = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $sql = "SELECT * FROM ".TABLE_ONLINE." WHERE UserID = '".$_SESSION['AgentID']."' AND AccessType = 'Agent' LIMIT 1";
        $result = $Conn->prepare($sql);
        $result->execute();
        if($result->rowCount() != 0){
            $sql = "UPDATE ".TABLE_ONLINE." SET OnlineTime = '".time()."', Location = '".$URL."' WHERE SessionID = '".session_id()."' AND UserID = '".$_SESSION['AgentID']."' AND AccessType = 'Agent' LIMIT 1";
        }else{
            $sql = "INSERT INTO ".TABLE_ONLINE." VALUES ('".$_SESSION['AgentID']."', 'Agent', '".session_id()."', '".time()."', '".$URL."')";
        }
        $result = $Conn->prepare($sql);
        $result->execute();
        
        $TimeCheck = time() - 900;
        $sql = "DELETE FROM ".TABLE_ONLINE." WHERE OnlineTime < '".$TimeCheck."'";
        $result = $Conn->prepare($sql);
        $result->execute();
	}
}
?>