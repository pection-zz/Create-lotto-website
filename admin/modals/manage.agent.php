<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        if(isset($_POST['CRUD'])){
            function BuildDropDown($Conn, $Name = "", $IsUnlimit, $BankID = "", $AccountNumber = "", $AccountName = ""){
                $bank = '
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
				            <label>เครดิตไม่จำกัด</label>
				            <select name="IsUnlimit" id="IsUnlimit" class="form-control select2" style="width: 100%;">';
                if($IsUnlimit == "Yes"){
                    $bank .= '<option value="Yes" selected>ใช่</option><option value="No">ไม่</option>';
                }else{
                    $bank .= '<option value="Yes">ใช่</option><option value="No" selected>ไม่</option>';
                }
                $bank .= '
				            </select>
			             </div>
		              </div>    
		              <div class="col-lg-6">
                        <div class="form-group">
                            <label>ธนาคาร</label>
                            <select name="'.$Name.'" id="'.$Name.'" class="form-control select2" style="width: 100%;">';
                $sql = "SELECT * FROM ".TABLE_BANK;
                $result = $Conn->prepare($sql);
                $result->execute();
                if($result->rowCount() > 0){
                    while($data = $result->fetchObject()){
                        if($BankID != ""){
                            if($BankID == $data->BankID){
                                $bank .= '<option value="'.$data->BankID.'" selected>'.$data->BankThaiName.' ('.$data->BankEnglishName.')</option>';
                            }else{
                                $bank .= '<option value="'.$data->BankID.'">'.$data->BankThaiName.' ('.$data->BankEnglishName.')</option>';
                            }
                        }else{
                            $bank .= '<option value="'.$data->BankID.'">'.$data->BankThaiName.' ('.$data->BankEnglishName.')</option>';
                        }
                    }
                }
                $bank .= '
                            </select>
                        </div>
                    </div>
</div>';
                if($AccountNumber == ""){
                    $bank .= '
		    <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>หมายเลขบัญชี</label>
                            <input type="text" class="form-control" placeholder="หมายเลขบัญชี" name="AccountNumber" id="AccountNumber">
                        </div>
                    </div>';
                }else{
                    $bank .= '
                    <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>หมายเลขบัญชี</label>
                            <input type="text" class="form-control" placeholder="'.$AccountNumber.'" name="AccountNumber" id="AccountNumber" value="'.$AccountNumber.'">
                        </div>
                    </div>';
                }
                if($AccountName == ""){
                    $bank .= '
			<div class="col-lg-6">
                            <div class="form-group">
                                <label>ชื่อบัญชี</label>
                                <input type="text" class="form-control" placeholder="ชื่อบัญชี" name="AccountName" id="AccountName">
                            </div>
                        </div>
                    </div>';   
                }else{
                    $bank .= '
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ชื่อบัญชี</label>
                                <input type="text" class="form-control" placeholder="'.$AccountName.'" name="AccountName" id="AccountName" value="'.$AccountName.'">
                            </div>
                        </div>
                    </div>';
                }
                return $bank;
            }
            if($_POST['CRUD'] == 'Create'){
                $user = '
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <input type="hidden" name="CRUD" id="CRUD" value="Create">
                                <label>ชื่อ - นามสกุล</label>
                                <input type="text" class="form-control" placeholder="ชื่อ - นามสกุล" name="Fullname" id="Fullname">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ชื่อผู้ใช้</label>
                                <input type="text" class="form-control" placeholder="ชื่อผู้ใช้" name="Username" id="Username">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>รหัสผ่าน</label>
                                <input type="password" class="form-control" placeholder="รหัสผ่าน" name="Password1" id="Password1">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>รหัสผ่าน (อีกครั้ง)</label>
                                <input type="password" class="form-control" placeholder="รหัสผ่าน" name="Password2" id="Password2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>อีเมล</label>
                                <input type="text" class="form-control" placeholder="อีเมล" name="Email1" id="Email1">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>อีเมล (อีกครั้ง)</label>
                                <input type="text" class="form-control" placeholder="อีเมล" name="Email2" id="Email2">
                            </div>
                        </div>
                    </div>';
                $commission = '
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>ค่าคอม 2 ตัวบน (%)</label>
                            <input type="text" class="form-control" placeholder="ค่าคอม 2 ตัวบน" name="Com2DigiUp" id="Com2DigiUp" value="0">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>ค่าคอม 2 ตัวล่าง (%)</label>
                            <input type="text" class="form-control" placeholder="0.00" name="Com2DigiDown" id="Com2DigiDown" value="0">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>ค่าคอม 3 ตัวตรง (%)</label>
                            <input type="text" class="form-control" placeholder="" name="Com3DigiUp" id="Com3DigiUp" value="0">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>ค่าคอม 3 ตัวโต๊ด (%)</label>
                            <input type="text" class="form-control" placeholder="" name="Com3DigiDown" id="Com3DigiDown" value="0">
                        </div>
                    </div>
                </div>';
                $bank = BuildDropDown($Conn, 'BankID', 'Yes', '', '', '');
                $contact = '
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>ที่อยู่</label>
                            <input type="text" class="form-control" placeholder="ที่อยู่" name="Address" id="Address">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>หมายเลขโทรศัพท์</label>
                            <input type="text" class="form-control" placeholder="หมายเลขโทรศัพท์" name="Telephone" id="Telephone">
                        </div>
                    </div>
                </div>';
                $button = '
                <div class="row">
                    <div class="col-lg-9 text-left" id="modal-status-text"></div>
                    <div class="col-lg-3 text-right">
                        <button type="submit" id="btn-submit" class="btn btn-primary btn-flat">บันทึก</button>
                    </div>
                </div>';
            }
            if($_POST['CRUD'] == 'Read'){}
            if($_POST['CRUD'] == 'Update' && isset($_POST['AgentID']) && $_POST['AgentID'] != ""){
                $sql = "SELECT * FROM ".TABLE_AGENT." WHERE AgentID = '".$_POST['AgentID']."' LIMIT 1";
                $result = $Conn->prepare($sql);
                $result->execute();
                if($result->rowCount() > 0){
                    $data = $result->fetchObject();
                    if($data->IsActive == 'Yes'){
                        $select = "<option value='Yes' selected>ปกติ</option><option value='No'>ระงับการใช้งาน</option>";
                    }else{
                        $select = "<option value='Yes'>ปกติ</option><option value='No' selected>ระงับการใช้งาน</option>";
                    }
                    $user = '
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>รหัสตัวแทนจำหน่าย</label>
                                <input type="hidden" name="CRUD" id="CRUD" value="Update">
                                <input type="hidden" name="AgentID" id="AgentID" value="'.$data->AgentID.'">
                                <input type="text" class="form-control" placeholder="'.$data->AgentID.'" value="'.$data->AgentID.'" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>สถานะ</label>
                                <select name="IsActive" id="IsActive" class="form-control select2" style="cursor: pointer;width: 100%;">'.$select.'
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ชื่อผู้ใช้</label>
                                <input type="text" class="form-control" placeholder="'.$data->Username.'" name="Username" id="Username" value="'.$data->Username.'" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>รหัสผ่าน</label>
                                <input type="text" class="form-control" placeholder="รหัสผ่าน" name="Password" id="Password">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ชื่อ - นามสกุล</label>
                                <input type="text" class="form-control" placeholder="'.$data->FullName.'" name="Fullname" id="Fullname" value="'.$data->FullName.'">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>อีเมล</label>
                                <input type="text" class="form-control" placeholder="'.$data->Email.'" name="Email" id="Email" value="'.$data->Email.'">
                            </div>
                        </div>
                    </div>';
                    $commission = '
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ค่าคอม 2 ตัวบน (%)</label>
                                <input type="text" class="form-control" placeholder="ค่าคอม 2 ตัวบน" name="Com2DigiUp" id="Com2DigiUp" value="'.$data->Com2DigiUp.'">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ค่าคอม 2 ตัวล่าง (%)</label>
                                <input type="text" class="form-control" placeholder="0.00" name="Com2DigiDown" id="Com2DigiDown" value="'.$data->Com2DigiDown.'">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ค่าคอม 3 ตัวตรง (%)</label>
                                <input type="text" class="form-control" placeholder="" name="Com3DigiUp" id="Com3DigiUp" value="'.$data->Com3DigiUp.'">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ค่าคอม 3 ตัวโต๊ด (%)</label>
                                <input type="text" class="form-control" placeholder="หมายเลขโทรศัพท์" name="Com3DigiDown" id="Com3DigiDown" value="'.$data->Com3DigiDown.'">
                            </div>
                        </div>
                    </div>';
                    $bank = BuildDropDown($Conn, 'BankID', $data->IsUnlimit, $data->BankID, $data->AccountNumber, $data->AccountName);
                    $contact = '
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ที่อยู่</label>
                                <input type="text" class="form-control" placeholder="ที่อยู่" name="Address" id="Address" value="'.$data->Address.'">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>หมายเลขโทรศัพท์</label>
                                <input type="text" class="form-control" placeholder="หมายเลขโทรศัพท์" name="Telephone" id="Telephone" value="'.$data->Telephone.'">
                            </div>
                        </div>
                    </div>';
                    $button = '
                    <div class="row">
                        <div class="col-lg-9 text-left" id="modal-status-text"></div>
                        <div class="col-lg-3 text-right">
                            <button type="submit" id="btn-submit" class="btn btn-warning btn-flat">ปรับปรุง</button>
                        </div>
                    </div>';
                }else{
                    $user = '<br><div class="text-center text-danger"><i class="fa fa-times fa-fw"></i> ผิดพลาด ไม่พบข้อมูล</div>';
                    $commission = '<br><div class="text-center text-danger"><i class="fa fa-times fa-fw"></i> ผิดพลาด ไม่พบข้อมูล</div>';
                    $bank = '<br><div class="text-center text-danger"><i class="fa fa-times fa-fw"></i> ผิดพลาด ไม่พบข้อมูล</div>';
                    $contact = '<br><div class="text-center text-danger"><i class="fa fa-times fa-fw"></i> ผิดพลาด ไม่พบข้อมูล</div>';
                }
            }
            if($_POST['CRUD'] == 'Delete'){}
?>
<form role="form" accept-charset="UTF-8" id="ilotto-form" name="ilotto-form" method="post" action="">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#user" data-toggle="tab">บัญชีผู้ใช้</a></li>
        <li><a href="#commission" data-toggle="tab">ค่าคอมมิสชั่น</a></li>
        <li><a href="#bank" data-toggle="tab">การเงิน</a></li>
        <li><a href="#contact" data-toggle="tab">ติดต่อ</a></li>
    </ul>
    <div class="tab-content">
            <div class="tab-pane fade in active" id="user">
                <p><?php echo $user; ?></p>
            </div>
            <div class="tab-pane fade" id="commission">
                <p><?php echo $commission; ?></p>
            </div>
            <div class="tab-pane fade" id="bank">
                <p><?php echo $bank; ?></p>
            </div>
            <div class="tab-pane fade" id="contact">
                <p><?php echo $contact; ?></p>
            </div>
    </div>
    <?php echo $button; ?>                   
</form>
<?php
        }
    }
}
?>
