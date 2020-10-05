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
                    
                    $sql = "UPDATE ".TABLE_PERIOD." SET Status = 'Close' WHERE Status = 'Open' AND AcceptExpireTime < '".date("Y-m-d H:i:s")."'";
                    $result = $Conn->prepare($sql);
                    $result->execute();
                    
                    echo '
                    <table class="table table-striped table-bordered table-hover table-data table-responsive" id="table-period-list">
                        <thead>
                            <tr>
                                <th class="text-center">งวดวันที่</th>
                                <th class="text-center">วันที่ปิดรับ</th>
                                <th class="text-center">สถานะ</th>
                                <th class="text-center">เครื่องมือ</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $sql = "SELECT * FROM ".TABLE_PERIOD." ORDER BY PeriodID DESC";
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        if($result->rowCount() > 0){
                            while($data = $result->fetchObject()){
                                echo '<tr>';
                                echo "<td class=\"text-center\" data-th=\"งวดวันที่\">".DateThai($data->PeriodID, 'No')."</td>";
                                echo "<td class=\"text-center\" data-th=\"วันที่ปิดรับ\">".DateThai($data->AcceptExpireTime, 'Yes')."</td>";
                                if($data->Status == "Open"){
                                    $Status = '<div class="text-success">เปิดรับแทง</div>';
                                }else{
                                    $Status = '<div class="text-danger">ปิดรับแทง</div>';
                                }
                                echo "<td class=\"text-center\" data-th=\"สถานะ\">$Status</td>";
                                echo "<td class=\"text-center\" data-th=\"เครื่องมือ\">
                                    <button type=\"button\" class=\"btn btn-outline btn-warning\" onclick=\"CRUDModal('Update', '".$data->PeriodID."');\">
                                        <i class=\"fa fa-pencil\"></i>
                                    </button>
                                    <button type=\"button\" class=\"btn btn-outline btn-info\" disabled>
                                        <i class=\"fa fa-info-circle\"></i>
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