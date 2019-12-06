<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?PHP echo $title; ?></title>

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
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="<?PHP echo base_url('Dashboard'); ?>">Card Monitoring System</a>
            </div>
            <?PHP $this->load->view('header_navbar'); ?>
        </div>
    </nav>
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
            <!-- Tabs With Icon Title -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="empTable" class="card">
                        <div class="header">
                            <h4>Roles List</h4>
                            
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-md-3">
                                    <p>
                                        <b>Search</b>
                                    </p>
                                    <select id="get_role_list" class="form-control show-tick" data-live-search="true">
                                        <option selected="true" disabled>Search here. . .</option>
                                        <?php echo $role_list; ?>
                                    </select>
                                </div>
                                </br>
                                <div class="col-md-1">
                                    <div class="preloader pl-size-sm selPreLoading">
                                        <div class="spinner-layer pl-blue">
                                            <div class="circle-clipper left">
                                                <div class="circle"></div>
                                            </div>
                                            <div class="circle-clipper right">
                                                <div class="circle"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="tblAccessRights" class="table table-bordered noselect" id="tbl-access-rights">
                                        <thead>
                                            <tr>
                                                <th>Menu Name</th>
                                                <th width="7%" class="text-center">View</th>
                                                <th width="5%" class="text-center">Create</th>
                                                <th width="5%" class="text-center">Update</th>
                                                <th width="5%" class="text-center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody id="accessRights">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- #END# Tabs With Icon Title -->
        </div>
    </section>

    <?PHP 
        $this->load->view('Utilities/javascripts');
    ?>

    <script src="<?php echo base_url('assets/plugins/jquery-form/jquery.form.min.js'); ?>"></script>
    
</body>
<script src="<?php echo base_url('assets/js/dist/role.js'); ?>"></script>
<!-- <script src="<?php // echo base_url('assets/js/dist/notification.js'); ?>"></script> -->
</html>