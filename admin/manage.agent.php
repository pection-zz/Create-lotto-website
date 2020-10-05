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
        .table tbody > tr > td{
            vertical-align: middle;
        }
        .table thead > tr > th{
            vertical-align: middle;
            font-family: 'Kanit';
            font-style: normal;
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
                    <div class="col-lg-12 col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading"><i class="glyphicon glyphicon-user fa-fw"></i> ตัวแทนจำน่าย</div>
                            <div class="panel-body">
                                <div id="display-table-agent-list">กำลังโหลดข้อมูล</div>
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
    <script type="text/javascript" src="assets/js/manage.agent.js"></script>
</body>
</html>
<?php
        $Layout->MainModal('ตัวแทนจำหน่าย');
        $Layout->StatusModal();
    }
}
?>