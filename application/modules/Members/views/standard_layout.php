<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>System | Members</title>

    <?PHP 
        $this->load->view('Utilities/styles'); 
        $this->load->view('Utilities/extras'); 
    ?>
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/dropzone/dropzone.css'); ?>"></link>
    <style type="text/css">
        #calendar {
            max-width: 900px;
            margin: 40px auto;
        }
        table#member-list > thead > tr > th:last-child, table#member-list >  tbody > tr > td:last-child{
          text-align: center;
          width: 50px;
        }
        table#subscription-table > thead > tr > th:last-child, table#subscription-table >  tbody > tr > td:last-child{
          text-align: center;
          width: 50px;
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
                    <h4>Members</h4>
                            
                    <ul class="header-dropdown m-r--5">
                        <button class="btn sm-btn bg-blue waves-effect waves-block" id="payment-btn">PAYMENT (<small>This button is for DEMO purposes only</small>)</button>
                    </ul>
                </div>

                <!-- Widgets -->
                <div class="body">
                    <div class="row">
                        <div class="col-md-12" id="member-list-div-table">
                            <div class="body table-responsive" style="height: 500px;">
                                <table id="member-list" class="table table-striped table-hover dataTable">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left;">SEQ. #</th>
                                            <th style="text-align: left;">NAME</th>
                                            <th style="text-align: left;">TMC ID</th>
                                            <th style="text-align: left;">DATE CREATED</th>
                                            <th style="text-align: left;">DATE APPROVED</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12" id="subscription-div-table" style="display: none;">
                            <!-- <button class="btn sm-btn bg-blue waves-effect waves-block">BACK</button> -->
                            <div class="btn-group" role="group">
                                <button type="button" class="btn waves-effect bg-blue" id="back-main-table-btn" data-id="">BACK</button>
                                <button type="button" class="btn btn-default" id="subscription-btn-name"></button>
                                <button type="button" class="btn btn-default copy" id="subscription-btn-code" style="cursor: copy;"></button>
                            </div>
                            <div class="body table-responsive" style="height: 500px;">
                                <table id="subscription-table" class="table table-striped table-hover dataTable">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left;">SEQ. #</th>
                                            <th style="text-align: left;">SUBSCRIPTION AMOUNT</th>
                                            <th style="text-align: left;">PAYMENT MODE</th>
                                            <th style="text-align: left;">DATE CREATED</th>
                                            <th style="text-align: left;">DATE APPROVED</th>
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
                <!-- #END# Widgets --> 

            </div>
        </div>

    </section>

    <?PHP 
        $this->load->view('Members/preview_modal');
        $this->load->view('Members/payment_modal');
        $this->load->view('Members/preview_breakdown');
        $this->load->view('Members/preview_ledger_details');
        $this->load->view('Members/preview_transaction_logs');
        $this->load->view('Utilities/javascripts');
    ?>

    <script src="<?php echo base_url('assets/plugins/jspdf/jspdf.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugins/dropzone/dropzone.js'); ?>"></script>
    <script src="<?php echo base_url("assets/plugins/Numerals/numerals.js"); ?>"></script>
    <script src="<?php echo base_url('assets/js/dist/member.js'); ?>"></script>
    <!-- <script src="<?php // echo base_url('assets/js/dist/notification.js'); ?>"></script> -->
</body>

</html>