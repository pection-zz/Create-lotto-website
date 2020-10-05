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
            //<li><a href="digi.sell.php"><i class="fa fa-shopping-cart fa-fw"></i> งานขาย</a></li>
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
                        <li><a href="digi.send.php"><i class="fa fa-plus fa-fw"></i> บันทึกตัวเลขเข้าระบบ</a></li>
                        <li><a href="report.history.php"><i class="fa fa-search-plus fa-fw"></i> ประวัติการส่งตัวเลข</a></li>
                        <li><a href="report.totals.php"><i class="fa fa-bar-chart-o fa-fw"></i> สรุปยอดย้อนหลัง 12 งวด</a></li>
                        <li><a href="report.agent.profit.php"><i class="fa fa-bar-chart-o fa-fw"></i> ค่าคอม</a></li>
                        <li><a href="lotto.php"><i class="fa fa-search-plus fa-fw"></i> ตรวจรางวัล</a></li>
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
                            <div class="text-center text-info"><i class="fa fa-spinner fa-spin"></i> กำลังเรียกข้อมูล...</div>
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
                            <div class="text-center text-info"><i class="fa fa-spinner fa-spin"></i> กำลังเรียกข้อมูล...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>