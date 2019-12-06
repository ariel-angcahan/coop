<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>System | Loan Releasing</title>

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

        /*fieldset{
            height: 500px;
        }*/
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
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-6">
                            <h4>Loan Releasing</h4>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <h5>NET PROCEED: <small id="net-proceeds">0.00</small></h5>
                        </div>
                    </div>
                </div>
                <!-- Widgets -->
                <div class="body clearfix">
                    <form id="form-loan-application" method="POST">
                        <h3></h3>
                        <fieldset>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <p>
                                    <b>Borrower*</b>
                                </p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" data-size="5" name="borrower-id" id="borrower-id">
                                            <?php echo $borrower_list; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <h3></h3>
                        <fieldset>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <p>
                                    <b>Loan Amount*</b>
                                </p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control not-empty" name="loan-amount" id="loan-amount">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <p>
                                    <b>Loan Term <small>(In Days)</small>*</b>
                                </p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="loan-term" id="loan-term">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <p>
                                    <b>Frequecy of Payment*</b>
                                </p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="frequency-of-payment" id="frequency-of-payment">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <p>
                                    <b>No. of Payment Period*</b>
                                </p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="no-of-payment-period" id="no-of-payment-period">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <p>
                                    <b>Loan Date*</b>
                                </p>
                                <div class="form-group">
                                    <div class="form-line" id="bs_datepicker_container">
                                        <input type="text" class="form-control" placeholder="Please choose a date..." id="loan-date" name="loan-date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <p>
                                    <b>Maturity Date*</b>
                                </p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="maturity-date" name="maturity-date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <p>
                                    <b>First Payment Date*</b>
                                </p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="first-payment-date" name="first-payment-date">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <h3></h3>
                        <fieldset>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <p>
                                    <b>TAX AMOUNT*</b>
                                </p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="tax-amount" name="tax-amount" value="0" readonly="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table id="deduction-table" class="table table-striped table-hover dataTable">
                                        <thead>
                                            <tr>
                                                <th style="text-align: left;">SEQ. #</th>
                                                <th style="text-align: left;">DEDUCTIONS</th>
                                                <th style="text-align: left;">AMORTIZED</th>
                                                <th style="text-align: left;">DEDUCT NET PROCEED</th>
                                                <th style="text-align: left;">RATE</th>
                                                <th style="text-align: left;">AMOUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!-- #END# Widgets --> 

            </div>
        </div>

    </section>

    <?PHP 
        $this->load->view('Utilities/javascripts');
    ?>
    <script src="<?php echo base_url("assets/plugins/Numerals/numerals.js"); ?>"></script>
    <!-- JQuery Steps Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/jquery-steps/jquery.steps.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugins/Number/number.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/dist/loan-releasing.js'); ?>"></script>
    <!-- <script src="<?php // echo base_url('assets/js/dist/notification.js'); ?>"></script> -->
</body>

</html>