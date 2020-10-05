<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
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
        
        if(isset($_POST['CRUD'])){
            if($_POST['CRUD'] == "Create"){
                if(ReadConfig('AcceptNumber', $Conn) == "Yes"){
                    if(PeroidStatus($_POST['PeriodID'], $Conn) == "Open"){
                        for($i=1;$i<=10;$i++){ // Begin for
                            if($_POST['Upper'.$i] == '' || $_POST['Upper'.$i] < 0){
                                $_POST['Upper'.$i] = 0;
                            }
                            if($_POST['Lower'.$i] == '' || $_POST['Lower'.$i] < 0){
                                $_POST['Lower'.$i] = 0;
                            }
                            if($_POST['Upper'.$i] == 0 && $_POST['Lower'.$i] == 0){
                                // ไม่ต้องทำอะไร
                            }else{
                                if(strlen($_POST['Number'.$i]) == 2 || strlen($_POST['Number'.$i]) == 3){
                                    if(strlen($_POST['Number'.$i]) == 2){
                                        $DigiType = '2Digi';
                                    }else{
                                        $DigiType = '3Digi';
                                    }
                                }
                                if(strlen($_POST['Number'.$i]) == 2){
                                    try{
                                        $Conn->beginTransaction();
                                        $sqlInsertNumber[$i] = "INSERT INTO ".TABLE_NUMBERS_DETAIL." VALUES('".$_POST['PeriodID']."', '".session_id()."', '".date("Y-m-d H:i:s")."', 'Manual', '', '".$_POST['Number'.$i]."', '".$DigiType."', '".$_POST['Upper'.$i]."', '".$_POST['Lower'.$i]."', '".$_SESSION['AgentID']."')";
                                        $resultInsertNumber[$i] = $Conn->prepare($sqlInsertNumber[$i]);
                                        $resultInsertNumber[$i]->execute();

                                        $sqlCreditLimit[$i] = "UPDATE ".TABLE_NUMBERS_CREDIT_LIMIT." SET CurrentCreditUp = CurrentCreditUp + ".$_POST['Upper'.$i].", CurrentCreditDown = CurrentCreditDown + ".$_POST['Lower'.$i]." WHERE PeriodID = '".$_POST['PeriodID']."' AND Numbers = '".$_POST['Number'.$i]."' LIMIT 1";
                                        $resultCreditLimit[$i] = $Conn->prepare($sqlCreditLimit[$i]);
                                        $resultCreditLimit[$i]->execute();

                                        $Conn->commit();
                                        echo 'true,สำเร็จ บันทึกรายการแล้ว';
                                    }catch(PDOException $e){
                                        $Conn->rollback();
                                        echo 'false,ผิดพลาด บันทึกรายการล้มเหลว';
                                    }   
                                }
                            }
                        }// End for                 
                    }else{
                        echo 'false,ผิดพลาด ปิดรับตัวเลขแล้ว';
                    }
                }else{
                    echo 'false,ผิดพลาด ปิดระบบโดยผู้ดูแลระบบ';
                }
            }
            if($_POST['CRUD'] == "Read"){
                if($_POST['Traget'] == "Totals"){
                    $sql = "SELECT SUM(CreditUp) AS TotalsUpper, SUM(CreditDown) AS TotalsLower FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$_POST['PeriodID']."' AND SessionID = '".session_id()."' AND Source = 'Manual' AND AgentID = '".$_SESSION['AgentID']."'";
                    $result = $Conn->prepare($sql);
                    $result->execute();
                    if($result->rowCount() > 0){
                        $data = $result->fetchObject();
                        $Totals = $data->TotalsUpper + $data->TotalsLower;
                        echo number_format($data->TotalsUpper, 2, '.', ',').'{}'.number_format($data->TotalsLower, 2, '.', ',').'{}'.number_format($Totals, 2, '.', ',');
                    }else{
                        echo '0.00{}0.00{}0.00';
                    }
                }
            }
            if($_POST['CRUD'] == "Update"){}
            if($_POST['CRUD'] == "Delete"){}
        }
    }
}
?>