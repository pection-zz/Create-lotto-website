<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
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
        if(isset($_POST['CRUD'])){
            if($_POST['CRUD'] == 'Create'){}
            if($_POST['CRUD'] == 'Read'){}
            if($_POST['CRUD'] == 'Update'){
                $content = '
                <div class="row">
                    <div class="col-xs-8 col-lg-8">
                        <input type="hidden" name="CRUD" id="CRUD" value="Update">
                        <input type="hidden" name="Position" id="Position" value="'.$_POST['Position'].'">
                    </div>
                    <div class="col-xs-4 col-lg-4">
                        <div class="form-group">
                            <label>งวดวันที่</label>
                            <input type="text" class="form-control" placeholder="งวดวันที่" value="'.ThdaiDateTime($_SESSION['SessionPeriodID'.$_POST['Position']], 'No').'">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4 col-lg-4">
                        <div class="form-group">
                            <label>ตัวเลข</label>
                            <input type="text" class="form-control" placeholder="ตัวเลข" value="'.$_SESSION['SessionNumber'.$_POST['Position']].'" readonly>
                        </div>
                    </div>
                    <div class="col-xs-4 col-lg-4">
                        <div class="form-group">
                            <label>2 ตัวบน / 3 ตัวตรง</label>
                            <input type="text" class="form-control" placeholder="2 ตัวบน / 3 ตัวตรง" name="UpdateCreditUp" id="UpdateCreditUp" value="'.$_SESSION['SessionCreditUp'.$_POST['Position']].'">
                        </div>
                    </div>
                    <div class="col-xs-4 col-lg-4">
                        <div class="form-group">
                            <label>2 ตัวล่าง / 3 ตัวโต๊ด</label>
                            <input type="text" class="form-control" placeholder="2 ตัวล่าง / 3 ตัวโต๊ด" name="UpdateCreditDown" id="UpdateCreditDown" value="'.$_SESSION['SessionCreditDown'.$_POST['Position']].'">
                        </div>
                    </div>
                </div>';
                $button = '
                <div class="row">
                    <div class="col-sx-9 col-lg-9 text-left" id="modal-status-text"></div>
                    <div class="col-sx-3 col-lg-3 text-right">
                        <button type="button" id="btn-update" class="btn btn-warning btn-flat">ปรับปรุงข้อมูล</button>
                    </div>
                </div>';
            }
            if($_POST['CRUD'] == 'Delete'){
                $content = '
                <div class="row">
                    <div class="col-xs-8 col-lg-8">
                        <input type="hidden" name="CRUD" id="CRUD" value="Delete">
                        <input type="hidden" name="Position" id="Position" value="'.$_POST['Position'].'">
                    </div>
                    <div class="col-xs-4 col-lg-4">
                        <div class="form-group">
                            <label>งวดวันที่</label>
                            <input type="text" class="form-control" placeholder="งวดวันที่" value="'.ThdaiDateTime($_SESSION['SessionPeriodID'.$_POST['Position']], 'No').'" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4 col-lg-4">
                        <div class="form-group">
                            <label>ตัวเลข</label>
                            <input type="text" class="form-control" placeholder="ตัวเลข" value="'.$_SESSION['SessionNumber'.$_POST['Position']].'" readonly>
                        </div>
                    </div>
                    <div class="col-xs-4 col-lg-4">
                        <div class="form-group">
                            <label>2 ตัวบน / 3 ตัวตรง</label>
                            <input type="text" class="form-control" placeholder="2 ตัวบน / 3 ตัวตรง" value="'.$_SESSION['SessionCreditUp'.$_POST['Position']].'" readonly>
                        </div>
                    </div>
                    <div class="col-xs-4 col-lg-4">
                        <div class="form-group">
                            <label>2 ตัวล่าง / 3 ตัวโต๊ด</label>
                            <input type="text" class="form-control" placeholder="2 ตัวล่าง / 3 ตัวโต๊ด" value="'.$_SESSION['SessionCreditDown'.$_POST['Position']].'" readonly>
                        </div>
                    </div>
                </div>';
                $button = '
                <div class="row">
                    <div class="col-sx-9 col-lg-9 text-left" id="modal-status-text"></div>
                    <div class="col-sx-3 col-lg-3 text-right">
                        <button type="button" id="btn-delete" class="btn btn-danger btn-flat">ลบข้อมูล</button>
                    </div>
                </div>';
            }
        }else{
            // ข้อความแจ้งความผิดพลาดไปยัง Modal
        }
?>
<form role="form" accept-charset="UTF-8" id="ilotto-form" name="ilotto-form" method="post" action="">
    <?php echo $content; ?>
    <?php echo $button; ?>                   
</form>
<?php
    }
}
?>