<?php
require_once('../common/assets/includes/session.php');
require_once('../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
	require_once('../common/assets/includes/tables.php');
	require_once('assets/class/security.php');

	$Security = new Security();
	if($Security->Authorize($Conn) != true){
		$Chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $_SESSION['SecretKey'] = md5(substr(str_shuffle($Chars),0,8));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="iLotto Administrator System">
    <meta name="author" content="TNT Networks System">

    <link rel="stylesheet" type="text/css" href="../common/assets/bootstrap/css/bootstrap.css?v=101">
    <link rel="stylesheet" type="text/css" href="../common/assets/fonts/awesome/font-awesome.min.css?v=101">
    <link rel="stylesheet" type="text/css" href="../common/assets/css/login.css?v=101">
    <link rel="stylesheet" type="text/css" href="../common/assets/css/core.css?v=101">
    <link rel="stylesheet" type="text/css" href="../common/assets/plugins/modal/bootstrap.modal.patch.css?v=101">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="../common/assets/js/html5shiv.js?v=101"></script>
        <script src="../common/assets/js/respond.min.js?v=101"></script>
    <![endif]-->
    <link rel="shortcut icon" type="image/x-icon" href="../common/assets/images/icon.png">
    <title>iLotto Administrator System</title>
</head>
<body>
    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h1><strong>Administrator</strong> Access</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <h4 class="text-left">Login to iLotto</h4>
                                <hr>
                                <form action="" method="post" class="login-form" id="login-admin">
                                    <div class="form-group has-feedback">
                                        <input type="text" name="username" id="login-username" class="form-control" placeholder="Username">
                                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <input type="password" name="password" id="login-password" class="form-control" placeholder="Password">
                                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 text-left"><a href="reset.php">Recovery Your Password</a></div>
                                        <div class="col-md-4">
                                            <button type="button" id="login-btn" class="btn btn-primary btn-block btn-flat" onclick="DoLogin();">Login</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="../common/assets/jquery/jquery.min.js?v=101"></script>
    <script type="text/javascript" src="../common/assets/bootstrap/js/bootstrap.min.js?v=101"></script>
    <script type="text/javascript" src="../common/assets/jquery/jquery.backstretch.min.js?v=101"></script>
    <script type="text/javascript" src="assets/js/login.js?v=101"></script>
    <!--[if lt IE 10]>
        <script type="text/javascript" src="../common/assets/js/placeholder.js?v=101"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto"> 
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Prompt:300,400">
</body>
</html>
<?php
        require_once('assets/class/layout.php');
        $Layout = new Layout();
        $Layout->StatusModal();
    }else{
        header('location:index.php');
    }
}   
?>