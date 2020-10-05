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
			$strMonth= date("n",strtotime($strDate));
			$strDay= date("j",strtotime($strDate));
			$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			$strMonthThai=$strMonthCut[$strMonth];
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
    <link rel="stylesheet" type="text/css" href="../common/assets/plugins/validation/validation.min.css">
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
                    <div class="col-lg-12 col-sm-12">
                        <div class="panel panel-yellow">
                            <div class="panel-heading"><i class="glyphicon glyphicon-list fa-fw"></i> รายการขายเลข 3 ตัว (งวดปัจจุบัน)</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-2 text-left">
										<div class="form-group">
											<label>ยอดรวม 3 ตัวตรง</label>
											<div class="input-group">
												<input type="text" class="form-control text-right" style="font-weight: bold" placeholder="ยอดรวมเลขบน" id="TotalsUpper" value="0.00">
											</div>
										</div>
									</div>
									<div class="col-lg-2 col-sm-2 text-left">
										<div class="form-group">
											<label>ยอดรวม 3 ตัวโต๊ด</label>
											<div class="input-group">
												<input type="text" class="form-control text-right" style="font-weight: bold" placeholder="ยอดรวมเลขล่าง" id="TotalsLower" value="0.00">
											</div>
										</div>
									</div>
                                    <div class="col-lg-2 col-sm-2 text-left">
										<div class="form-group">
											<label>ยอดรวมทั้งหมด</label>
											<div class="input-group">
												<input type="text" class="form-control text-right" style="font-weight: bold" placeholder="ยอดรวมทั้งหมด" id="Totals" value="0.00">
											</div>
										</div>
									</div>
                                    <div class="col-lg-3 col-sm-3">
                                        <div class="form-group">
                                            <label>งวดวันที่</label>
                                            <select name="PeriodID" id="PeriodID" class="form-control select2" style="cursor: pointer;width: 100%;">';
                                                <?php
                                                    $select = '';
													$sql = "SELECT * FROM ".TABLE_PERIOD." WHERE AcceptExpireTime > '".date("Y-m-d H:i:s")."' AND Status = 'Open'";
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
                                    <div class="col-lg-3 col-sm-3">
                                        <div class="form-group">
                                            <label>ตัวแทนจำหน่าย</label>
                                            <select name="AgentID" id="AgentID" class="form-control select2" style="cursor: pointer;width: 100%;">';
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
                                    <div class="col-lg-12 table-responsive" id="display-table-3digi-list"></div>
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
    <script type="text/javascript" src="../common/assets/plugins/validation/validation.min.js"></script>
    <script type="text/javascript" src="../common/assets/plugins/validation/bootstrap.min.js"></script>
    <script type="text/javascript" src="../common/assets/plugins/select2/select2.full.min.js"></script>
    <script type="text/javascript" src="../common/assets/jquery/jquery.form.min.js"></script>
    <script type="text/javascript" src="assets/js/report.sell.3digi.js"></script>
</body>
</html>
<?php
        $Layout->MainModal('');
        $Layout->StatusModal();
    }
}
?>