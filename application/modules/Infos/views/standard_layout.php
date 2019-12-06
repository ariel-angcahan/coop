<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>System | Confirmation</title>
    
    <?PHP 
        $this->load->view('Utilities/styles'); 
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
    
	<nav class="navbar">
	    <div class="container-fluid">
	        <div class="navbar-header">
	            <a class="navbar-brand" href="#" style="margin-left: 0;">Ezware Services Corporation</a>
	            <input type="hidden" name="token" id="token" value="<?php echo $token; ?>">
	        </div>
	    </div>
	</nav>
    <!-- #END# Search Bar -->
    <!-- #Top Bar -->
    <section class="content" style="margin-left: 15px;">
            <!-- Tabs With Icon Title -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="empTable" class="card">
                        <div class="header">
                                <button class="btn sm-btn bg-blue waves-effect waves-block" id="btn-download-pdf">DOWNLOAD</button>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div id="reg-info" class="col-md-12">
                                    <table class="table table-bordered table-hover js-basic-example dataTable">
                                        <tbody>
                                            <?php echo $body; ?>
                                        </tbody>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- #END# Tabs With Icon Title -->
    </section>

    <?PHP $this->load->view('Utilities/javascripts'); ?>

    <script src="<?php echo base_url('assets/plugins/jspdf/jspdf.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/dist/info.js'); ?>"></script>
</body>

</html>