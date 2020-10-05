<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        if(isset($_POST['CRUD'])){
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
            if($_POST['CRUD'] == 'Create'){
                $Select = '';
                $sql = "SELECT PeriodID FROM ".TABLE_PERIOD." WHERE AcceptExpireTime < '".date("Y-m-d H:i:s")."' AND Status = 'Close' AND IsResults = 'No' ORDER BY PeriodID DESC";
                $result = $Conn->prepare($sql);
                $result->execute();
                if($result->rowCount() > 0){
                    $data = $result->fetchObject();
                    $Select .= '<option value="'.$data->PeriodID.'">'.DateThai($data->PeriodID, 'No').'</option>';
                }
                $content = '
                <div class="row">
                    <div class="col-sx-6 col-lg-6"></div>
                    <div class="col-sx-6 col-lg-6">
                        <div class="form-group">
                            <input type="hidden" name="CRUD" id="CRUD" value="Create">
                            <label>งวดวันที่</label>
                            <select name="PeriodID" id="PeriodID" class="form-control select2" style="cursor: pointer;width: 100%;">'.$Select.'
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-4 col-lg-4">
                        <div class="form-group">
                            <label>2 ตัวบน</label>
                            <input type="text" class="form-control" placeholder="2 ตัวบน" name="Digi2Up" id="Digi2Up" maxlength="2">
                        </div>
                    </div>
                    <div class="col-sx-4 col-lg-4">
                        <div class="form-group">
                            <label>2 ตัวล่าง</label>
                            <input type="text" class="form-control" placeholder="2 ตัวล่าง" name="Digi2Down" id="Digi2Down" maxlength="2">
                        </div>
                    </div>
                    <div class="col-sx-4 col-lg-4">
                        <div class="form-group">
                            <label>3 ตัวตรง</label>
                            <input type="text" class="form-control" placeholder="3 ตัวตรง" name="Digi3" id="Digi3" maxlength="3">
                        </div>
                    </div>
                </div>';
                $button = '
                <div class="row">
                    <div class="col-sx-9 col-lg-9 text-left" id="modal-status-text"></div>
                    <div class="col-sx-3 col-lg-3 text-right">
                        <button type="submit" id="btn-submit" class="btn btn-primary btn-flat">บันทึก</button>
                    </div>
                </div>';
            }
            if($_POST['CRUD'] == 'Read'){}
            if($_POST['CRUD'] == 'Update' && isset($_POST['PeriodID']) && $_POST['PeriodID'] != ""){
                $sql = "SELECT * FROM ".TABLE_RESULTS." WHERE PeriodID = '".$_POST['PeriodID']."' LIMIT 1";
                $result = $Conn->prepare($sql);
                $result->execute();
                if($result->rowCount() > 0){
                    $data = $result->fetchObject();
                    $content = '
                <div class="row">
                    <div class="col-sx-6 col-lg-6"></div>
                    <div class="col-sx-6 col-lg-6">
                        <div class="form-group">
                            <input type="hidden" name="CRUD" id="CRUD" value="Update">
                            <input type="hidden" name="PeriodID" id="PeriodID" value="'.$data->PeriodID.'">
                            <label>งวดวันที่</label>
                            <input type="text" class="form-control" placeholder="'.DateThai($data->PeriodID, 'No').'" value="'.DateThai($data->PeriodID, 'No').'" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-4 col-lg-4">
                        <div class="form-group">
                            <label>2 ตัวบน</label>
                            <input type="text" class="form-control" placeholder="'.$data->Digi2Up.'" name="Digi2Up" id="Digi2Up" maxlength="2" value="'.$data->Digi2Up.'">
                        </div>
                    </div>
                    <div class="col-sx-4 col-lg-4">
                        <div class="form-group">
                            <label>2 ตัวล่าง</label>
                            <input type="text" class="form-control" placeholder="'.$data->Digi2Down.'" name="Digi2Down" id="Digi2Down" maxlength="2" value="'.$data->Digi2Down.'">
                        </div>
                    </div>
                    <div class="col-sx-4 col-lg-4">
                        <div class="form-group">
                            <label>3 ตัวตรง</label>
                            <input type="text" class="form-control" placeholder="'.$data->Digi3.'" name="Digi3" id="Digi3" maxlength="3" value="'.$data->Digi3.'">
                        </div>
                    </div>
                </div>';
                    $button = '
                    <div class="row">
                        <div class="col-sx-9 col-lg-9 text-left" id="modal-status-text"></div>
                        <div class="col-sx-3 col-lg-3 text-right">
                            <button type="submit" id="btn-submit" class="btn btn-warning btn-flat">ปรับปรุง</button>
                        </div>
                    </div>';
                }else{
                    $content = '<br><div class="text-center text-danger"><i class="fa fa-times fa-fw"></i> ผิดพลาด ไม่พบข้อมูล</div>';
                }
            }
            if($_POST['CRUD'] == 'Delete' && isset($_POST['PeriodID']) && $_POST['PeriodID'] != ""){
                $sql = "SELECT * FROM ".TABLE_PERIOD." WHERE PeriodID = '".$_POST['PeriodID']."' LIMIT 1";
                $result = $Conn->prepare($sql);
                $result->execute();
                if($result->rowCount() > 0){
                    $data = $result->fetchObject();
                    if($data->Status == "Open"){
                        $Status = 'เปิดรับแทง';
                    }else{
                        $Status = 'ปิดรับแทง';
                    }
                    $content = '
                    <div class="row">
                        <div class="col-sx-6 col-lg-6"></div>
                        <div class="col-sx-6 col-lg-6">
                            <div class="form-group">
                                <input type="hidden" name="CRUD" id="CRUD" value="Delete">
                                <input type="hidden" name="PeriodID" id="PeriodID" value="'.$data->PeriodID.'">
                                <label>สถานะการเปิดรับแทง</label>
                                <input type="text" class="form-control" name="Status" placeholder="สถานะการเปิดรับแทง" value="'.$Status.'" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-6 col-lg-6">
                            <div class="form-group">
                                <label>งวดวันที่</label>
                                <input type="text" class="form-control" placeholder="งวดวันที่" value="'.$data->PeriodID.'" readonly>
                            </div>
                        </div>
                        <div class="col-sx-6 col-lg-6">
                            <div class="form-group">
                                <label>วันที่ปิดรับ</label>
                                <input type="text" class="form-control" placeholder="วันที่ปิดรับ" value="'.$data->AcceptExpireTime.'" readonly>
                            </div>
                        </div>
                        </div>
                    </div>';
                    $button = '
                    <div class="row">
                        <div class="col-sx-9 col-lg-9 text-left" id="modal-status-text"></div>
                        <div class="col-sx-3 col-lg-3 text-right">
                            <button type="submit" id="btn-submit" class="btn btn-danger btn-flat">ลบข้อมูล</button>
                        </div>
                    </div>';
                }else{
                    $content = '<br><div class="text-center text-danger"><i class="fa fa-times fa-fw"></i> ผิดพลาด ไม่พบข้อมูล</div>';
                }
            }
?>
<form role="form" accept-charset="UTF-8" id="ilotto-form" name="ilotto-form" method="post" action="">
    <?php
            echo $content;
            echo $button; 
    ?>                   
</form>
<?php
        }
    }
}
?>