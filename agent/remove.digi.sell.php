<?php
require_once('../common/assets/includes/session.php');
require_once('../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../common/assets/includes/tables.php');
	require_once('assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) != true){
		include_once('login.php');
	}else{
        $Security->UserOnline($Conn);
        require_once('assets/class/layout.php');
        $Layout = new Layout();
        // เริ่มต้นการประกาศฟังก์ชั่น
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
        // สิ้นสุดการประกาศฟังก์ชั่น
        
        // เริ่มต้นการอัพเดตงวดที่เกินเวลา
        $sql = "UPDATE ".TABLE_PERIOD." SET Status = 'Close' WHERE Status = 'Open' AND AcceptExpireTime < '".date("Y-m-d H:i:s")."'";
        $result = $Conn->prepare($sql);
        $result->execute();
        // สิ้นสุดการอัพเดตงวดที่เกินเวลา
        
        $disabled = '';
        if(ReadConfig('AcceptNumber', $Conn) != 'Yes'){
            $disabled = ' disabled';
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php  
        $Layout->Navigation('html-meta');
        $Layout->Navigation('html-icon');
        $Layout->Navigation('html-css');
    ?>
    <link rel="stylesheet" type="text/css" href="../common/assets/plugins/datatables/datatables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../common/assets/plugins/datatables/datatables.responsive.css">
    <link rel="stylesheet" type="text/css" href="../common/assets/plugins/datatables/buttons.datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../common/assets/plugins/datatables/buttons.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../common/assets/plugins/modal/bootstrap.modal.patch.css">
    <link rel="stylesheet" type="text/css" href="../common/assets/plugins/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../common/assets/css/table.responsive.css">
    <link rel="stylesheet" type="text/css" href="../common/assets/css/ilotto.form.css">
    <!--[if lt IE 9]>
        <script type="text/javascript" src="assets/js/html5shiv.js"></script>
        <script type="text/javascript" src="assets/js/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .table tbody>tr>td{
            vertical-align: middle;
        }
    </style>
    <title>iLotto Agent System</title>
</head>
<body>
    <div id="page-loading"></div>
    <div class="page-contain">
        <div id="wrapper">
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <?php 
                    $Layout->Navigation('navbar-header');
                    $Layout->Navigation('fav-icon');
                    $Layout->Navigation('navbar-static-side');
                ?>
            </nav>
            <div id="page-wrapper">
                <br>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-yellow">
                            <div class="panel-heading"><i class="fa fa-shopping-cart fa-fw"></i> งานขาย</div>
                            <div class="panel-body table-responsive">
                                <div class="row">
                                    <div class="col-xs-3">งวดวันที่</div>
                                    <div class="col-xs-2">ตัวเลข</div>
                                    <div class="col-xs-2">2 ตัวบน / 3 ตัวตรง</div>
                                    <div class="col-xs-2">2 ตัวล่าง / 3 ตัวโต๊ด</div>
                                    <div class="col-xs-3"></div>
                                </div>
                                <div class="row">
                                    <form id="session-form" name="session-form" method="post" action="">
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <input type="hidden" name="CRUD" id="CRUD" value="Create">
                                                <select name="PeriodID" id="PeriodID" class="form-control" style="cursor: pointer;width: 100%;">';
                                                    <?php
                                                        $select = '';
                                                        $sql = "SELECT * FROM ".TABLE_PERIOD." WHERE AcceptExpireTime > '".date("Y-m-d H:i:s")."' AND Status = 'Open'";
                                                        $result = $Conn->prepare($sql);
                                                        $result->execute();
                                                        if($result->rowCount() > 0){
                                                            while($data = $result->fetchObject()){
                                                                $select .= '<option value="'.$data->PeriodID.'">'.ThdaiDateTime($data->PeriodID, 'No').'</option>';
                                                            }
                                                        }
                                                        echo $select;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="ตัวเลข" name="Number" id="Number" maxlength="3" <?php echo $disabled; ?>>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="จำนวนเงิน" name="MoneyUpper" id="MoneyUpper" maxlength="6" <?php echo $disabled; ?>>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="จำนวนเงิน" name="MoneyLower" id="MoneyLower" maxlength="6" <?php echo $disabled; ?>>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-success btn-block btn-flat" id="btn-save-session" <?php echo $disabled; ?>>เพิ่มรายการ</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <p style="border-bottom:1px dotted #ccc"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12" id="display-sell-list">
                                        แสดงรายการขายเลข
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $Layout->Navigation('html-js'); ?>
    <script type="text/javascript" src="../common/assets/plugins/datatables/jquery.datatables.min.js"></script>
    <script type="text/javascript" src="../common/assets/plugins/datatables/datatables.bootstrap.min.js"></script>
    <script type="text/javascript" src="../common/assets/plugins/datatables/datatables.buttons.min.js"></script>
    <script type="text/javascript" src="../common/assets/plugins/select2/select2.full.min.js"></script>
    <script type="text/javascript" src="../common/assets/jquery/jquery.form.min.js"></script>
    <script type="text/javascript" src="assets/js/digi.sell.js"></script>
</body>
</html>
<?php
        $Layout->MainModal('');
        $Layout->StatusModal();
    }
}
?>