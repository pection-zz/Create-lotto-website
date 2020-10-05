<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        header('Content-type: application/json');
        $valid = false;
        $sql = "SELECT * FROM ".TABLE_AGENT." WHERE";
        if($_POST['CRUD'] == "Create"){
            if(isset($_POST['Username'])){
                 $sql .= " Username = '".$_POST['Username']."' LIMIT 1";
            }
            if(isset($_POST['Email1'])){
                 $sql .= " Email = '".$_POST['Email1']."' LIMIT 1";
            }
            if(isset($_POST['Email2'])){
                 $sql .= " Email = '".$_POST['Email2']."' LIMIT 1";
            }
        }
        if($_POST['CRUD'] == "Update"){
            $sql .= " Email = '".$_POST['Email']."' AND AgentID != '".$_POST['AgentID']."' LIMIT 1";
        }
        
        $result = $Conn->prepare($sql);
        $result->execute();
        if($result->rowCount() > 0){
            $valid = false;
        }else{
            $valid = true;
        }
        echo json_encode(array(
            'valid' => $valid,
        ));
    }
}
?>