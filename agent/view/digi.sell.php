<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        if(isset($_POST['CRUD']) && $_POST['CRUD'] != ''){
            function ThdaiDateTime($DateTime, $ShowTime = 'Yes'){
                if($DateTime != ''){
                    $strYear = date("Y",strtotime($DateTime))+543;
                    $strMonth= date("n",strtotime($DateTime));
                    $strDay= date("j",strtotime($DateTime));
                    $strHour= date("H",strtotime($DateTime));
                    $strMinute= date("i",strtotime($DateTime));
                    $strSeconds= date("s",strtotime($DateTime));
                    $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
                    $strMonthThai=$strMonthCut[$strMonth];
                    if($ShowTime == 'Yes'){
                        return "$strDay $strMonthThai $strYear $strHour:$strMinute";
                    }else{
                        return "$strDay $strMonthThai $strYear";
                    }
                }
            }
            function ReadConfig($ConfigName, $Conn){
                $sql = "SELECT ConfigValue FROM ".TABLE_CONFIGURATION." WHERE ConfigName = '".$ConfigName."' limit 1";
                $result = $Conn->prepare($sql);
                $result->execute();
                if($result->rowCount() > 0){
                    $data = $result->fetchObject();
                    return $data->ConfigValue;

                }else{
                    return '';
                }
            }
            if($_POST['CRUD'] == 'Create'){}
            if($_POST['CRUD'] == 'Read'){
                if($_POST['View'] == "Session"){
                    $disabled = '';
                    if(ReadConfig('AcceptNumber', $Conn) != 'Yes'){
                        $disabled = ' disabled';
                    }
                    
                    if(isset($_SESSION['SessionIndex']) && $_SESSION['SessionIndex'] != ''){
                        $html = '';
                        $SessionListCount = 0;
                        $Totals = 0;
                        for($i=0;$i<=$_SESSION['SessionIndex'];$i++){
                            if(isset($_SESSION['SessionNumber'.$i]) && $_SESSION['SessionNumber'.$i] != ''){
                                $SessionListCount++;
                                $Totals = $Totals + $_SESSION['SessionCreditUp'.$i] + $_SESSION['SessionCreditDown'.$i];
                                $html .= '<tr>';
                                $html .= '<td class="text-center" data-th="งวดวันที่">'.ThdaiDateTime($_SESSION['SessionPeriodID'.$i], 'No').'</td>';
                                $html .= '<td class="text-center" data-th="ตัวเลข">'.$_SESSION['SessionNumber'.$i].'</td>';
                                $html .= '<td class="text-center" data-th="2 ตัวบน / 3 ตัวตรง">'.number_format($_SESSION['SessionCreditUp'.$i], 2, '.', ',').'</td>';
                                $html .= '<td class="text-center" data-th="2 ตัวล่าง / 3 ตัวโต๊ด">'.number_format($_SESSION['SessionCreditDown'.$i], 2, '.', ',').'</td>';
                                $html .= '<td class="text-center" data-th="เครื่องมือ">
                                        <button type="button" class="btn btn-outline btn-warning" onclick="CRUDModal(\'Update\', \''.$i.'\');">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline btn-danger" onclick="CRUDModal(\'Delete\', \''.$i.'\');">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </td>';
                                $html .= '</tr>'; 
                            }
                        }
                    }
                    
                    if($SessionListCount < 1){
                        $disabled = ' disabled';
                    }
                    $Totals = number_format($Totals, 2, '.', ',');
                    echo '
                    <div class="row">
                        <div class="col-xs-8 text-right"></div>
                        <div class="col-xs-4">
                            <div class="form-group">
                            <label>รวมทั้งหมด</label>
                            <input type="text" class="form-control text-right" placeholder="'.$Totals.'" name="Totals" id="Totals" value="'.$Totals.'" readonly>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-xs-12">
                    <table class="table table-striped table-bordered table-hover table-data table-responsive" id="table-sell-list">
                        <thead>
                            <tr>
                                <th class="text-center">งวดวันที่</th>
                                <th class="text-center">ตัวเลข</th>
                                <th class="text-center">2 ตัวบน / 3 ตัวตรง</th>
                                <th class="text-center">2 ตัวล่าง / 3 ตัวโต๊ด</th>
                                <th class="text-center">เครื่องมือ</th>
                        </thead>
                        <tbody>'.$html.'
                        </tbody>
                    </table>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <button type="button" class="btn btn-primary btn-flat" id="btn-save-database"'.$disabled.' onclick="CRUDData(\'Create\', \'Database\');">บันทึกรายการขาย</button>
                        </div>
                    </div>';
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