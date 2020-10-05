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
    <link rel="stylesheet" type="text/css" href="../common/assets/plugins/modal/bootstrap.modal.patch.css">
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
                <form id="session-form" name="session-form" method="post" action="">
                    <div class="row">
                        <div class="col-lg-3 col-sm-3 text-left"></div>
                        <div class="col-lg-6 col-sm-6 text-left">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>รหัสผ่านปัจจุบัน</label>
                                    <input type="password" class="form-control" placeholder="รหัสผ่านปัจจุบัน" name=CurrentPassword id="CurrentPassword" maxlength="32">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3 text-left"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-sm-3 text-left"></div>
                        <div class="col-lg-6 col-sm-6 text-left">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>รหัสผ่านใหม่</label>
                                    <input type="password" class="form-control" placeholder="รหัสผ่านใหม่" name="NewPassword1" id="NewPassword1" maxlength="32">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3 text-left"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-sm-3 text-left"></div>
                        <div class="col-lg-6 col-sm-6 text-left">
                            <div class="form-group">
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="รหัสผ่านใหม่" name="NewPassword2" id="NewPassword2" maxlength="32">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3 text-left"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-sm-3 text-left"></div>
                        <div class="col-lg-6 col-sm-6 text-right">
                            <button type="button" id="btn-submit" class="btn btn-warning btn-flat" onclick="DoChangePassword();">เปลี่ยนรหัสผ่าน</button>
                        </div>
                        <div class="col-lg-3 col-sm-3 text-left"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php $Layout->Navigation('html-js'); ?>
    <script type="text/javascript" src="../common/assets/jquery/jquery.form.min.js"></script>
    <script type="text/javascript" src="assets/js/password.js"></script>
</body>
</html>
<?php
        $Layout->StatusModal();
        $Layout->MainModal('');
    }
}
?>