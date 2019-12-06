<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>System | User Account</title>

    <?PHP 
        $this->load->view('Utilities/styles'); 
        $this->load->view('Utilities/extras'); 
        $this->load->view('Users/add_form');
        $this->load->view('Users/edit_form');
    ?>
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/dropzone/dropzone.css'); ?>"></link>
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
            <!-- Tabs With Icon Title -->
            <!-- <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h4>Leave Entry</h4>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle btn bg-blue-grey waves-effect" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                                        <i class="material-icons">view_comfy</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li id="btnSaveForm"><a href="javascript:void(0);" class=" waves-effect waves-block"><i style="color: #2196f3;" class="material-icons">save</i> Save</a></li>
                                        <li id="btnDeleteForm"><a href="javascript:void(0);" class=" waves-effect waves-block"><i style="color: #f44336;" class="material-icons">delete</i> Delete</a></li>
                                        <li><a href="javascript:void(0);" class=" waves-effect waves-block"><i style="color: #9e9e9e;" class="material-icons">print</i> Print</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h4>User Accounts</h4>

                            <ul class="header-dropdown m-r--5">
                                <button class="btn sm-btn bg-blue waves-effect waves-block" id="btnAddUser">
                                    Add <i class="material-icons">person_add</i>
                                </button>
                            </ul>

                        </div>
                        <div class="body">
                            <table id="tblUsersList" class="table table-bordered table-striped table-hover dataTable display responsive no-wrap" width="100%">
                                <thead>
                                    <tr>
                                        <th width="30%">Name</th>
                                        <th>Userame</th>
                                        <th>Role</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                    <tbody>
                                    </tbody>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?PHP 
        $this->load->view('Utilities/javascripts');
    ?>
    <!-- Sweet Alert Plugin Js -->
    <script src="<?php echo base_url('assets/plugins/dropzone/dropzone.js'); ?>"></script>
    <script src="<?php echo base_url("assets/js/dist/ayieee.js"); ?>"></script>
    <script src="<?php echo base_url('assets/js/dist/users.js'); ?>"></script>

    <!-- <script src="<?php // echo base_url('assets/js/dist/notification.js'); ?>"></script> -->
    
</body>

</html>