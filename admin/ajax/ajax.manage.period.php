<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        if(isset($_POST['CRUD'])){
            function Validator($Conn){
                $status = true;
                
                // Begin validate Username       
                if(empty($_POST['PeriodID']) || empty($_POST['AcceptExpireTime']) || empty($_POST['PeriodStatus'])){
                    $status = false;
                }else{
                    // || date_diff($_POST['PeriodID'], date("Y-m-d") < 0
                    $ExpireTime = $_POST['AcceptExpireTime'].':00';
                    $CurrentTime = date("Y-m-d H:i").":00";
                    //if(date_diff($ExpireTime, $CurrentTime) < 0 || date_diff($_POST['PeriodID'], date("Y-m-d"))){
                    if($ExpireTime < $CurrentTime || $_POST['PeriodID'] < date("Y-m-d")){
                        $status = false;
                    }else{
                        // Check already PeriodID in Create mode
                        if($_POST['CRUD'] == 'Create'){
                            $sql = "SELECT * FROM ".TABLE_PERIOD." WHERE PeriodID = '".$_POST['PeriodID']."' LIMIT 1";
                            $result = $Conn->prepare($sql);
                            $result->execute();
                            if($result->rowCount() < 0){
                                $status = false;
                            }
                        }
                    }    
                }
                return $status;
            }
            if($_POST['CRUD'] == 'Create'){
                if(Validator($Conn) == true){
                    try{
                        $Conn->beginTransaction();
                        $ExpireTime = $_POST['AcceptExpireTime'].':00';
                        $sql = "INSERT INTO ".TABLE_PERIOD." VALUES('".$_POST['PeriodID']."', '".$ExpireTime."', '".$_POST['PeriodStatus']."', 'No', '".$_SESSION['AdminID']."')";
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        
                        $Conn->commit();
                        echo 'true,สำเร็จ บันทึกข้อมูลเสร็จสิ้น';
                    }catch(PDOException $e){
                        $Conn->rollback();
                        echo 'false,ผิดพลาด บันทึกข้อมูลล้มเหลว';
                    }
                }else{
                    echo 'false,ผิดพลาด ข้อมูลไม่ถูกต้อง1';
                }
            }
            if($_POST['CRUD'] == 'Read'){}
            if($_POST['CRUD'] == 'Update'){
                if($_POST['PeriodID'] != ""){
                    if($_POST['AcceptExpireTime'] > date("Y-m-d H:i:s")){
                        $sql = "UPDATE ".TABLE_PERIOD." SET Status = '".$_POST['PeriodStatus']."' WHERE PeriodID = '".$_POST['PeriodID']."' LIMIT 1";
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        echo 'true,สำเร็จ ปรับปรุงข้อมูลเสร็จสิ้น';   
                    }else{
                        echo 'false,ยกเลิกการทำงานโดยระบบ';
                    }
                }else{
                    echo 'false,ผิดพลาด ข้อมูลไม่ถูกต้อง';
                }
            }
            if($_POST['CRUD'] == 'Delete'){
                if($_POST['PeriodID'] != ""){
                    $sql = "SELECT * FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$_POST['PeriodID']."' LIMIT 1";
                    $result = $Conn->prepare($sql);
                    $result->execute();
                    if($result->rowCount() > 0){
                        echo 'false,ยกเลิกการทำงานโดยระบบ';
                    }else{
                        $sql = "DELETE FROM ".TABLE_PERIOD." WHERE PeriodID = '".$_POST['PeriodID']."' LIMIT 1";
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        echo 'true,สำเร็จ ลบข้อมูลเสร็จสิ้น';
                    }
                }else{
                    echo 'false,ผิดพลาด ข้อมูลไม่ถูกต้อง';
                }
            }
        }else{
            echo 'false,ยกเลิกการทำงานโดยระบบ';
        }
    }else{
        echo 'false,ยกเลิกการทำงานโดยระบบ';
    }
}
?>