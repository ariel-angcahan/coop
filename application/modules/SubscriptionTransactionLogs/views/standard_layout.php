<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>System | Subscription Transaction Logs</title>

    <?PHP 
        $this->load->view('Utilities/styles'); 
        $this->load->view('Utilities/extras'); 
    ?>

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
                    <h4>Subscription Transaction</small></h4>
                </div>
                <!-- Widgets -->
                <div class="body ">
                    <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-primary">
                            <div class="panel-heading" role="tab" id="headingOne_1">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion_1" href="#filter-options" aria-expanded="true" aria-controls="filter-options">
                                        <i class="material-icons">swap_vert</i>More Filter(s)
                                    </a>
                                </h4>
                            </div>
                            <div id="filter-options" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_1">
                                <div class="panel-body row">
                                    <div class="col-lg-6 row">
                                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-6">
                                            <h2 class="card-inside-title">Date From</h2>
                                            <div class="form-group">
                                                <div class="form-line" id="bs_datepicker_container">
                                                    <input type="text" class="form-control" placeholder="Please choose a date..." id="date-from" name="date-from">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-6">
                                            <h2 class="card-inside-title">Date To</h2>
                                            <div class="form-group">
                                                <div class="form-line" id="bs_datepicker_container">
                                                    <input type="text" class="form-control" placeholder="Please choose a date..." id="date-to" name="date-to">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 row">
                                        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-6">
                                            <h2 class="card-inside-title">Filter Button</h2>
                                            <button type="button" id="btn-filter-submit" class="btn btn-primary waves-effect">SEARCH</button>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <h2 class="card-inside-title">Report</h2>
                                            <button type="button" id="btn-print-preview" class="btn btn-primary waves-effect">PRINT</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="body table-responsive">
                                <table id="subscription-transaction-table" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left;">SEQ. #</th>
                                            <th style="text-align: left;">TMC ID</th>
                                            <th style="text-align: left;">MEMBER</th>
                                            <th style="text-align: left;">AMOUNT</th>
                                            <th style="text-align: left;">TRANSACTION DATE</th>
                                            <th style="text-align: left;"></th>
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
        $this->load->view('preview_transaction_details');
        $this->load->view('Utilities/javascripts');
    ?>
    <script src="<?php echo base_url('assets/js/dist/subscription-transaction-logs.js'); ?>"></script>
</body>

</html>