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
                if($_POST['View'] == "display-table-period-list"){
                    function DateThai($strDate, $ShowTime){
                        $strYear = date("Y",strtotime($strDate))+543;
                        $strMonth= date("n",strtotime($strDate));
                        $strDay= date("j",strtotime($strDate));
                        $strHour= date("H",strtotime($strDate));
                        $strMinute= date("i",strtotime($strDate));
                        $strSeconds= date("s",strtotime($strDate));
                        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
                        $strMonthThai=$strMonthCut[$strMonth];
                        if($ShowTime == 'Yes'){
                            return "$strDay $strMonthThai $strYear $strHour:$strMinute";
                        }else{
                            return "$strDay $strMonthThai $strYear";
                        }
                    }
                    
                    function CheckRepeatedly($Source, $Value){
                       foreach($Source as $NewValue){
                            if($NewValue == $Value){ 
                                return false;
                            }
                       }
                       return true;
                    }
                    
                    function GetSellTotals($PeriodID, $Conn){
                        $sql = "SELECT SUM(CreditUp) + SUM(CreditDown) AS SellTotals FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$PeriodID."'";
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        if($result->rowCount() > 0){
                            $data = $result->fetchObject();
                            return $data->SellTotals;
                        }else{
                            return 0;
                        }
                    }
                    
                    function GetRate($NumberType, $CreditType, $Conn){
                        // Begin recheck variable
                        if($NumberType == '2Digi'){
                            $NumberType = '2Digi';
                        }else{
                            $NumberType = '3Digi';
                        }
                        if($CreditType == 'CreditUp'){
                            $CreditType = 'CreditUp';
                        }else{
                            $CreditType = 'CreditDown';
                        }
                        // End recheck variable
                        $sql = "SELECT Rate FROM ".TABLE_RATE;
                        if($NumberType == '2Digi'){
                            if($CreditType == 'CreditUp'){
                                $sql .= " WHERE RateName = '2DigiUp'";
                            }else{
                                $sql .= " WHERE RateName = '2DigiDown'";
                            }
                        }else{
                            if($CreditType == 'CreditUp'){
                                $sql .= " WHERE RateName = '3DigiUp'";
                            }else{
                                $sql .= " WHERE RateName = '3DigiDown'";
                            }
                        }
                        $sql .= " LIMIT 1";
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        $data = $result->fetchObject();
                        return $data->Rate;
                    }
                    
                    function GetResultNumber($PeriodID, $NumberType, $CreditType, $Conn){
                        // Begin recheck variable
                        if($NumberType == '2Digi'){
                            $NumberType = '2Digi';
                        }else{
                            $NumberType = '3Digi';
                        }
                        if($CreditType == 'CreditUp'){
                            $CreditType = 'CreditUp';
                        }else{
                            $CreditType = 'CreditDown';
                        }
                        // End recheck variable
                        if($NumberType == '2Digi'){
                            if($CreditType == 'CreditUp'){
                                $sql = 'SELECT Digi2Up AS Numbers';
                            }else{
                                $sql = 'SELECT Digi2Down AS Numbers';
                            }
                        }
                        if($NumberType == '3Digi'){
                            $sql = 'SELECT Digi3 AS Numbers';
                        }
                        $sql .= " FROM ".TABLE_RESULTS." WHERE PeriodID = '".$PeriodID."' LIMIT 1";
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        $data = $result->fetchObject();
                        if($data->Numbers == null){
                            return false;
                        }else{
                             return $data->Numbers;
                        }
                    }
                    
                    function GetRewardData($PeriodID, $AgentID, $RewardNumber, $NumberType, $CreditType, $Rate, $Conn){
                        if($RewardNumber != false){
                            // Begin recheck variable
                            if($NumberType == '2Digi'){
                                $NumberType = '2Digi';
                            }else{
                                $NumberType = '3Digi';
                            }
                            if($CreditType == 'CreditUp'){
                                $CreditType = 'CreditUp';
                            }else{
                                $CreditType = 'CreditDown';
                            }
                            // End recheck variable

                            if($NumberType == '2Digi'){
                                if($AgentID == "All"){
                                    $sql = "SELECT SUM(".$CreditType.") AS Totals FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$PeriodID."' AND NumberType = '".$NumberType."' AND Numbers = '".$RewardNumber."'";
                                }else{
                                    // If specify AgentID
                                    $sql = "SELECT SUM(".$CreditType.") AS Totals FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$PeriodID."' AND AgentID = '".$AgentID."' AND NumberType = '".$NumberType."' AND Numbers = '".$RewardNumber."'";
                                }
                                $result = $Conn->prepare($sql);
                                $result->execute();
                                $data = $result->fetchObject();
                                if($data->Totals == null){
                                    return '0';
                                }else{
                                     return $data->Totals * $Rate;
                                }
                            }
                            if($NumberType == '3Digi'){
                                if($CreditType == 'CreditUp'){
                                    if($AgentID == "All"){
                                        $sql = "SELECT SUM(".$CreditType.") AS Totals FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$PeriodID."' AND NumberType = '".$NumberType."' AND Numbers = '".$RewardNumber."'";
                                    }else{
                                        // If specify AgentID
                                        $sql = "SELECT SUM(".$CreditType.") AS Totals FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$PeriodID."' AND AgentID = '".$AgentID."' AND NumberType = '".$NumberType."' AND Numbers = '".$RewardNumber."'";
                                    }
                                    $result = $Conn->prepare($sql);
                                    $result->execute();
                                    $data = $result->fetchObject();
                                    if($data->Totals == null){
                                        return '0';
                                    }else{
                                         return $data->Totals * $Rate;
                                    }
                                }else{
                                    // เลขโต๊ด
                                    $RewardNumber = (string)$RewardNumber;
                                    if($AgentID == "All"){
                                        $sql = "SELECT SUM(CreditDown) AS Totals FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$PeriodID."' AND NumberType = '".$NumberType."' AND Numbers = '".$RewardNumber[0].$RewardNumber[2].$RewardNumber[1]."' OR Numbers = '".$RewardNumber[1].$RewardNumber[2].$RewardNumber[0]."' OR Numbers = '".$RewardNumber[1].$RewardNumber[0].$RewardNumber[2]."' OR Numbers = '".$RewardNumber[2].$RewardNumber[1].$RewardNumber[0]."' OR Numbers = '".$RewardNumber[2].$RewardNumber[0].$RewardNumber[1]."'";
                                    }else{
                                        // If specify AgentID
                                        $sql = "SELECT SUM(CreditDown) AS Totals FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$PeriodID."' AND AgentID = '".$AgentID."' AND NumberType = '".$NumberType."' AND Numbers = '".$RewardNumber[0].$RewardNumber[2].$RewardNumber[1]."' OR Numbers = '".$RewardNumber[1].$RewardNumber[2].$RewardNumber[0]."' OR Numbers = '".$RewardNumber[1].$RewardNumber[0].$RewardNumber[2]."' OR Numbers = '".$RewardNumber[2].$RewardNumber[1].$RewardNumber[0]."' OR Numbers = '".$RewardNumber[2].$RewardNumber[0].$RewardNumber[1]."'";
                                    }
                                    $result = $Conn->prepare($sql);
                                    $result->execute();
                                    $data = $result->fetchObject();
                                    if($data->Totals == null){
                                        return '0';
                                    }else{
                                         return $data->Totals * $Rate;
                                    }
                                }
                            }
                        }else{
                            return 0;
                        }
                    }
                    
                    function GetAllReward($PeriodID, $Conn){
                        $Result2UpNumber = GetResultNumber($PeriodID, '2Digi', 'CreditUp', $Conn);
                        $Result2DownNumber = GetResultNumber($PeriodID, '2Digi', 'CreditDown', $Conn);
                        $RateUp2 = GetRate('2Digi', 'CreditUp', $Conn);
                        $RateDown2 = GetRate('2Digi', 'CreditDown', $Conn);
                        $RewardUp2 = GetRewardData($PeriodID, 'All', $Result2UpNumber, '2Digi', 'CreditUp', $RateUp2, $Conn);
                        $RewardDown2 = GetRewardData($PeriodID, 'All', $Result2DownNumber, '2Digi', 'CreditDown', $RateDown2, $Conn);
                        $Reward2 = $RewardUp2 + $RewardDown2;
                        
                        $Result3UpNumber = GetResultNumber($PeriodID, '3Digi', 'CreditUp', $Conn);
                        $RateUp3 = GetRate('3Digi', 'CreditUp', $Conn);
                        $RateDown3 = GetRate('3Digi', 'CreditDown', $Conn);
                        $RewardUp3 = GetRewardData($PeriodID, 'All', $Result3UpNumber, '3Digi', 'CreditUp', $RateUp3, $Conn);
                        $RewardDown3 = GetRewardData($PeriodID, 'All', $Result3UpNumber, '3Digi', 'CreditDown', $RateDown3, $Conn);
                        $Reward3 = $RewardUp3 + $RewardDown3;
                        
                        $Reward = $Reward2 + $Reward3;
                        return $Reward;
                    }
                    
                    $sql = "UPDATE ".TABLE_PERIOD." SET Status = 'Close' WHERE Status = 'Open' AND AcceptExpireTime < '".date("Y-m-d H:i:s")."'";
                    $result = $Conn->prepare($sql);
                    $result->execute();
                    
                    echo '
                    <table class="table table-striped table-bordered table-hover table-data table-responsive" id="table-period-list">
                        <thead>
                            <tr>
                                <th class="text-center">งวดวันที่</th>
                                <th class="text-center">2 ตัวบน</th>
                                <th class="text-center">2 ตัวล่าง</th>
                                <th class="text-center">3 ตัวตรง</th>
                                <th class="text-center">3 ตัวโต๊ด</th>
                                <th class="text-center">ยอดขายรวม</th>
                                <th class="text-center">ยอดจ่ายรวม</th>
                                <th class="text-center">เครื่องมือ</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $sql = "SELECT * FROM ".TABLE_RESULTS." ORDER BY PeriodID DESC";
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        if($result->rowCount() > 0){
                            while($data = $result->fetchObject()){
                                $RewardNumber = (string)$data->Digi3;
                                $Digi3[0] = $RewardNumber[0].$RewardNumber[2].$RewardNumber[1];
                                $Digi3[1] = $RewardNumber[1].$RewardNumber[2].$RewardNumber[0];
                                $Digi3[2] = $RewardNumber[1].$RewardNumber[0].$RewardNumber[2];
                                $Digi3[3] = $RewardNumber[2].$RewardNumber[0].$RewardNumber[1];
                                $Digi3[4] = $RewardNumber[2].$RewardNumber[1].$RewardNumber[0];
                                $Source = array($Digi3[0],$Digi3[1],$Digi3[2],$Digi3[3],$Digi3[4]);
                                $ReFormatSource = array("");
                                $RunNumber = 0;
                                foreach($Source as $Value){
                                    if(CheckRepeatedly($ReFormatSource, $Value)){
                                        $ReFormatSource[$RunNumber] = $Value;
                                        $RunNumber ++;
                                    }
                                }
                                $Digi3list = '';
                                foreach($ReFormatSource as $Value){
                                    $Digi3list .= $Value.' ';
                                }
                                echo '<tr>';
                                echo "<td class=\"text-center\" data-th=\"งวดวันที่\">".DateThai($data->PeriodID, 'No')."</td>";
                                echo "<td class=\"text-center\" data-th=\"2 ตัวบน\">".$data->Digi2Up."</td>";
                                echo "<td class=\"text-center\" data-th=\"2 ตัวล่าง\">".$data->Digi2Down."</td>";
                                echo "<td class=\"text-center\" data-th=\"3 ตัวตรง\">".$data->Digi3."</td>";
                                echo "<td class=\"text-center\" data-th=\"3 ตัวโต๊ด\">".$Digi3list."</td>";
                                echo "<td class=\"text-center\" data-th=\"ยอดขายรวม\">".number_format(GetSellTotals($data->PeriodID, $Conn), 2, '.', ',')."</td>";
                                echo "<td class=\"text-center\" data-th=\"ยอดจ่ายรวม\">".number_format(GetAllReward($data->PeriodID, $Conn), 2, '.', ',')."</td>";
                                echo "<td class=\"text-center\" data-th=\"เครื่องมือ\">
                                    <button type=\"button\" class=\"btn btn-outline btn-warning\" onclick=\"CRUDModal('Update', '".$data->PeriodID."');\">
                                        <i class=\"fa fa-pencil\"></i>
                                    </button>
                                    <button type=\"button\" class=\"btn btn-outline btn-danger\" onclick=\"CRUDModal('Delete', '".$data->PeriodID."');\">
                                        <i class=\"fa fa-trash-o\"></i>
                                    </button>
                                </td>";
                                echo '</tr>'; 
                            }
                        }
                echo '
                        </tbody>
                    </table>';
                }
            }
            if($_POST['CRUD'] == 'Update'){}
            if($_POST['CRUD'] == 'Delete'){}
        }else{
            echo '';
        }
    }
}
?>