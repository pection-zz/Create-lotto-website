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
        function DateThai($strDate){
			$strYear = date("Y",strtotime($strDate))+543;
			$strMonth = date("n",strtotime($strDate));
			$strDay = date("j",strtotime($strDate));
			$strHour = date("H",strtotime($strDate));
			$strMinute = date("i",strtotime($strDate));
			$strSeconds = date("s",strtotime($strDate));
			$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			$strMonthThai = $strMonthCut[$strMonth];
			return "$strDay $strMonthThai $strYear";
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
    <title>iLotto Administrator System</title>
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
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel panel-danger">
                            <div class="panel-heading" style="overflow: hidden;"> รายงานยอดขายประจำงวด
                                <div class="pull-right">
                                    <div class="btn-group btn-group-sm">
                                        <select name="PeriodID" id="PeriodID" class="form-control select2" style="cursor: pointer;width: 100%;">';
                                            <?php
                                                $select = '';
                                                $sql = "SELECT PeriodID FROM ".TABLE_PERIOD." WHERE AcceptExpireTime > '".date("Y-m-d H:i:s")."' AND Status = 'Open' ORDER BY PeriodID DESC";
                                                $result = $Conn->prepare($sql);
                                                $result->execute();
                                                if($result->rowCount() > 0){
                                                    while($data = $result->fetchObject()){
                                                        $select .= '<option value="'.$data->PeriodID.'">'.DateThai($data->PeriodID).'</option>';
                                                    }
                                                }else{
                                                    $select = '<option value=""></option>';
                                                }
                                                echo $select;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12 col-lg-12">
                                        <div id="display-sell-data"><i class="fa fa-spinner fa-spin"></i> กำลังเรียกข้อมูล...</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-lg-12">
                                        <div id="display-sell-detail"><i class="fa fa-spinner fa-spin"></i> กำลังเรียกข้อมูล...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel panel-success">
                            <div class="panel-heading"><i class="fa fa-bar-chart-o fa-fw"></i> รายงานยอดกำไร - ขาดทุน ย้อนหลัง 12 งวด
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-2 text-left">
										<div class="form-group">
											<label class="text-success">เลขท้าย 2 ตัวบน</label>
											<div class="input-group">
												<input type="text" class="form-control text-right" style="font-weight: bold" placeholder="เลขท้าย 2 ตัวบน" id="2DigiUp">
											</div>
										</div>
									</div>
									<div class="col-lg-2 col-sm-2 text-left">
										<div class="form-group">
											<label class="text-success">เลขท้าย 2 ตัวล่าง</label>
											<div class="input-group">
												<input type="text" class="form-control text-right" style="font-weight: bold" placeholder="เลขท้าย 2 ตัวล่าง" id="2DigiDown">
											</div>
										</div>
									</div>
                                    <div class="col-lg-2 col-sm-2 text-left">
										<div class="form-group">
											<label class="text-success">เลขท้าย 3 ตัว</label>
											<div class="input-group">
												<input type="text" class="form-control text-right" style="font-weight: bold" placeholder="เลขท้าย 3 ตัว" id="3Digi">
											</div>
										</div>
									</div>
                                    <div class="col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label>งวดวันที่</label>
                                            <select name="ReportPeriodID" id="ReportPeriodID" class="form-control select2" style="cursor: pointer;width: 100%;">';
                                                <?php
                                                    $select = '';
                                                    $sql = "SELECT PeriodID FROM ".TABLE_PERIOD." WHERE AcceptExpireTime < '".date("Y-m-d H:i:s")."' AND Status = 'Close' ORDER BY PeriodID DESC LIMIT 12";
                                                    $result = $Conn->prepare($sql);
                                                    $result->execute();
                                                    if($result->rowCount() > 0){
                                                        while($data = $result->fetchObject()){
                                                            $select .= '<option value="'.$data->PeriodID.'">'.DateThai($data->PeriodID).'</option>';
                                                        }
                                                    }
                                                    echo $select;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-lg-3">
                                        <div class="form-group">
                                            <label>ตัวแทนจำหน่าย</label>
                                            <select name="ReportAgentID" id="ReportAgentID" class="form-control select2" style="cursor: pointer;width: 100%;">';
                                                <?php
                                                    $select = '<option value="All" selected>ทั้งหมด</option>';
                                                    $sql = "SELECT * FROM ".TABLE_AGENT;
                                                    $result = $Conn->prepare($sql);
                                                    $result->execute();
                                                    if($result->rowCount() > 0){
                                                        while($data = $result->fetchObject()){
                                                            $select .= '<option value="'.$data->AgentID.'">'.$data->FullName.'</option>';
                                                        }
                                                    }
                                                    echo $select;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-lg-12">
                                        <div id="display-accounting"><i class="fa fa-spinner fa-spin"></i> กำลังเรียกข้อมูล...</div>
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
    <script type="text/javascript" src="../common/assets/plugins/validation/bootstrap.min.js"></script>
    <script type="text/javascript" src="../common/assets/plugins/select2/select2.full.min.js"></script>
    <script type="text/javascript" src="../common/assets/jquery/jquery.form.min.js"></script>
    <script type="text/javascript" src="assets/js/index.js"></script>
</body>
</html>
<?php
        $Layout->StatusModal('');
        //$Layout->MainModal('');
    }
}
?>