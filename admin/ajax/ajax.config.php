<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        if($_POST['AcceptNumber'] != 'Yes' && $_POST['AcceptNumber'] != 'No'){
            $_POST['AcceptNumber'] = 'Yes';
        }
        
        if($_POST['Rate2DigiUp'] == '' || !is_numeric($_POST['Rate2DigiUp']) || $_POST['Rate2DigiUp'] < 0){
            $_POST['Rate2DigiUp'] = 0;
        }
        
        if($_POST['Rate2DigiDown'] == '' || !is_numeric($_POST['Rate2DigiDown']) || $_POST['Rate2DigiDown'] < 0){
            $_POST['Rate2DigiDown'] = 0;
        }
        
        if($_POST['Rate3DigiUp'] == '' || !is_numeric($_POST['Rate3DigiUp']) || $_POST['Rate3DigiUp'] < 0){
            $_POST['Rate3DigiUp'] = 0;
        }
        
        if($_POST['Rate3DigiDown'] == '' || !is_numeric($_POST['Rate3DigiDown']) || $_POST['Rate3DigiDown'] < 0){
            $_POST['Rate3DigiDown'] = 0;
        }
    
        try{
            $Conn->beginTransaction();
            $sql[0] = "UPDATE ".TABLE_CONFIGURATION." SET ConfigValue = '".$_POST['AcceptNumber']."' WHERE ConfigName = 'AcceptNumber' LIMIT 1";
            $sql[1] = "UPDATE ".TABLE_RATE." SET Rate = '".$_POST['Rate2DigiUp']."' WHERE RateID = '001' LIMIT 1";
            $sql[2] = "UPDATE ".TABLE_RATE." SET Rate = '".$_POST['Rate2DigiDown']."' WHERE RateID = '002' LIMIT 1";
            $sql[3] = "UPDATE ".TABLE_RATE." SET Rate = '".$_POST['Rate3DigiUp']."' WHERE RateID = '003' LIMIT 1";
            $sql[4] = "UPDATE ".TABLE_RATE." SET Rate = '".$_POST['Rate3DigiDown']."' WHERE RateID = '004' LIMIT 1";
            
            $result[0] = $Conn->prepare($sql[0]);
            $result[0]->execute();
            
            $result[1] = $Conn->prepare($sql[1]);
            $result[1]->execute();
            
            $result[2] = $Conn->prepare($sql[2]);
            $result[2]->execute();
            
            $result[3] = $Conn->prepare($sql[3]);
            $result[3]->execute();
            
            $result[4] = $Conn->prepare($sql[4]);
            $result[4]->execute();
            
            $Conn->commit();
            echo 'true,สำเร็จ บันทึกข้อมูลเสร็จสิ้น';
        }catch(PDOException $e){
            $Conn->rollback();
            echo 'false,ผิดพลาด บันทึกข้อมูลล้มเหลว';
        }
    }
}
?>