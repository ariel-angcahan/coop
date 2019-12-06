<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>System | Registration</title>
    <!-- Favicon-->
    <link rel="icon" href="<?PHP echo base_url('assets/images/sys_img/favicon.ico'); ?>" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url("assets/plugins/bootstrap/css/bootstrap.css"); ?>" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="<?php echo base_url("assets/plugins/node-waves/waves.css"); ?>" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="<?php echo base_url("assets/plugins/animate-css/animate.css"); ?>" rel="stylesheet" />
    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="<?php echo base_url("assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"); ?>" rel="stylesheet" />
    <!-- Bootstrap DatePicker Css -->
    <link href="<?php echo base_url("assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css"); ?>" rel="stylesheet" />
    <!-- Wait Me Css -->
    <link href="<?php echo base_url("assets/plugins/waitme/waitMe.css"); ?>" rel="stylesheet" />
    <!-- Bootstrap Select Css -->
    <link href="<?php echo base_url("assets/plugins/bootstrap-select/css/bootstrap-select.css"); ?>" rel="stylesheet" />
    <!-- Custom Css -->
    <link href="<?php echo base_url("assets/css/style.css"); ?>" rel="stylesheet">
    <link href="<?php echo base_url("assets/css/materialize.css"); ?>" rel="stylesheet">
    
    <!-- Sweetalert Css -->
    <link href="<?php echo base_url("assets/plugins/sweetalert/sweetalert.css"); ?>" rel="stylesheet" />
    <!-- Sweetalert Css -->
    <link href="<?php echo base_url("assets/plugins/jquery-spinner/css/bootstrap-spinner.min.css"); ?>" rel="stylesheet" />
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url("assets/css/themes/all-themes.css"); ?>" rel="stylesheet" />
    <style type="text/css">
        .form-group{
            margin-bottom: 0;
        }
        .registered-list-border{
            border-bottom: gray 2px solid;
        }
        @media (min-width: 768px) and (max-width: 1024px) {
            b{
                font-size: large;
            }
            input[type="text"] {
                font-size: large;
            }
            html *{
                font-size: large;
            }
            .dropdown-menu{
                font-size: large;
            }
        }
        @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
            b{
                font-size: large;
            }
            input[type="text"] {
                font-size: large;
            }
            html *{
                font-size: large;
            }
        }

        .card{
            margin-left: .1% !important;
        }

        .clearfix{
            max-width: 100% !important;
        }
    </style>
</head>

<body class="signup-page" style="max-width: 100% !important; padding-left: 5% !important; padding-right: 5% !important;">
    <div class="signup-box">
        <div class="logo">
            <a href="javascript:void(0);">Admin<b>BSB</b></a>
            <small>Admin BootStrap Based - Material Design</small>
        </div>
    </div>
    <div class="clearfix row">
        <div class="card col-lg-3">
            <div class="body">
                <form id="sign_up" method="POST">
                    <div class="msg">Register a new membership</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="namesurname" placeholder="Name Surname" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" minlength="6" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="confirm" minlength="6" placeholder="Confirm Password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="terms" id="terms" class="filled-in chk-col-pink">
                        <label for="terms">I read and agree to the <a href="javascript:void(0);">terms of usage</a>.</label>
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">SIGN UP</button>

                    <div class="m-t-25 m-b--5 align-center">
                        <a href="sign-in.html">You already have a membership?</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card col-lg-3">
            <div class="body">
                <form id="sign_up" method="POST">
                    <div class="msg">Register a new membership</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="namesurname" placeholder="Name Surname" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" minlength="6" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="confirm" minlength="6" placeholder="Confirm Password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="terms" id="terms" class="filled-in chk-col-pink">
                        <label for="terms">I read and agree to the <a href="javascript:void(0);">terms of usage</a>.</label>
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">SIGN UP</button>

                    <div class="m-t-25 m-b--5 align-center">
                        <a href="sign-in.html">You already have a membership?</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card col-lg-3">
            <div class="body">
                <form id="sign_up" method="POST">
                    <div class="msg">Register a new membership</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="namesurname" placeholder="Name Surname" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" minlength="6" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="confirm" minlength="6" placeholder="Confirm Password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="terms" id="terms" class="filled-in chk-col-pink">
                        <label for="terms">I read and agree to the <a href="javascript:void(0);">terms of usage</a>.</label>
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">SIGN UP</button>

                    <div class="m-t-25 m-b--5 align-center">
                        <a href="sign-in.html">You already have a membership?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Jquery Core Js -->
    <script src="<?php echo base_url("assets/plugins/jquery/jquery.min.js"); ?>"></script>
    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url("assets/plugins/bootstrap/js/bootstrap.js"); ?>"></script>
    <!-- Select Plugin Js -->
    <script src="<?php echo base_url("assets/plugins/bootstrap-select/js/bootstrap-select.js"); ?>"></script>
    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url("assets/plugins/jquery-slimscroll/jquery.slimscroll.js"); ?>"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url("assets/plugins/node-waves/waves.js"); ?>"></script>
    <!-- Autosize Plugin Js -->
    <script src="<?php echo base_url("assets/plugins/autosize/autosize.js"); ?>"></script>
    <!-- Moment Plugin Js -->
    <script src="<?php echo base_url("assets/plugins/momentjs/moment.js"); ?>"></script>
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="<?php echo base_url("assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"); ?>"></script>
    <script src="<?php echo base_url("assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"); ?>"></script>
    
    <!-- Sweet Alert Plugin Js -->
    <script src="<?php echo base_url("assets/plugins/jquery-spinner/js/jquery.spinner.min.js"); ?>"></script>
    <!-- Sweet Alert Plugin Js -->
    <script src="<?php echo base_url("assets/plugins/sweetalert/sweetalert.min.js"); ?>"></script>
    <!-- Custom Js -->
    <script src="<?php echo base_url("assets/plugins/javascripts/js/admin.js"); ?>"></script>
    
    <script src="<?php echo base_url("assets/plugins/javascripts/js/pages/forms/basic-form-elements.js"); ?>"></script>
    <!-- Demo Js -->
    <script src="<?php echo base_url("assets/plugins/javascripts/js/demo.js"); ?>"></script>
    <!-- Jquery Validation Plugin Css -->
    <script src="<?php echo base_url("assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js"); ?>"></script>
    <!-- Jquery Validation Plugin Css -->
    <script src="<?php echo base_url("assets/plugins/jquery-validation/jquery.validate.js"); ?>"></script>

    <script src="<?php echo base_url("assets/plugins/Numerals/numerals.js"); ?>"></script>

    <script src="<?php echo base_url("assets/js/dist/ayieee.js"); ?>"></script>
    <!-- <script src="<?php echo base_url("assets/js/dist/registration.js"); ?>"></script> -->
</body>

</html>