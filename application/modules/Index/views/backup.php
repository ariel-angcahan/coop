<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome to Cooperative Management System</title>
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

        .ayieee-header{
            position: relative; 
            padding: 0px; 
            margin: 0px; 
            top: 0; 
            width: 100%; 
            height: 70px; 
            padding: 25px;
        }
        
        .ayieee-footer {
           position: fixed;
           left: 0;
           bottom: 0;
           height: 50px;
           width: 100%;
           color: white;
           text-align: left;
           z-index: 999;
           padding: 15px;
           font-size: 12px;
        }
    </style>
</head>
<body class="theme-blue">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <div class="ayieee-header bg-blue">
        COOPERATIVE MANAGEMENT SYSTEM
        <a href="<?php echo base_url('Logins') ?>" class="col-white" role="button" style="float: right;">
            <label>LOGIN</label>
            <i class="material-icons">vpn_lock</i>
        </a>
    </div>
    <section class="content" style="margin-left: 15px; margin-bottom: 30px; margin-top: 10px;">
        <div class="container-fluid">
            <div class="block-header">
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <a href="<?php echo base_url('Registrations')?>" class="info-box bg-pink hover-expand-effect" style="text-decoration: none;">
                        <div class="icon">
                            <i class="material-icons">fiber_new</i>
                        </div>
                        <div class="content">
                            <div class="text"><h3>NEW APPLICATION</h3></div>
                            <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20"></div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <a href="<?php echo base_url('Inquery')?>" class="info-box bg-cyan hover-expand-effect" style="text-decoration: none;">
                        <div class="icon">
                            <i class="material-icons">help</i>
                        </div>
                        <div class="content">
                            <div class="text"><h3>SUBSCRIPTION INQUERY</h3></div>
                            <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>TOP 10 NEW COOPERATIVE MEMBER</h2>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive" style="margin: 0px;">
                                <table class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Member</th>
                                            <th>Joined Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $list; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 card" style="padding: 0px;">
                    <div class="table-responsive" style="margin: 0px;">
                        <table class="table table-hover dashboard-task-infos">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Member</th>
                                    <th>Joined Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $list; ?>
                            </tbody>
                        </table>
                    </div>
                </div> -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                    <div id="carousel-example-generic_2" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carousel-example-generic_2" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel-example-generic_2" data-slide-to="1"></li>
                            <li data-target="#carousel-example-generic_2" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner" role="listbox">
                            <div class="item active">
                                <img src="https://gurayyarar.github.io/AdminBSBMaterialDesign/images/image-gallery/10.jpg" />
                                <div class="carousel-caption">
                                    <h3>First slide label</h3>
                                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                                </div>
                            </div>
                            <div class="item">
                                <img src="https://gurayyarar.github.io/AdminBSBMaterialDesign/images/image-gallery/12.jpg" />
                                <div class="carousel-caption">
                                    <h3>Second slide label</h3>
                                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                                </div>
                            </div>
                            <div class="item">
                                <img src="https://gurayyarar.github.io/AdminBSBMaterialDesign/images/image-gallery/19.jpg" />
                                <div class="carousel-caption">
                                    <h3>Third slide label</h3>
                                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                                </div>
                            </div>
                        </div>
                        <a class="left carousel-control" href="#carousel-example-generic_2" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic_2" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="bg-teal ayieee-footer">
       EZWARE SERVICES CORPORATION © 2019 - 2020
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