<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        $_SESSION['PDFPeriodID'] = $_POST['PeriodID'];
        function DateTimeThai($strDate, $ShowTime = 'No'){
            $strYear = date("Y",strtotime($strDate))+543;
            $strMonth = date("n",strtotime($strDate));
            $strDay = date("j",strtotime($strDate));
            $strHour = date("H",strtotime($strDate));
            $strMinute = date("i",strtotime($strDate));
            $strSeconds = date("s",strtotime($strDate));
            $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
            $strMonthThai=$strMonthCut[$strMonth];
            if($ShowTime == 'Yes'){
                return "$strDay $strMonthThai $strYear $strHour:$strMinute";   
            }else{
                return "$strDay $strMonthThai $strYear";
            }
        }
        $html = '
        <table class="table table-striped table-bordered table-hover table-data table-responsive" id="table-report-history">
            <thead>
                <tr>
                    <th width="20%" class="text-center">งวดวันที่</th>
                    <th width="20%" class="text-center">วันเวลาที่ส่ง</th>
                    <th width="20%" class="text-center">ตัวเลข</th>
                    <th width="20%" class="text-center">2 ตัวบน / 3 ตัวตรง</th>
                    <th width="20%" class="text-center">2 ตัวล่าง / 3 ตัวโต๊ด</th>
                </tr>
            </thead>
            <tbody>';
            $sql = "SELECT * FROM ".TABLE_NUMBERS_DETAIL." WHERE AgentID = '".$_SESSION['AgentID']."' AND PeriodID = '".$_POST['PeriodID']."' ORDER BY DateTime DESC";
            $result = $Conn->prepare($sql);
            $result->execute();
            if($result->rowCount() > 0){
                while($data = $result->fetchObject()){
                    $html .= '
            <tr>
            <td width="20%" class="text-center" data-th="งวดวันที่">'.DateTimeThai($data->PeriodID, 'No').'</td>
            <td width="20%" class="text-center" data-th="วันเวลาที่ส่ง">'.DateTimeThai($data->DateTime, 'Yes').'</td>
            <td width="20%" class="text-center" data-th="ตัวเลข">'.$data->Numbers.'</td>
            <td width="20%" class="text-center" data-th="2 ตัวบน / 3 ตัวตรง">'.number_format($data->CreditUp, 2, '.', ',').'</td>
            <td width="20%" class="text-center" data-th="2 ตัวล่าง / 3 ตัวโต๊ด">'.number_format($data->CreditDown, 2, '.', ',').'</td>
            </tr>';
                }
            }
        $html .= '
            </tbody>
        </table>';
        echo $html;
    }
}
?>