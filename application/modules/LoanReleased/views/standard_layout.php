<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>System | Loan Released</title>

    <?PHP 
        $this->load->view('Utilities/styles'); 
        $this->load->view('Utilities/extras'); 
    ?>

    <style type="text/css">
        .wizard.vertical > .steps {
            float: left;
            width: 10%; 
        }

        .wizard.vertical > .content {
            float: left;
            margin: 0 0 0.5em 0;
            width: 90%; 
        }

        ul[role="tablist"] > li > a{
            text-align: center;
        }
        
        .dropdown-menu{
            left: -60px;
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
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <?php $this->load->view('Utilities/header_navbar'); ?>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <?PHP  $this->load->view('Utilities/user_info'); ?>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <?PHP echo modules::run('Utilities/menu'); ?>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2018 <a href="javascript:void(0);">Ezware Services Corporation</a>.                    
                </div>
                <div class="version">
                    <b>Version: </b> 1.0
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->

    </section>

    <section class="content">
        <div class="container-fluid">
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div id="widgets_table" class="card">
                <div class="header">
                    <h4>Loan Released</h4>
                </div>
                <!-- Widgets -->
                <div class="body clearfix">
                    <div id="loan-borrower-table">
                        <div class="body table-responsive" style="height: 380px;">
                            <table id="loan-borrower-list" class="table table-striped table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th style="text-align: left;">SEQ. #</th>
                                        <th style="text-align: left;">BORROWER</th>
                                        <th style="text-align: left;">TOTAL LOAN AMOUNT</th>
                                        <th style="text-align: left;">TOTAL LOAN COUNT</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="loan-released-table" style="display: none;">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn waves-effect bg-blue" id="back-main-table-btn" data-id="">BACK</button>
                        </div>
                        <div class="body table-responsive" style="height: 380px;">
                            <table id="borrower-loan-list" class="table table-striped table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th style="text-align: left;">SEQ. #</th>
                                        <th style="text-align: left;">BORROWER</th>
                                        <th style="text-align: left;">AMOUNT</th>
                                        <th style="text-align: left;">LOAN DATE</th>
                                        <th style="text-align: left;">MATURITY DATE</th>
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
                <!-- #END# Widgets --> 

            </div>
        </div>

    </section>

    <?PHP 
        $this->load->view('payment_transaction_details_modal');
        $this->load->view('payment_modal');
        $this->load->view('penalty_modal');
        $this->load->view('loan_payment_modal');
        $this->load->view('loan_history_modal');
        $this->load->view('loan_history_details_modal');
        $this->load->view('preview_info_modal');
        $this->load->view('Utilities/javascripts');
    ?>
    <script src="<?php echo base_url("assets/plugins/Numerals/numerals.js"); ?>"></script>
    <!-- JQuery Steps Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-steps/jquery.steps.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugins/Number/number.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/dist/loan-released.js'); ?>"></script>
    <!-- <script src="<?php // echo base_url('assets/js/dist/notification.js'); ?>"></script> -->
</body>

</html>