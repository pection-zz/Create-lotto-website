<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        function GetRate($RateName, $Conn){
            $sql = "SELECT Rate FROM ".TABLE_RATE." WHERE RateName = '".$RateName."' LIMIT 1";
            $result = $Conn->prepare($sql);
            $result->execute();
			if($result->rowCount() > 0){
                $data = $result->fetchObject();
                return $data->Rate;
            }else{
                return 0;
            }
        }
?>
<form role="form" accept-charset="UTF-8" id="ilotto-form" name="ilotto-form" method="post" action="">
    <div class="row">
        <div class="col-sm-10 col-lg-10"></div>
        <div class="col-sm-2 col-lg-2">
            <div class="form-group">
            <label>สถานะการรับแทง</label>
            <select name="AcceptNumber" id="AcceptNumber" class="form-control" style="cursor: pointer;width: 100%;">';
                <?php
                    $sql = "SELECT ConfigValue FROM ".TABLE_CONFIGURATION." WHERE ConfigName = 'AcceptNumber' LIMIT 1";
                    $result = $Conn->prepare($sql);
                    $result->execute();
                    if($result->rowCount() > 0){
                        while($data = $result->fetchObject()){
                            if($data->ConfigValue == "Yes"){
                                $select = '<option value="Yes" selected>เปิดรับแทง</option><option value="No">ปิดรับแทง</option>';
                            }else{
                                $select = '<option value="Yes">เปิดรับแทง</option><option value="No" selected>ปิดรับแทง</option>';
                            }
                        }
                    }else{
                        $select = '<option value="Yes" selected>เปิดรับแทง</option><option value="No">ปิดรับแทง</option>';
                    }
                    echo $select;
                ?>
            </select>
        </div>
        </div>
    </div>
    <div class="row">
        <?php 
            $Rate2DigiUp = GetRate('2DigiUp', $Conn);
            $Rate2DigiDown = GetRate('2DigiDown', $Conn);
            $Rate3DigiUp = GetRate('3DigiUp', $Conn);
            $Rate3DigiDown = GetRate('3DigiDown', $Conn);
        ?>
        <div class="col-sm-3 col-lg-3">
            <div class="form-group">
                <label>อัตราการจ่ายรางวัล 2 ตัวบน</label>
                <input type="text" class="form-control text-right" placeholder="<?php echo $Rate2DigiUp; ?>" name="Rate2DigiUp" id="Rate2DigiUp" value="<?php echo $Rate2DigiUp; ?>">
            </div>
        </div>
        <div class="col-sm-3 col-lg-3">
            <div class="form-group">
                <label>อัตราการจ่ายรางวัล 2 ตัวล่าง</label>
                <input type="text" class="form-control text-right" placeholder="<?php echo $Rate2DigiDown; ?>" name="Rate2DigiDown" id="Rate2DigiDown" value="<?php echo $Rate2DigiDown; ?>">
            </div>
        </div>
        <div class="col-sm-3 col-lg-3">
            <div class="form-group">
                <label>อัตราการจ่ายรางวัล 3 ตัวตรง</label>
                <input type="text" class="form-control text-right" placeholder="<?php echo $Rate3DigiUp; ?>" name="Rate3DigiUp" id="Rate3DigiUp" value="<?php echo $Rate3DigiUp; ?>">
            </div>
        </div>
        <div class="col-sm-3 col-lg-3">
            <div class="form-group">
                <label>อัตราการจ่ายรางวัล 3 ตัวโต๊ด</label>
                <input type="text" class="form-control text-right" placeholder="<?php echo $Rate3DigiDown; ?>" name="Rate3DigiDown" id="Rate3DigiDown" value="<?php echo $Rate3DigiDown; ?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-lg-12 text-danger"> * อัตราการจ่ายรางวัลเทียบกับเงินซื้อ 1 บาท</div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-lg-12 text-right">
            <button type="button" id="btn-submit" class="btn btn-primary btn-flat" onclick="DoSubmit();">บันทึก</button>
        </div>
    </div>
</form>
<?php
    }
}
?>