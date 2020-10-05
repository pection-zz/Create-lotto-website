<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        if(isset($_POST['CRUD']) && $_POST['CRUD'] != ''){
            if($_POST['CRUD'] == 'Create'){}
            if($_POST['CRUD'] == 'Read'){
                function DateThai($strDate){
                    $strYear = date("Y",strtotime($strDate))+543;
                    $strMonth = date("n",strtotime($strDate));
                    $strDay = date("j",strtotime($strDate));
                    $strHour = date("H",strtotime($strDate));
                    $strMinute = date("i",strtotime($strDate));
                    $strSeconds = date("s",strtotime($strDate));
                    $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
                    $strMonthThai=$strMonthCut[$strMonth];
                    return "$strDay $strMonthThai $strYear";
                }
                
                function GetAgentFullName($AgentID, $Conn){
                    $sql = "SELECT FullName FROM ".TABLE_AGENT." WHERE AgentID = '".$AgentID."' LIMIT 1";
                    $result = $Conn->prepare($sql);
                    $result->execute();
                    if($result->rowCount() > 0){
                        while($data = $result->fetchObject()){
                            return $data->FullName;
                        }
                    }else{
                        return '-';
                    }
                }
                echo '
                    <table class="table table-striped table-bordered table-hover table-data table-responsive" id="table-detail-2digi-list">
                        <thead>
                            <tr>
                                <th class="text-center">งวดวันที่</th>
                                <th class="text-center">วันที่ทำรายการ</th>
                                <th class="text-center">ตัวแทนจำหน่าย</th>
                                <th class="text-center">ตัวเลข</th>
                                <th class="text-center">2 ตัวบน</th>
                                <th class="text-center">2 ตัวล่าง</th>
                            </tr>
                        </thead>
                        <tbody>';
                        
                        if($_POST['AgentID'] == "All"){
                            $sql = "SELECT * FROM ".TABLE_NUMBERS_DETAIL." WHERE NumberType = '2Digi' AND PeriodID = '".$_POST['PeriodID']."' AND Numbers = '".$_POST['Numbers']."' ORDER BY Numbers ASC";
                        }else{
                            $sql = "SELECT * FROM ".TABLE_NUMBERS_DETAIL." WHERE NumberType = '2Digi' AND PeriodID = '".$_POST['PeriodID']."' AND Numbers = '".$_POST['Numbers']."' AND AgentID = '".$_POST['AgentID']."' ORDER BY Numbers ASC";    
                        }
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        if($result->rowCount() > 0){
                            while($data = $result->fetchObject()){
                                echo '<tr>';
                                echo "<td class=\"text-center\" data-th=\"งวดที่\">".DateThai($_POST['PeriodID'])."</td>";
                                echo "<td class=\"text-center\" data-th=\"วันที่ทำรายการ\">".$data->DateTime."</td>";
                                echo "<td class=\"text-center\" data-th=\"ตัวแทนจำหน่าย\">".GetAgentFullName($data->AgentID, $Conn)."</td>";
                                echo "<td class=\"text-center\" data-th=\"ตัวเลข\">$data->Numbers</td>";
                                echo "<td class=\"text-center\" data-th=\"2 ตัวบน\">".number_format($data->CreditUp, 2, '.', ',')."</td>";
                                echo "<td class=\"text-center\" data-th=\"2 ตัวล่าง\">".number_format($data->CreditDown, 2, '.', ',')."</td>";
                                echo '</tr>'; 
                            }
                        }
                echo '
                        </tbody>
                    </table>';
            }
            if($_POST['CRUD'] == 'Update'){}
            if($_POST['CRUD'] == 'Delete'){}
        }else{
            echo '';
        }
    }
}
?>