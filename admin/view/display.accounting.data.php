<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        function GetSellData($PeriodID, $AgentID, $NumberType, $CreditType, $Conn){
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
            
            if($AgentID == "All"){
                $sql = "SELECT SUM(".$CreditType.") AS Totals FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$PeriodID."' AND NumberType = '".$NumberType."'";
            }else{
                // If specify AgentID
                $sql = "SELECT SUM(".$CreditType.") AS Totals FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$PeriodID."' AND AgentID = '".$AgentID."' AND NumberType = '".$NumberType."'";
            }
            $result = $Conn->prepare($sql);
            $result->execute();
            $data = $result->fetchObject();
            if($data->Totals == null){
                return '0';
            }else{
                 return $data->Totals;
            }
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
        
        function DateTimeThai($strDate){
            $strYear = date("Y",strtotime($strDate))+543;
            $strMonth = date("n",strtotime($strDate));
            $strDay = date("j",strtotime($strDate));
            $strHour = date("H",strtotime($strDate));
            $strMinute = date("i",strtotime($strDate));
            $strSeconds = date("s",strtotime($strDate));
            $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
            $strMonthThai=$strMonthCut[$strMonth];
            return "$strDay $strMonthThai $strYear $strHour:$strMinute";
        }
        
        function CalulatePercent($SellValue,$RewardValue){
            if($SellValue > 0){
                $Temp = $SellValue - $RewardValue;
                $Percent = ($Temp * 100) / $SellValue;
            }else{
                $Percent = 0;
            }
            return $Percent;
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
        
        if(isset($_POST['PeriodID']) && $_POST['PeriodID'] != ''){
            $PeriodID = $_POST['PeriodID'];
        }else{
            $sql = "SELECT PeriodID FROM ".TABLE_PERIOD." ORDER BY PeriodID DESC LIMIT 1";
            $result = $Conn->prepare($sql);
            $result->execute();
            $data = $result->fetchObject();
            $PeriodID = $data->PeriodID;   
        }
        $CreditUp2 = GetSellData($_POST['PeriodID'], $_POST['AgentID'], '2Digi', 'CreditUp', $Conn);
        $CreditDown2 = GetSellData($_POST['PeriodID'], $_POST['AgentID'], '2Digi', 'CreditDown', $Conn);
        $Credit2 = $CreditUp2 + $CreditDown2;
        $Result2UpNumber = GetResultNumber($_POST['PeriodID'], '2Digi', 'CreditUp', $Conn);
        $Result2DownNumber = GetResultNumber($_POST['PeriodID'], '2Digi', 'CreditDown', $Conn);
        $RateUp2 = GetRate('2Digi', 'CreditUp', $Conn);
        $RateDown2 = GetRate('2Digi', 'CreditDown', $Conn);
        $RewardUp2 = GetRewardData($_POST['PeriodID'], $_POST['AgentID'], $Result2UpNumber, '2Digi', 'CreditUp', $RateUp2, $Conn);
        $RewardDown2 = GetRewardData($_POST['PeriodID'], $_POST['AgentID'], $Result2DownNumber, '2Digi', 'CreditDown', $RateDown2, $Conn);
        $Reward2 = $RewardUp2 + $RewardDown2;
        $Percent2 = CalulatePercent($Credit2, $Reward2);
        $Benefit2 = $Credit2 - $Reward2;
        if($Benefit2 < 0){
            $htmlstyle2 = ' text-danger';
        }else{
            $htmlstyle2 = ' text-success';
        }
        
        $CreditUp3 = GetSellData($_POST['PeriodID'], $_POST['AgentID'], '3Digi', 'CreditUp', $Conn);
        $CreditDown3 = GetSellData($_POST['PeriodID'], $_POST['AgentID'], '3Digi', 'CreditDown', $Conn);
        $Credit3 = $CreditUp3 + $CreditDown3;
        $Result3UpNumber = GetResultNumber($_POST['PeriodID'], '3Digi', 'CreditUp', $Conn);
        $RateUp3 = GetRate('3Digi', 'CreditUp', $Conn);
        $RateDown3 = GetRate('3Digi', 'CreditDown', $Conn);
        $RewardUp3 = GetRewardData($_POST['PeriodID'], $_POST['AgentID'], $Result3UpNumber, '3Digi', 'CreditUp', $RateUp3, $Conn);
        $RewardDown3 = GetRewardData($_POST['PeriodID'], $_POST['AgentID'], $Result3UpNumber, '3Digi', 'CreditDown', $RateDown3, $Conn);
        $Reward3 = $RewardUp3 + $RewardDown3;
        $Percent3 = CalulatePercent($Credit3, $Reward3);
        $Benefit3 = $Credit3 - $Reward3;
         if($Benefit3 < 0){
            $htmlstyle3 = ' text-danger';
        }else{
            $htmlstyle3 = ' text-success';
        }
        $html = '
        <table class="table table-striped table-bordered table-hover table-data table-responsive" id="table-2digi-list">
            <thead>
                <tr>
                    <th width="20%" class="text-center">ยอดขาย 2 ตัวบน</th>
                    <th width="20%" class="text-center">ยอดขาย 2 ตัวล่าง</th>
                    <th width="20%" class="text-center">ยอดถูก 2 ตัวบน</th>
                    <th width="20%" class="text-center">ยอดถูก 2 ตัวล่าง</th>
                    <th width="20%" class="text-center">ผลกำไร</th>
                </tr>
            </thead>
            <tbody><tr>
            <td width="20%" class="text-center" data-th="ยอดขาย 2 ตัวบน">'.number_format($CreditUp2, 2, '.', ',').'</td>
            <td width="20%" class="text-center" data-th="ยอดขาย 2 ตัวล่าง">'.number_format($CreditDown2, 2, '.', ',').'</td>
            <td width="20%" class="text-center" data-th="ยอดถูก 2 ตัวบน">'.number_format($RewardUp2, 2, '.', ',').'</td>
            <td width="20%" class="text-center" data-th="ยอดถูก 2 ตัวล่าง">'.number_format($RewardDown2, 2, '.', ',').'</td>
            <td width="20%" class="text-center'.$htmlstyle2.'" data-th="ผลกำไร">'.number_format($Benefit2, 2, '.', ',').' ('.number_format($Percent2, 2, '.', ',').'%)</td>
            </tr>
            </tbody>
        </table>
        <br>
        <table class="table table-striped table-bordered table-hover table-data table-responsive" id="table-3digi-list">
            <thead>
                <tr>
                    <th width="20%" class="text-center">ยอดขาย 3 ตัวตรง</th>
                    <th width="20%" class="text-center">ยอดขาย 3 ตัวโต๊ด</th>
                    <th width="20%" class="text-center">ยอดถูก 3 ตัวตรง</th>
                    <th width="20%" class="text-center">ยอดถูก 3 ตัวโต๊ด</th>
                    <th width="20%" class="text-center">ผลกำไร</th>
                </tr>
            </thead>
            <tbody><tr>
            <td width="20%" class="text-center" data-th="ยอดขาย 3 ตัวตรง">'.number_format($CreditUp3, 2, '.', ',').'</td>
            <td width="20%" class="text-center" data-th="ยอดขาย 3 ตัวโต๊ด">'.number_format($CreditDown3, 2, '.', ',').'</td>
            <td width="20%" class="text-center" data-th="ยอดถูก 3 ตัวตรง">'.number_format($RewardUp3, 2, '.', ',').'</td>
            <td width="20%" class="text-center" data-th="ยอดถูก 3 ตัวโต๊ด">'.number_format($RewardDown3, 2, '.', ',').'</td>
            <td width="20%" class="text-center'.$htmlstyle3.'" data-th="ผลกำไร">'.number_format($Benefit3, 2, '.', ',').' ('.number_format($Percent3, 2, '.', ',').'%)</td>
            </tr>
            </tbody>
        </table>{}'.$Result2UpNumber.'{}'.$Result2DownNumber.'{}'.$Result3UpNumber;
        echo $html;
    }
}
?>