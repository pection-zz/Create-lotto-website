<?php
class Layout{
	function Navigation($Name = ''){
		if($Name == 'html-meta'){
			echo '
			<meta charset="utf-8">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
		    <meta name="description" content="iLotto Administrator System">
		    <meta name="author" content="TNT Networks System">
		    
		    <meta property="og:title" content="iLotto Administrator System">
			<meta property="og:description" content="iLotto Administrator System">
			<meta property="og:site_name" content="http://ilotto.tntdev.net/">
            <meta property="og:url" content="http://ilotto.tntdev.net/">
			<meta property="og:image" content="">';
		}
		if($Name == 'html-icon'){
			echo '
			<link rel="shortcut icon" type="image/x-icon" href="../common/assets/images/icon.png">';
		}
		if($Name == 'html-css'){
			echo '
            <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Prompt&subset=thai">
            <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Kanit:300"> 
            <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto">
			<link rel="stylesheet" type="text/css" href="../common/assets/bootstrap/css/bootstrap.css">
		    <link rel="stylesheet" type="text/css" href="../common/assets/fonts/awesome/font-awesome.min.css">
		    <link rel="stylesheet" type="text/css" href="../common/assets/css/core.css">
		    <link rel="stylesheet" type="text/css" href="../common/assets/css/loading.css">
		    <link rel="stylesheet" type="text/css" href="../common/assets/plugins/metis/metis.css">
		    <link rel="stylesheet" type="text/css" href="../common/assets/css/ilotto.css">';
		}
		if($Name == 'html-js'){
			echo '
		    <script type="text/javascript" src="../common/assets/jquery/jquery.min.js"></script>
		    <script type="text/javascript" src="../common/assets/bootstrap/js/bootstrap.min.js"></script>
		    <script type="text/javascript" src="../common/assets/plugins/metis/metis.min.js"></script>
		    <script type="text/javascript" src="assets/js/core.js"></script>
		    <!--[if lt IE 10]>
        		<script type="text/javascript" src="../common/assets/js/placeholder.js"></script>
    		<![endif]-->';
		}
		if($Name == 'navbar-header'){
			$License = (parse_ini_file("../common/license.lic"));
			echo '
			<div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">iLotto v'.$License['Version'].'</a>
            </div>';
		}

		if($Name == 'navbar-static-side'){
			echo '
			<div class="navbar-default sidebar" role="navigation">
			    <div class="sidebar-nav navbar-collapse">
			        <ul class="nav" id="side-menu">
			            <li class="sidebar-search">
			                <div class="input-group custom-search-form">
			                    <input type="text" name="Keyword" class="form-control" placeholder="ค้นหา...">
			                    <span class="input-group-btn">
			                    <button class="btn btn-default" type="button" onclick="javascript:DoSearch();">
			                        <i class="fa fa-search"></i>
			                    </button>
			                </span>
			                </div>
			            </li>
                        <li><a href="index.php"><i class="fa fa-home fa-fw"></i> หน้าหลัก</a></li>
                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> งวดหวยและผลการออกรางวัล<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="manage.period.php">งวดหวย</a></li>
                                <li><a href="manage.period.result.php">ผลการออกรางวัล</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> รายงาน<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#"><i class="fa fa-files-o fa-fw"></i> ยอดขายงวดปัจจุบัน <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li><a href="report.current.sell.2digi.php">เลข 2 ตัว</a></li>
                                        <li><a href="report.current.sell.3digi.php">เลข 3 ตัว</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-files-o fa-fw"></i> ยอดขายงวดที่ผ่านมา <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li><a href="report.sell.2digi.php">เลข 2 ตัว</a></li>
                                        <li><a href="report.sell.3digi.php">เลข 3 ตัว</a></li>
                                    </ul>
                                </li>
                                <li><a href="report.agent.profit.php">กำไร - ขาดทุน</a></li>
                            </ul>
                        </li>
                        <li><a href="config.php"><i class="fa fa-gears fa-fw"></i> ตั้งค่าระบบ</a></li>
                        <li><a href="manage.agent.php"><i class="glyphicon glyphicon-user fa-fw"></i> ตัวแทนจำหน่าย</a></li>
                        <li><a href="password.php"><i class="fa fa-key fa-fw"></i> เปลี่ยนรหัสผ่าน</a></li>
                        <li><a href="javascript:DoLogout();"><i class="fa fa-sign-out fa-fw"></i> ออกจากระบบ</a></li>
			        </ul>
			    </div>
			</div>';
		}
	}
    function StatusModal(){
?>        
        <div class="modal fade" id="ilotto-status-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="modal-status">
                            <div class="text-center text-info"><i class="fa fa-spinner fa-spin"></i> โปรดรอ...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
	}
    function MainModal($Label = ''){
?>
        <div class="modal fade" id="ilotto-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="font-family:''">&times;</button>
                        <h4 class="modal-title" id="modal-label"><?php echo $Label; ?></h4>
                    </div>
                    <div class="modal-body">
                        <div id="modal-body">
                            <div class="text-center text-info"><i class="fa fa-spinner fa-spin"></i> โปรดรอ...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>
