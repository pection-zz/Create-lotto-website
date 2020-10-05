<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        // เริ่มต้นการประกาศฟังก์ชั่น
        function NumberIsLimit($Conn){
            return false;
            /*
            $sql = "SELECT * FROM ".TABLE_NUMBERS_CREDIT_LIMIT." WHERE PeriodID = '".$_POST['PeriodID']."' AND Numbers = '".$_POST['Number']."' LIMIT 1";
            $result = $Conn->prepare($sql);
            $result->execute();
            if($result->rowCount() > 0){
                $IsNumberFound = false;
                for($i=0;$i<$_SESSION['SessionIndex'];$i++){
                    if($_SESSION['SessionPeriodID'.$i] == $_POST['PeriodID'] && $_SESSION['SessionNumber'.$i] == $_POST['Number']){
                        $IsNumberFound = true;
                        $Position = $i;
                        break;
                    }
                }
                $SessionCreditUp = 0;
                $SessionCreditDown = 0;
                if($IsNumberFound == true){
                    $SessionCreditUp = $_SESSION['SessionCreditUp'.$Position];
                    $SessionCreditDown = $_SESSION['SessionCreditDown'.$Position];
                }
                $data = $result->fetchObject();
                if($data->LimitCreditUp == 0 && $data->LimitCreditDown == 0){
                    return false;
                }else{
                    $NewCreditUp = $data->CurrentCreditUp + $_POST['MoneyUpper'] + $SessionCreditUp;
                    $NewCreditDown = $data->CurrentCreditDown + $_POST['MoneyLower'] + $SessionCreditDown;
                    if($NewCreditUp < $data->LimitCreditUp || $NewCreditDown < $data->LimitCreditDown){
                        return true;
                    }else{
                        return false;
                    }
                }
            }else{
                return false;
            }
            */
        }
        function ReadConfig($ConfigName, $Conn){
            $sql = "SELECT ConfigValue FROM ".TABLE_CONFIGURATION." WHERE ConfigName = '".$ConfigName."' LIMIT 1";
            $result = $Conn->prepare($sql);
            $result->execute();
            if($result->rowCount() > 0){
                $data = $result->fetchObject();
                return $data->ConfigValue;
            }else{
                return '';
            }
        }
        
        function PeroidStatus($PeriodID, $Conn){
            $sql = "SELECT * FROM ".TABLE_PERIOD." WHERE PeriodID = '".$PeriodID."' AND Status = 'Open' AND AcceptExpireTime > '".date("Y-m-d H:i:s")."' LIMIT 1";
            $result = $Conn->prepare($sql);
            $result->execute();
            if($result->rowCount() > 0){
                return 'Open';
            }else{
                return 'Closed';
            }
        }
        
        function CreditUnlimit($Conn){
            $sql = "SELECT IsUnlimit FROM ".TABLE_AGENT." WHERE AgentID = '".$_SESSION['AgentID']."' AND IsActive = 'Yes' LIMIT 1";
            $result = $Conn->prepare($sql);
            $result->execute();
            $data = $result->fetchObject();
            return $data->IsUnlimit;
        }
        // สิ้นสุดการประกาศฟังก์ชั่น
        if(isset($_POST['CRUD'])){
            if($_POST['CRUD'] == "Create"){
                function Validate(){
                    if(!isset($_POST['PeriodID']) || $_POST['PeriodID'] == ''){
                        return false;
                        exit();
                    }
                    if(!isset($_POST['Number']) || !is_numeric($_POST['Number'])){
                        return false;
                        exit();
                    }
                    if($_POST['MoneyUpper'] == '' || $_POST['MoneyUpper'] < 0){
                        $_POST['MoneyUpper'] = 0;
                    }
                    if($_POST['MoneyLower'] == '' || $_POST['MoneyLower'] < 0){
                        $_POST['MoneyLower'] = 0;
                    }
                    if($_POST['MoneyUpper'] == 0 && $_POST['MoneyLower'] == 0){
                        return false;
                        exit();
                    }
                    return true;
                }
                
                function GenBillID($Conn){
                    // เริ่มต้นการสร้าง BillID
                    $sqlGenBill['01'] = "SELECT * FROM ".TABLE_BILL_ID." WHERE ID = '01' LIMIT 1";
                    $resultGenBill['01'] = $Conn->prepare($sqlGenBill['01']);
                    $resultGenBill['01']->execute();
                    if($resultGenBill['01']->rowCount() == 0){
                        $BillID = date("Y").date("m").date("d").'-000001';
                        $sqlGenBill['02'] = "INSERT INTO ".TABLE_BILL_ID." VALUES ('01', '".date("Y")."', '".date("m")."', '".date("d")."', '2')";
                        $resultGenBill['02'] = $Conn->prepare($sqlGenBill['02']);
                        $resultGenBill['02']->execute();
                    }else{
                        $data = $resultGenBill['01']->fetchObject();
                        $Current = $data->Year."-".$data->Month."-".$data->Day;
                        if($Current != date("Y-m-d")){
                            $BillID = date("Y").date("m").date("d").'-000001';
                            $sqlGenBill['02'] = "UPDATE ".TABLE_BILL_ID." SET Year = '".date("Y")."', Month = '".date("m")."', Day = '".date("d")."', Value = '2' WHERE ID = '01' LIMIT 1";
                            $resultGenBill['02'] = $Conn->prepare($sqlGenBill['02']);
                            $resultGenBill['02']->execute();
                        }else{
                            $BillID = $data->Year.$data->Month.$data->Day.'-'.substr("000000".$data->Value, -6);
                            $NextValue = $data->Value + 1;
                            $sqlGenBill['02'] = "UPDATE ".TABLE_BILL_ID." SET Value = '".$NextValue."' WHERE ID = '01' LIMIT 1";
                            $resultGenBill['02'] = $Conn->prepare($sqlGenBill['02']);
                            $resultGenBill['02']->execute();
                        } 
                    }
                    return $BillID;
                }
                
                if(CreditUnlimit($Conn) == 'Yes'){
                    // ไม่จำกัดวงเงินของตัวแทน
                    if($_POST['Traget'] == 'Database'){
                        if(NumberIsLimit($Conn) == true){
                            // เลขเต็มจำนวนแล้ว
                            echo 'false,ผิดพลาด เกินขีดจำกัดการรับแทง';
                        }else{
                            $_SESSION['SessionBillID'] = GenBillID($Conn);
                            
                        
                            try{
                                $Conn->beginTransaction();
                                for($i=0;$i<=$_SESSION['SessionIndex'];$i++){
                                    if($_SESSION['SessionNumber'.$i] != '' && strlen($_SESSION['SessionNumber'.$i]) > 1){
                                        if($_SESSION['SessionCreditUp'.$i] == '' || $_SESSION['SessionCreditUp'.$i] < 0){
                                            $_SESSION['SessionCreditUp'.$i] = 0;
                                        }
                                        if($_SESSION['SessionCreditDown'.$i] == '' || $_SESSION['SessionCreditDown'.$i] < 0){
                                            $_SESSION['SessionCreditDown'.$i] = 0;
                                        }
                                        if($_SESSION['SessionCreditUp'.$i] == 0 && $_SESSION['SessionCreditDown'.$i] == 0){
                                            // ไม่ต้องทำอะไร
                                        }else{
                                            if(strlen($_SESSION['SessionNumber'.$i]) == 2 || strlen($_SESSION['SessionNumber'.$i]) == 3){
                                                if(strlen($_SESSION['SessionNumber'.$i]) == 2){
                                                    $DigiType = '2Digi';
                                                }else{
                                                    $DigiType = '3Digi';
                                                }

                                                $sqlInsertNumber[$i] = "INSERT INTO ".TABLE_NUMBERS_DETAIL." VALUES('".$_SESSION['SessionPeriodID'.$i]."', '".session_id()."', '".date("Y-m-d H:i:s")."', 'System', '".$_SESSION['SessionBillID']."', '".$_SESSION['SessionNumber'.$i]."', '".$DigiType."', '".$_SESSION['SessionCreditUp'.$i]."', '".$_SESSION['SessionCreditDown'.$i]."', '".$_SESSION['AgentID']."')";
                                                $resultInsertNumber[$i] = $Conn->prepare($sqlInsertNumber[$i]);
                                                $resultInsertNumber[$i]->execute();

                                                $sqlCreditLimit[$i] = "UPDATE ".TABLE_NUMBERS_CREDIT_LIMIT." SET CurrentCreditUp = CurrentCreditUp + ".$_SESSION['SessionCreditUp'.$i].", CurrentCreditDown = CurrentCreditDown + ".$_SESSION['SessionCreditDown'.$i]." WHERE PeriodID = '".$_SESSION['SessionPeriodID'.$i]."' AND Numbers = '".$_SESSION['SessionNumber'.$i]."' LIMIT 1";
                                                $resultCreditLimit[$i] = $Conn->prepare($sqlCreditLimit[$i]);
                                                $resultCreditLimit[$i]->execute();
                                            }
                                        }
                                    }
                                }
                                
                                $Conn->commit();
                                echo 'true,สำเร็จ บันทึกรายการแล้ว';
                                for($i=0;$i<=$_SESSION['SessionIndex'];$i++){
                                    $_SESSION['SessionPeriodID'.$i] = null;
                                    $_SESSION['SessionNumber'.$i] = null;
                                    $_SESSION['SessionCreditUp'.$i] = null;
                                    $_SESSION['SessionCreditDown'.$i] = null;
                                    unset($_SESSION['SessionPeriodID'.$i]);
                                    unset($_SESSION['SessionNumber'.$i]);
                                    unset($_SESSION['SessionCreditUp'.$i]);
                                    unset($_SESSION['SessionCreditDown'.$i]);
                                }
                                $_SESSION['SessionIndex'] = null;
                                unset($_SESSION['SessionIndex']);
                            }catch(PDOException $e){
                                $Conn->rollback();
                                echo 'false,ผิดพลาด บันทึกรายการล้มเหลว';
                            }
                        }
                    } // $_POST['Traget'] == 'Database'
                    if($_POST['Traget'] == 'Session'){
                        if(ReadConfig('AcceptNumber', $Conn) == 'Yes' && PeroidStatus($_POST['PeriodID'], $Conn) == 'Open'){
                            if(Validate() == true){
                                if(NumberIsLimit($Conn) == true){
                                    // เลขเต็มจำนวนแล้ว
                                    echo 'false,ผิดพลาด เกินขีดจำกัดการรับแทง';
                                }else{
                                    if(!isset($_SESSION['SessionIndex']) || $_SESSION['SessionIndex'] == ''){
                                        $_SESSION['SessionIndex'] = 0;
                                        $_SESSION['SessionPeriodID'.$_SESSION['SessionIndex']] = $_POST['PeriodID'];
                                        $_SESSION['SessionNumber'.$_SESSION['SessionIndex']] = $_POST['Number'];
                                        $_SESSION['SessionCreditUp'.$_SESSION['SessionIndex']] = $_POST['MoneyUpper'];
                                        $_SESSION['SessionCreditDown'.$_SESSION['SessionIndex']] = $_POST['MoneyLower'];
                                        $_SESSION['SessionIndex']++;
                                        echo 'true,สำเร็จ บันทึกรายการแล้ว';
                                    }else{
                                        $IsNumberFound = false;
                                        for($i=0;$i<$_SESSION['SessionIndex'];$i++){
                                            if($_SESSION['SessionPeriodID'.$i] == $_POST['PeriodID'] && $_SESSION['SessionNumber'.$i] == $_POST['Number']){
                                                $IsNumberFound = true;
                                                $Position = $i;
                                                break;
                                            }
                                        }
                                        if($IsNumberFound == true){
                                            $_SESSION['SessionCreditUp'.$Position] = $_SESSION['SessionCreditUp'.$Position] + $_POST['MoneyUpper'];
                                            $_SESSION['SessionCreditDown'.$Position] = $_SESSION['SessionCreditDown'.$Position] + $_POST['MoneyLower'];
                                            echo 'true,สำเร็จ บันทึกรายการแล้ว';
                                        }else{
                                            $IsEmptyFound = false;
                                            for($i=0;$i<$_SESSION['SessionIndex'];$i++){
                                                if($_SESSION['SessionNumber'.$i] == ''){
                                                    $IsEmptyFound = true;
                                                    $Position = $i;
                                                    break;
                                                }
                                            }
                                            if($IsEmptyFound == true){
                                                $_SESSION['SessionPeriodID'.$Position] = $_POST['PeriodID'];
                                                $_SESSION['SessionNumber'.$Position] = $_POST['Number'];
                                                $_SESSION['SessionCreditUp'.$Position] = $_POST['MoneyUpper'];
                                                $_SESSION['SessionCreditDown'.$Position] = $_POST['MoneyLower'];
                                                echo 'true,สำเร็จ บันทึกรายการแล้ว';
                                            }else{
                                                $_SESSION['SessionPeriodID'.$_SESSION['SessionIndex']] = $_POST['PeriodID'];
                                                $_SESSION['SessionNumber'.$_SESSION['SessionIndex']] = $_POST['Number'];
                                                $_SESSION['SessionCreditUp'.$_SESSION['SessionIndex']] = $_POST['MoneyUpper'];
                                                $_SESSION['SessionCreditDown'.$_SESSION['SessionIndex']] = $_POST['MoneyLower'];
                                                $_SESSION['SessionIndex']++;
                                                echo 'true,สำเร็จ บันทึกรายการแล้ว';
                                            }
                                        }
                                    }
                                }
                            }else{
                                echo 'false,ผิดพลาด ข้อมูลไม่ถูกต้อง';
                            } // Validate() == true
                        }else{
                            echo 'false,ผิดพลาด ปิดรับแทงแล้ว';
                        }
                    } // $_POST['Traget'] == 'Session'
                }else{
                    // จำกัดวงเงินของตัวแทน
                }
            } // $_POST['CRUD'] == "Create"
            
            if($_POST['CRUD'] == "Read"){}
            if($_POST['CRUD'] == "Update"){
                if($_POST['Traget'] == 'Database'){
                    echo 'false,ผิดพลาด ยกเลิกคำสั่งโดยระบบ';
                }
                if($_POST['Traget'] == 'Session'){
                    if(isset($_POST['Position']) && $_POST['Position'] != ''){
                        if($_POST['MoneyUpper'] == '' || $_POST['MoneyUpper'] < 0) {
                            $_POST['MoneyUpper'] = 0;
                        }
                        if($_POST['MoneyLower'] == '' || $_POST['MoneyLower'] < 0) {
                            $_POST['MoneyLower'] = 0;
                        }
                        if($_POST['MoneyUpper'] == 0 && $_POST['MoneyLower'] == 0){
                            // Remove from sell list
                            $_SESSION['SessionNumber'.$_POST['Position']] = '';
                            $_SESSION['SessionCreditUp'.$_POST['Position']] = 0;
                            $_SESSION['SessionCreditDown'.$_POST['Position']] = 0;
                            echo 'true,สำเร็จ ลบตัวเลขแล้ว';
                        }else{
                            $_SESSION['SessionCreditUp'.$_POST['Position']] = $_POST['MoneyUpper'];
                            $_SESSION['SessionCreditDown'.$_POST['Position']] = $_POST['MoneyLower'];
                            echo 'true,สำเร็จ ปรับปรุงตัวเลขแล้ว';
                        }
                    }else{
                        echo 'false,ผิดพลาด ข้อมูลไม่ถูกต้อง';
                    }
                }
            }
            if($_POST['CRUD'] == "Delete"){
                if($_POST['Traget'] == 'Database'){
                    echo 'false,ผิดพลาด ยกเลิกคำสั่งโดยระบบ';
                }
                if($_POST['Traget'] == 'Session'){
                    if(isset($_POST['Position']) && $_POST['Position'] != ''){
                        $_SESSION['SessionNumber'.$_POST['Position']] = '';
                        $_SESSION['SessionCreditUp'.$_POST['Position']] = 0;
                        $_SESSION['SessionCreditDown'.$_POST['Position']] = 0;
                        echo 'true,สำเร็จ ลบตัวเลขแล้ว';
                    }else{
                        echo 'false,ผิดพลาด ข้อมูลไม่ถูกต้อง';
                    }
                }
            }
        }
    }
}
?>