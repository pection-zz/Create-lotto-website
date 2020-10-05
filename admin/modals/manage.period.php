<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        if(isset($_POST['CRUD'])){
            if($_POST['CRUD'] == 'Create'){
                $content = '
                <div class="row">
                    <div class="col-sx-6 col-lg-6"></div>
                    <div class="col-sx-6 col-lg-6">
                        <div class="form-group">
                            <input type="hidden" name="CRUD" id="CRUD" value="Create">
                            <label>สถานะการเปิดรับแทง</label>
                            <select name="PeriodStatus" id="PeriodStatus" class="form-control select2" style="cursor: pointer;width: 100%;">
                                <option value="Close">ปิดรับแทง</option>
                                <option value="Open" selected>เปิดรับแทง</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-6 col-lg-6">
                        <div class="form-group">
                            <label>งวดวันที่</label>
                            <input type="text" class="form-control" placeholder="งวดวันที่" name="PeriodID" id="PeriodID" style="cursor:pointer" readonly>
                        </div>
                    </div>
                    <div class="col-sx-6 col-lg-6">
                        <div class="form-group">
                            <label>วันที่ปิดรับ</label>
                            <input type="text" class="form-control" placeholder="วันที่ปิดรับ" name="AcceptExpireTime" id="AcceptExpireTime" style="cursor:pointer" readonly>
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
                $sql = "SELECT * FROM ".TABLE_PERIOD." WHERE PeriodID = '".$_POST['PeriodID']."' LIMIT 1";
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
                                <input type="hidden" name="AcceptExpireTime" id="AcceptExpireTime" value="'.$data->AcceptExpireTime.'">
                                <label>สถานะการเปิดรับแทง</label>
                                <select name="PeriodStatus" id="PeriodStatus" class="form-control select2" style="cursor: pointer;width: 100%;">';
                                        if($data->Status == "Open"){
                                            $content .= '
                                            <option value="Close">ปิดรับแทง</option>
                                            <option value="Open" selected>เปิดรับแทง</option>';
                                        }else{
                                            $content .= '
                                            <option value="Close" selected>ปิดรับแทง</option>
                                            <option value="Open">เปิดรับแทง</option>';
                                        }
                                $content .= '
                                </select>
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
                            <button type="submit" id="btn-submit" class="btn btn-warning btn-flat">บันทึก</button>
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
                                <input type="text" class="form-control" name="PeriodStatus" id="PeriodStatus" placeholder="สถานะการเปิดรับแทง" value="'.$Status.'" readonly>
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