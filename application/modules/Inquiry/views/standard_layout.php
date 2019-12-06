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
    <!-- Bootstrap datatable -->
    <link href="<?PHP echo base_url('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?PHP echo base_url('assets/plugins/jquery-datatable/skin/bootstrap/css/responsive.dataTables.min.css'); ?>" rel="stylesheet">
    <link href="<?PHP echo base_url('assets/plugins/jquery-datatable/select.dataTables.min.css'); ?>" rel="stylesheet">
    
    <!-- Sweetalert Css -->
    <link href="<?php echo base_url("assets/plugins/sweetalert/sweetalert.css"); ?>" rel="stylesheet" />
    <!-- Sweetalert Css -->
    <link href="<?php echo base_url("assets/plugins/jquery-spinner/css/bootstrap-spinner.min.css"); ?>" rel="stylesheet" />
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url("assets/css/themes/all-themes.css"); ?>" rel="stylesheet" />
    <style type="text/css">
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

        .ayieee-header{
            position: relative; 
            padding: 0px; 
            margin: 0px; 
            top: 0; 
            width: 100%; 
            height: 70px; 
            padding: 25px;
        }
        .dropdown-menu {
            left: -60px;
        }
    </style>
</head>
<body class="theme-blue">
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>">
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
        <a href="<?php echo base_url()?>" style="text-decoration: none;" class="col-white">COOPERATIVE MANAGEMENT SYSTEM</a>
        <a href="<?php echo base_url('Logins') ?>" class="col-white" role="button" style="float: right;">
            <label>LOGIN</label>
            <i class="material-icons">vpn_lock</i>
        </a>
    </div>
    <section class="content" style="margin-left: 15px; margin-bottom: 30px; margin-top: 10px;">
        <div id="widgets_table" class="card">
            <div class="body">
                <div class=" body row">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary" id="subscription-btn-name"></button>
                        <button type="button" class="btn btn-primary copy" id="subscription-btn-code" style="cursor: copy;"></button>
                    </div>
                    <div style="float: right;">
                        <button type="button" class="btn btn-primary" id="tmc-modal-toggle">TMC</button>
                    </div>
                    <div class="table-responsive" style="height: 500px;">
                        <table id="subscription-table" class="table table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    <th style="text-align: left;">SEQ. #</th>
                                    <th style="text-align: left;">SUBSCRIPTION AMOUNT</th>
                                    <th style="text-align: left;">PAYMENT MODE</th>
                                    <th style="text-align: left;">DATE</th>
                                    <th style="text-align: left;">STATUS</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="bg-teal ayieee-footer">
       EZWARE SERVICES CORPORATION © 2019 - 2020
    </div>
    <?php 
        $this->load->view('tmc_modal'); 
        $this->load->view('Members/preview_ledger_details');
        $this->load->view('Members/preview_breakdown');
        $this->load->view('Members/preview_transaction_logs');
    ?>
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
    <!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-notify/bootstrap-notify.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/skin/bootstrap/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/dataTables.select.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.responsive.min.js'); ?>"></script>
    
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
    <script src="<?php echo base_url("assets/js/dist/inquery.js"); ?>"></script>
</body>
</html>