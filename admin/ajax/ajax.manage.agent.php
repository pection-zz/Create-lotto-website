<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        if(isset($_POST['CRUD'])){
            if(!is_numeric($_POST['Com2DigiUp'])){
                $_POST['Com2DigiUp'] = 0;
            }
            if(!is_numeric($_POST['Com2DigiDown'])){
                $_POST['Com2DigiDown'] = 0;
            }
            if(!is_numeric($_POST['Com3DigiUp'])){
                $_POST['Com3DigiUp'] = 0;
            }
            if(!is_numeric($_POST['Com3DigiDown'])){
                $_POST['Com3DigiDown'] = 0;
            }
            function Validator($CRUD, $Conn){
                $status = true;
                if($CRUD == "Create"){
                    // Begin validate Fullname 
                    if(empty($_POST['Fullname'])){
                        $status = false;
                    }

                    // Begin validate Username       
                    if(empty($_POST['Username'])){
                        $status = false;
                    }else{
                        $sql = "SELECT * FROM ".TABLE_AGENT." WHERE Username = '".$_POST['Username']."'";
                        if($_POST['CRUD'] == 'Update'){
                            $sql .= " AND AgentID != '".$_POST['AgentID']."' LIMIT 1";
                        }
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        if($result->rowCount() > 0){
                            $status = false;
                        }
                    }
        
                    // Begin validate and compare Password 
                    if(empty($_POST['Password1']) || empty($_POST['Password2'])){
                        $status = false;
                    }else{
                        if(strstr($empty, $_POST['Password1']) || strstr($empty, $_POST['Password2'])){
                            $status = false;
                        }else{
                            if(md5($_POST['Password1']) != md5($_POST['Password2'])){
                                $status = false;
                            }
                        }
                    }
                }
                if($CRUD == "Read"){}
                if($CRUD == "Update"){
                    // Begin validate Fullname 
    
                }
                if($CRUD == "Delete"){}
                return $status;
            }
            if($_POST['CRUD'] == 'Create'){
                if(Validator($_POST['CRUD'], $Conn) == 'true'){
                    try{
                        $Conn->beginTransaction();
                        // Step for generate AgentID
                        $sql[0] = "SELECT * FROM ".TABLE_AGENT_ID." LIMIT 1";
                        $result[0] = $Conn->prepare($sql[0]);
                        $result[0]->execute();
                        if($result[0]->rowCount() == 0){
                            $AgentID = date("Y").date("m").'-001';
                            $sql[1] = "INSERT INTO ".TABLE_AGENT_ID." VALUES ('".date("Y")."', '".date("m")."', '2')";
                        }else{
                            $data = $result[0]->fetchObject();
                            if($data->Year != date("Y")){
                                $AgentID = date("Y").date("m").'-001';
                                $sql[1] = "UPDATE ".TABLE_AGENT_ID." SET Value = '2', Month = '".date("m")."', Year = '".date("Y")."' LIMIT 1";
                            }else{
                                if($data->Month != date("m")){
                                   $AgentID = date("Y").date("m").'-001';
                                    $sql[1] = "UPDATE ".TABLE_AGENT_ID." SET Value = '2', Month = '".date("m")."' WHERE Year = '".date("Y")."' LIMIT 1";
                                }else{
                                    $AgentID = $data->Year.$data->Month.'-'.substr("000".$data->Value, -3);
                                    $NextValue = $data->Value + 1;
                                    $sql[1] = "UPDATE ".TABLE_AGENT_ID." SET Value = '".$NextValue."' LIMIT 1";
                                }                             
                            }
                        }
                        $result[1] = $Conn->prepare($sql[1]);
                        $result[1]->execute();
                        
                        $sql[2] = "INSERT INTO ".TABLE_AGENT." VALUES ('".$AgentID."', '".$_POST['Username']."', '".md5($_POST['Password1'])."', '".$_POST['Fullname']."', '".$_POST['Address']."', '".$_POST['Email1']."', '".$_POST['Telephone']."', '".$_POST['BankID']."', '".$_POST['AccountName']."', '".$_POST['AccountNumber']."', '0', '".$_POST['Com2DigiUp']."', '".$_POST['Com2DigiDown']."', '".$_POST['Com3DigiUp']."', '".$_POST['Com3DigiDown']."', '".$_POST['IsUnlimit']."', '".$_SESSION['AdminID']."', 'Yes', NULL, NULL, '2001-01-01 01:01:01.000000')";
                        $result[2] = $Conn->prepare($sql[2]);
                        $result[2]->execute();
                        
                        $Conn->commit();
                        echo 'true,สำเร็จ บันทึกข้อมูลเสร็จสิ้น';
                    }catch(PDOException $e){
                        $Conn->rollback();
                        //echo 'false,ผิดพลาด บันทึกข้อมูลล้มเหลว';
                        echo $sql[2];
                    }
                }else{
                    echo 'false,ผิดพลาด ข้อมูลไม่ถูกต้อง';
                }
            }
            if($_POST['CRUD'] == 'Read'){}
            if($_POST['CRUD'] == 'Update'){
                if(Validator($_POST['CRUD'], $Conn) == 'true'){
                    try{
                        $Conn->beginTransaction();
                        // Update Agent data and disconnect
                        $sql[0] = "UPDATE ".TABLE_AGENT." SET FullName = '".$_POST['Fullname']."', Address = '".$_POST['Address']."', Email = '".$_POST['Email']."', Telephone = '".$_POST['Telephone']."', BankID = '".$_POST['BankID']."', AccountName = '".$_POST['AccountName']."', AccountNumber = '".$_POST['AccountNumber']."', Com2DigiUp = '".$_POST['Com2DigiUp']."', Com2DigiDown = '".$_POST['Com2DigiDown']."', Com3DigiUp = '".$_POST['Com3DigiUp']."', Com3DigiDown = '".$_POST['Com3DigiDown']."', IsUnlimit = '".$_POST['IsUnlimit']."', IsActive = '".$_POST['IsActive']."', AuthorizeKey = '', SessionID = ''";
                        
                        if($_POST['Password'] != ''){
                            $sql[0] .= ", Password = '".md5($_POST['Password'])."'";
                        }
                        $sql[0] .=  " WHERE AgentID = '".$_POST['AgentID']."' LIMIT 1";
                        $result[0] = $Conn->prepare($sql[0]);
                        $result[0]->execute();
                        
                        // Clear user online
                        $sql[1] = "DELETE FROM ".TABLE_ONLINE." WHERE UserID = '".$_POST['AgentID']."' AND AccessType = 'Agent'";
                        $result[1] = $Conn->prepare($sql[1]);
                        $result[1]->execute();
                        
                        $Conn->commit();
                        echo 'true,สำเร็จ ปรับปรุงข้อมูลเสร็จสิ้น';
                    }catch(PDOException $e){
                        $Conn->rollback();
                        echo 'false,ผิดพลาด ปรับปรุงข้อมูลล้มเหลว';
                    }
                }else{
                    echo 'false,ผิดพลาด ข้อมูลไม่ถูกต้อง';
                }
            }
            if($_POST['CRUD'] == 'Delete'){}
        }else{
            echo 'false,ยกเลิกการทำงานโดยระบบ';
        }
    }else{
        echo 'false,ยกเลิกการทำงานโดยระบบ';
    }
}
?>
