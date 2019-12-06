<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" href="<?PHP echo base_url('assets/images/sys_img/favicon.ico'); ?>" type="image/x-icon">
    
    <title>System | Login</title>

    <link rel="stylesheet" href="<?PHP echo base_url('assets/css/googleapis.css'); ?>">
    
    <link rel="stylesheet" href="<?PHP echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>">
    <!-- Waves Effect Css -->
    <link rel="stylesheet" href="<?PHP echo base_url('assets/plugins/node-waves/waves.css'); ?>">
    <!-- Animation Css -->
    <link rel="stylesheet" href="<?PHP echo base_url('assets/plugins/animate-css/animate.css'); ?>">
    <!-- Sweetalert Css -->
    <link href="<?PHP echo base_url('assets/plugins/sweetalert/sweetalert.css'); ?>" rel="stylesheet" />
    <!-- Theme -->
    <link rel="stylesheet" href="<?PHP echo base_url('assets/css/themes/all-themes.css'); ?>">
    <!-- Custome Style -->
    <link rel="stylesheet" href="<?PHP echo base_url('assets/css/style.css'); ?>">
</head>

<body class="login-page" style="margin-top: 30px;">
    <div class="login-box">
        <div class="logo">
            <center><img src="<?PHP echo base_url('assets/images/sys_img/ezware-logo.png'); ?>" width="350px"></center> 
            <a href="javascript:void(0);" style="font-size: 20px; padding-top:10px;"><b>EzCS</b></a> 
            <small>Ezware Cooperative System</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST">
                    <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-block bg-blue waves-effect" type="submit">SIGN IN</button>
                        </div>
                    </div>
                    <div>
                        <input type="hidden" name="token" value="<?php echo $generated_token; ?>">
                        <input type="hidden" name="_access" value="<?php echo $generated_token; ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php 
        $this->load->view('Utilities/extras');
    ?>
    
    <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.js'); ?>"></script>
    <!-- Jquery Validation Plugin Css -->
    <script src="<?php echo base_url('assets/plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <!-- Sweet Alert Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/sweetalert/sweetalert.min.js'); ?>"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/node-waves/waves.js'); ?>"></script>
    <!-- Ayieee Core Js -->
    <script src="<?php echo base_url('assets/js/dist/ayieee.js'); ?>"></script>

    <script src="<?php echo base_url('assets/js/dist/login.js'); ?>"></script>


</body>

</html>