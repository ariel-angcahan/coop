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
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a class="navbar-brand" href="<?php echo base_url(); ?>" style="margin-left: 0">COOPERATIVE MANAGEMENT SYSTEM</a>
                <input type="hidden" name="token" id="token" value="<?php echo $token; ?>">
            </div>
        
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Notifications -->
                    <li class="dropdown">
                        <a href="<?php echo base_url('Logins') ?>" role="button">
                            <label>LOGIN</label>
                            <i class="material-icons">vpn_lock</i>
                        </a>
                    </li>
                    <!-- #END# Notifications -->
                </ul>
            </div>
        </div>
    </nav>
    <section class="content" style="margin-left: 15px;">
        <div class="container-fluid">
            <div class="block-header">
                <h2>REGISTRATIONS</h2>
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form id="register_information" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="card">
                            <div class="body">

                                <div class="row clearfix">
                                    <div class="header">
                                        <h2 class="col-pink">
                                            <b>*PERSONAL INFORMATION</h2></b>
                                        </h2>
                                        <ul class="header-dropdown m-r--5">                              
                                        </ul>
                                    </div>
                                    <br>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>LAST NAME</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="last_name" id="last_name"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>FIRST NAME</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="first_name" id="first_name" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>MIDDLE NAME</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="middle_name" id="middle_name" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>GENDER</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" data-live-search="true" name="gender" id="gender">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>BIRTH DATE</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line" id="birth_date">
                                                <input type="text" class="form-control" name="birth_date_val" id="birth_date_val">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>BIRTH PLACE</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="birth_place" id="birth_place" />
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <div class="row clearfix">
                                    <div class="header">
                                        <h2 class="col-pink">
                                            <b>*CONTACT INFORMATION</b>
                                        </h2>
                                        <ul class="header-dropdown m-r--5">                              
                                        </ul>
                                    </div>
                                    <br>
                                    <div class="col-sm-6">
                                        <p>
                                            <b>EMAIL</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control email" name="email" id="email" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>
                                            <b>MOBILE NO.</b>
                                        </p>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="mobile_no" id="mobile_no" placeholder="(63)-000-0000000" />
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row clearfix">
                                    <div class="header">
                                        <h2 class="col-pink">
                                            <b>*HOME ADDRESS</b>
                                        </h2>
                                        <ul class="header-dropdown m-r--5">                              
                                        </ul>
                                    </div>
                                    <br>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>COUNTRY</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" data-live-search="true" name="country" id="country">
                                                    <option value="1">Philippines</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>REGION</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" data-live-search="true" name="region" id="region">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>PROVINCE</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" data-live-search="true" name="province" id="province">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>MUNICIPALITY</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" data-live-search="true" name="city" id="city">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>ZIP CODE</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="number" class="form-control" name="zip_code" name="zip_code" id="zip_code" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>BARANGAY</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" data-live-search="true" name="barangay" id="barangay">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <p>
                                            <b>STREET</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="street" id="street" />
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row clearfix">
                                    <div class="header">
                                        <h2 class="col-pink">
                                            <b>*MARKET ADDRESS</b>
                                        </h2>
                                        <ul class="header-dropdown m-r--5">                              
                                        </ul>
                                    </div>
                                    <br>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>MARKET</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" data-live-search="true" name="market" id="market">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>TYPE</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" data-live-search="true" name="market_type" id="market_type">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4" id="market_type_fixed" style="display: block;">
                                        <p>
                                            <b>STALL NO</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" data-live-search="true" name="stall_no" id="stall_no">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4" id="market_type_open_entry" style="display: none;">
                                        <p>
                                            <b>LOCATION DESCRIPTION</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="stall_description" id="stall_description" />
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row clearfix">
                                    <div class="header">
                                        <h2 class="col-pink">
                                            <b>*SUBSCRIPTION</b>
                                        </h2>
                                        <ul class="header-dropdown m-r--5">                              
                                        </ul>
                                    </div>
                                    <br>
                                    <div class="col-sm-3">
                                        <p>
                                            <b>MEMBERSHIP</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" data-live-search="true" name="membership_type" id="membership_type">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <p>
                                            <b>SUBSCRIPTION AMOUNT</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="subscription_amount" id="subscription_amount" value="0.00" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <p>
                                            <b>PAYMENT MODE</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control show-tick" data-live-search="true" name="payment_mode" id="payment_mode">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <p>
                                            <b id="payment_per_mode_text">MODE PAYMENT</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="payment_per_mode" id="payment_per_mode" value="0.00" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="body" id="subscription_preview">
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <div class="row clearfix">
                                    <div class="header">
                                        <h2 class="col-pink">
                                            <b>*MOTHER'S MAIDEN NAME</b>
                                        </h2>
                                        <div class="switch">
                                            <label><input type="checkbox" id="switch_mother"><span class="lever"></span></label>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>LAST NAME</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="mother_last_name" id="mother_last_name" value="None" readonly="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>FIRST NAME</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="mother_first_name" id="mother_first_name" value="None" readonly="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>MIDDLE NAME</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="mother_maiden" id="mother_maiden" value="None" readonly="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <div class="row clearfix">
                                    <div class="header">
                                        <h2 class="col-pink">
                                            <b>*FATHER'S NAME</b>
                                        </h2>
                                        <div class="switch">
                                            <label><input type="checkbox" id="switch_father"><span class="lever"></span></label>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>LAST NAME</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="father_last_name" id="father_last_name" value="None" readonly="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>FIRST NAME</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="father_first_name" id="father_first_name" value="None" readonly="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <p>
                                            <b>MIDDLE NAME</b>
                                        </p>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" name="father_middle_name" id="father_middle_name" value="None" readonly="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row clearfix">
                                    <div class="header">
                                        <h2 class="col-pink">
                                            <b>*OTHER INFORMATION</b>
                                        </h2>
                                        <ul class="header-dropdown m-r--5">                              
                                        </ul>
                                    </div>
                                    <br>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <div class="switch">
                                                    <label><input type="checkbox" id="student"><span class="lever"></span></label>
                                                </div>
                                            </span>
                                            <select class="form-control show-tick" id="selected_school_name" name="selected_school_name" data-live-search="true" data-dropup-auto="false" disabled="">
                                                <?php echo $get_school_list; ?>"
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                 <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <input type="checkbox" id="chk_student" disabled="">
                                                <label for="chk_student"></label>
                                            </span>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="entry_school" id="entry_school" placeholder="Enter School Name" disabled/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <div class="switch">
                                                    <label><input type="checkbox" id="employee"><span class="lever"></span></label>
                                                </div>
                                            </span>
                                            <select class="form-control show-tick" id="selected_company_name" name="selected_company_name" data-live-search="true" disabled="">
                                                <?php echo $get_company_list; ?>"
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <input type="checkbox" id="chk_company" disabled="">
                                                <label for="chk_company"></label>
                                            </span>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="entry_company" id="entry_company" placeholder="Enter Company Name" disabled/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-12" style="text-align:right;">
                                        <button type="submit" class="btn bg-blue waves-effect" id="btn_register" name="btn_register">
                                            <i class="material-icons">verified_user</i>
                                            <span>SAVE USER</span>
                                        </button>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
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
    <script src="<?php echo base_url("assets/js/dist/registration.js"); ?>"></script>
</body>
</html>