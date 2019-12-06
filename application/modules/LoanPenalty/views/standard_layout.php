<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>System | Dashboard</title>

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
                    <h4>Penalty</h4>
                </div>

                <!-- Widgets -->
                <div class="body clearfix">
                    <div id="loan-penalty-table">
                        <div class="body table-responsive">
                            <table id="loan-penalty-list" class="table table-striped table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th style="text-align: left;">SEQ. #</th>
                                        <th style="text-align: left;">DUE DATE</th>
                                        <th style="text-align: left;">DUE AMOUNT</th>
                                        <th style="text-align: left;">BALANCE AMOUNT</th>
                                        <th style="text-align: left;">PENALTY AMOUNT</th>
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
        $this->load->view('Utilities/javascripts');
    ?>
    <script src="<?php echo base_url('assets/js/dist/loan-penalty.js'); ?>"></script>
    <!-- <script src="<?php // echo base_url('assets/js/dist/notification.js'); ?>"></script> -->
</body>

</html>