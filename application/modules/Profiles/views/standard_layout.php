<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?PHP echo $title; ?></title>
    <?PHP $this->load->view('Utilities/styles'); ?>

    <style>
        .container {
          position: relative;
          width: 100%;
          max-width: 400px;
        }

        .container img {
          width: 100%;
          height: auto;
        }

        .container .btn {
          position: absolute;
          top: 10%;
          left: 50%;
          transform: translate(-50%, -50%);
          -ms-transform: translate(-50%, -50%);
          background-color: #555;
          color: white;
          font-size: 16px;
          padding: 6px 12px;
          border: none;
          cursor: pointer;
          border-radius: 5px;
          text-align: center;
        }

        .container .btn:hover {
          background-color: grey;
        }
    </style>
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
                    &copy; 2017 <a href="javascript:void(0);">Ezware Services Corporation</a>.
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
                    <div class="card">
                        <div class="header">
                            <h4>User Profile</h4>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-md-3">
                                <!-- <form id="changeProfile" method="post" enctype="multipart/form-data" action="Profile/changeProfilePicture">     -->
                                    <div class="container">
                                    <img class="img-responsive thumbnail" id="avatarUsr" style="cursor:pointer;" src="<?PHP echo $this->session->avatar; ?>" with="150" height="170"/>
                                    <input name="fileEmp" id="fileEmp" type="file" style="display: none;"/>
                                    <button class="btn" id="btn-upload-photo"><i class="material-icons">add_a_photo</i></button>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    </br>
                                    <div class="row">
                                        <div class="col-md-2"> 
                                            <label>User Role</label>
                                        </div>  
                                        <div class="col-md-1"> 
                                            :
                                        </div>  
                                        <div class="col-md-8"> 
                                            <?PHP echo strtoupper(get_user_role($this->session->RoleId));  ?>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2"> 
                                            <label>Full Name</label>
                                        </div>  
                                        <div class="col-md-1"> 
                                            :
                                        </div>  
                                        <div class="col-md-8"> 
                                            <?PHP echo ucwords($this->session->name); ?>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2"> 
                                            <label>Username</label>
                                        </div>  
                                        <div class="col-md-1"> 
                                            :
                                        </div>  
                                        <div class="col-md-8"> 
                                            <?PHP echo $this->session->username; ?>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="email_address">Old Password</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="password" name="old-password" id="old-password" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="email_address">New Password</label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="password" name="new-password" id="new-password" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                                <label for="email_address">Confirm Password</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="password" name="confirm-password" id="confirm-password" class="form-control">
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <button class="form-control btn btn-success waves-effect" id="btn-change-password">Submit</button>
                                        </div>
                                    </div>
                                    </div>
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
        $this->load->view('Utilities/extras');
        $this->load->view('Utilities/javascripts');  
    ?>
    </form> 
    <script src="<?php echo base_url('assets/plugins/jquery-form/jquery.form.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/dist/profiles.js'); ?>"></script>
    
</body>
</html>