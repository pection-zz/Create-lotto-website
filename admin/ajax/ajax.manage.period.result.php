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
                
                if(empty($_POST['PeriodID'])){
                    $status = false;
                }
                   
                if(empty($_POST['Digi2Up']) || !is_numeric($_POST['Digi2Up'])){
                    $status = false;
                }
                   
                if(empty($_POST['Digi2Down']) || !is_numeric($_POST['Digi2Down'])){
                    $status = false;
                }
                   
                if(empty($_POST['Digi3']) || !is_numeric($_POST['Digi3'])){
                    $status = false;
                }
                
                if($_POST['CRUD'] == "Create"){
                    if($status == true){
                        $sql = "SELECT * FROM ".TABLE_RESULTS." WHERE PeriodID = '".$_POST['PeriodID']."' LIMIT 1";
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        if($result->rowCount() > 0){
                            $status = false;
                        }
                    }
                }
                
                if($status == true){
                    $sql = "SELECT * FROM ".TABLE_PERIOD." WHERE PeriodID = '".$_POST['PeriodID']."' AND AcceptExpireTime < '".date("Y-m-d H:i:s")."' AND Status = 'Close' LIMIT 1";
                    $result = $Conn->prepare($sql);
                    $result->execute();
                    if($result->rowCount() != 1){
                        $status = false;
                    }
                }
                $TempDigi3 = substr($_POST['Digi3'], 1);
                if($_POST['Digi2Up'] != $TempDigi3){
                    $status = false;
                }
                
                return $status;
            }
            if($_POST['CRUD'] == 'Create'){
                if(Validator($Conn) == true){
                    try{
                        $Conn->beginTransaction();
                        $sql[0] = "INSERT INTO ".TABLE_RESULTS." VALUES('".$_POST['PeriodID']."', '".date("Y-m-d H:i:s")."', '".$_POST['Digi2Up']."', '".$_POST['Digi2Down']."', '".$_POST['Digi3']."', '".$_SESSION['AdminID']."')";
                        $result[0] = $Conn->prepare($sql[0]);
                        $result[0]->execute();
                        
                        $sql[1] = "UPDATE ".TABLE_PERIOD." SET IsResults = 'Yes' WHERE PeriodID = '".$_POST['PeriodID']."' AND Status = 'Close' LIMIT 1";
                        $result[1] = $Conn->prepare($sql[1]);
                        $result[1]->execute();
                        $Conn->commit();
                        echo 'true,สำเร็จ บันทึกข้อมูลเสร็จสิ้น';
                    }catch(PDOException $e){
                        $Conn->rollback();
                        echo 'false,ผิดพลาด บันทึกข้อมูลล้มเหลว';
                    }
                }else{
                    echo 'false,ผิดพลาด ข้อมูลไม่ถูกต้อง';
                }
            }
            if($_POST['CRUD'] == 'Read'){}
            if($_POST['CRUD'] == 'Update'){
                if(Validator($Conn) == true){
                    if($_POST['PeriodID'] != ""){
                        $sql = "UPDATE ".TABLE_RESULTS." SET DateTime = '".date("Y-m-d H:i:s")."', Digi2Up = '".$_POST['Digi2Up']."', Digi2Down = '".$_POST['Digi2Down']."', Digi3 = '".$_POST['Digi3']."', AdminID = '".$_SESSION['AdminID']."' WHERE PeriodID = '".$_POST['PeriodID']."' LIMIT 1";
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        echo 'true,สำเร็จ ปรับปรุงข้อมูลเสร็จสิ้น';
                    }else{
                        echo 'false,ผิดพลาด ข้อมูลไม่ถูกต้อง';
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