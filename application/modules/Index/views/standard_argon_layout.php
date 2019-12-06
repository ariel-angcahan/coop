<!--

=========================================================
* Argon Design System - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-design-system
* Copyright 2019 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Design System for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>Argon Design System - Free Design System for Bootstrap 4</title>
  <!-- Favicon -->
  <link href="<?php echo base_url('./assets2/img/brand/favicon.png'); ?>" rel="icon" type="image/png">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <!-- Icons -->
  <link href="<?php echo base_url('./assets2/vendor/nucleo/css/nucleo.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('./assets2/vendor/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
  <!-- Argon CSS -->
  <link type="text/css" href="<?php echo base_url('./assets2/css/argon.css?v=1.1.0'); ?>" rel="stylesheet">
</head>

<body>
  <header class="header-global">
    <nav id="navbar-main" class="navbar navbar-main navbar-expand-lg navbar-transparent navbar-light headroom">
      <div class="container">
        <a class="navbar-brand mr-lg-5" href="javascript:void(0);">
          <img alt="image" src="<?php echo base_url('./assets2/img/brand/white.png'); ?>">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbar_global">
          <div class="navbar-collapse-header">
            <div class="row">
              <div class="col-6 collapse-brand">
                <a href="javascript:void(0);">
                  <img alt="image" src="<?php echo base_url('./assets2/img/brand/blue.png'); ?>">
                </a>
              </div>
              <div class="col-6 collapse-close">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                  <span></span>
                  <span></span>
                </button>
              </div>
            </div>
          </div>
          <ul class="navbar-nav navbar-nav-hover align-items-lg-center">
            <li class="nav-item dropdown">
              <a href="#" class="nav-link" data-toggle="dropdown" role="button">
                <i class="ni ni-ui-04 d-lg-none"></i>
                <span class="nav-link-inner--text">Quick navigate</span>
              </a>
              <div class="dropdown-menu dropdown-menu-xl">
                <div class="dropdown-menu-inner">
                  <a href="<?php echo base_url('Registrations'); ?>" class="media d-flex align-items-center">
                    <div class="icon icon-shape bg-gradient-primary rounded-circle text-white">
                      <i class="ni ni-spaceship"></i>
                    </div>
                    <div class="media-body ml-3">
                      <h6 class="heading text-primary mb-md-1">Join Now</h6>
                      <p class="description d-none d-md-inline-block mb-0">Learn how to use Argon compiling Scss, change brand colors and more.</p>
                    </div>
                  </a>
                  <a href="<?php echo base_url('Inquiry'); ?>" class="media d-flex align-items-center">
                    <div class="icon icon-shape bg-gradient-success rounded-circle text-white">
                      <i class="ni ni-palette"></i>
                    </div>
                    <div class="media-body ml-3">
                      <h6 class="heading text-primary mb-md-1">Member Inquiry</h6>
                      <p class="description d-none d-md-inline-block mb-0">Learn more about colors, typography, icons and the grid system we used for Argon.</p>
                    </div>
                  </a>
                  <a href="<?php echo base_url('Logins'); ?>" class="media d-flex align-items-center">
                    <div class="icon icon-shape bg-gradient-warning rounded-circle text-white">
                      <i class="ni ni-ui-04"></i>
                    </div>
                    <div class="media-body ml-3">
                      <h5 class="heading text-warning mb-md-1">Login</h5>
                      <p class="description d-none d-md-inline-block mb-0">Browse our 50 beautiful handcrafted components offered in the Free version.</p>
                    </div>
                  </a>
                </div>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav align-items-lg-center ml-lg-auto">
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="javascript:void(0);" data-toggle="tooltip" title="Like us on Facebook">
                <i class="fa fa-facebook-square"></i>
                <span class="nav-link-inner--text d-lg-none">Facebook</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="javascript:void(0);" data-toggle="tooltip" title="Follow us on Instagram">
                <i class="fa fa-instagram"></i>
                <span class="nav-link-inner--text d-lg-none">Instagram</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="javascript:void(0);" data-toggle="tooltip" title="Follow us on Twitter">
                <i class="fa fa-twitter-square"></i>
                <span class="nav-link-inner--text d-lg-none">Twitter</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="javascript:void(0);" data-toggle="tooltip" title="Star us on Github">
                <i class="fa fa-github"></i>
                <span class="nav-link-inner--text d-lg-none">Github</span>
              </a>
            </li>
            <li class="nav-item d-none d-lg-block ml-lg-4">
              <a href="<?php echo base_url('Logins'); ?>" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon">
                  <i class="ni ni-key-25 text-info"></i>
                </span>
                <span class="nav-link-inner--text">LOGIN</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <main>
    <!--

=========================================================
* Argon Design System - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-design-system
* Copyright 2019 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
    <div class="position-relative">
      <!-- Hero for FREE version -->
      <section class="section section-lg section-hero section-shaped">
        <!-- Background circles -->
        <div class="shape shape-style-1 shape-primary">
          <span class="span-150"></span>
          <span class="span-50"></span>
          <span class="span-50"></span>
          <span class="span-75"></span>
          <span class="span-100"></span>
          <span class="span-75"></span>
          <span class="span-50"></span>
          <span class="span-100"></span>
          <span class="span-50"></span>
          <span class="span-100"></span>
        </div>
        <div class="container shape-container d-flex align-items-center py-lg">
          <div class="col px-0">
            <div class="row align-items-center justify-content-center">
              <div class="col-lg-6 text-center">
                <img alt="image" src="<?php echo base_url('./assets2/img/brand/white.png'); ?>" style="width: 200px;" class="img-fluid">
                <p class="lead text-white">A beautiful Design System for Bootstrap 4. It's Free and Open Source.</p>
                <div class="btn-wrapper mt-5">
                  <a href="<?php echo base_url('Registrations') ?>" class="btn btn-lg btn-white btn-icon mb-3 mb-sm-0">
                    <span class="btn-inner--icon"><i class="ni ni-cloud-download-95"></i></span>
                    <span class="btn-inner--text">JOIN NOW</span>
                  </a>
                  <a href="<?php echo base_url('Inquiry') ?>" class="btn btn-lg btn-github btn-icon mb-3 mb-sm-0">
                    <span class="btn-inner--icon"><i class="fa fa-github"></i></span>
                    <span class="btn-inner--text"><span class="text-warning">MEMBER</span> INQUIRY</span>
                  </a>
                </div>
                <div class="mt-5">
                  <small class="text-white font-weight-bold mb-0 mr-2">*Powered by</small>
                  <img alt="image" src="<?php echo base_url('./assets2/img/brand/white.png'); ?>" style="height: 28px;">
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- SVG separator -->
        <div class="separator separator-bottom separator-skew zindex-100">
          <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <polygon class="fill-white" points="2560 0 2560 100 0 100"></polygon>
          </svg>
        </div>
      </section>
    </div>
  </main>
  <footer class="footer has-cards">
    <div class="container container-lg">
      <div class="row">
        <div class="col-md-6 mb-5 mb-md-0">
          <div class="card card-lift--hover shadow border-0">
            <table class="table align-items-center table-dark" style="margin: 0px !important; border-radius: 7px !important;">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Member</th>
                        <th scope="col">Joined Date</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $list; ?>
                </tbody>
            </table>
            <!-- <a href="./examples/landing.html" title="Landing Page">
              <img alt="image" src="<?php echo base_url('./assets2/img/theme/landing.jpg'); ?>" class="card-img">
            </a> -->
          </div>
        </div>
        <div class="col-md-6 mb-5 mb-lg-0">
          <div class="card card-lift--hover shadow border-0">
            <a href="./examples/profile.html" title="Profile Page">
              <img alt="image" src="<?php echo base_url('./assets2/img/theme/profile.jpg'); ?>" class="card-img">
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row row-grid align-items-center my-md">
        <div class="col-lg-6">
          <h3 class="text-primary font-weight-light mb-2">Thank you for supporting us!</h3>
          <h4 class="mb-0 font-weight-light">Let's get in touch on any of these platforms.</h4>
        </div>
        <div class="col-lg-6 text-lg-center btn-wrapper">
          <a href="javascript:void(0);" class="btn btn-neutral btn-icon-only btn-twitter btn-round btn-lg" data-toggle="tooltip" data-original-title="Follow us">
            <i class="fa fa-twitter"></i>
          </a>
          <a href="javascript:void(0);" class="btn btn-neutral btn-icon-only btn-facebook btn-round btn-lg" data-toggle="tooltip" data-original-title="Like us">
            <i class="fa fa-facebook-square"></i>
          </a>
          <a href="javascript:void(0);" class="btn btn-neutral btn-icon-only btn-dribbble btn-lg btn-round" data-toggle="tooltip" data-original-title="Follow us">
            <i class="fa fa-dribbble"></i>
          </a>
          <a href="javascript:void(0);" class="btn btn-neutral btn-icon-only btn-github btn-round btn-lg" data-toggle="tooltip" data-original-title="Star on Github">
            <i class="fa fa-github"></i>
          </a>
        </div>
      </div>
      <hr>
      <div class="row align-items-center justify-content-md-between">
        <div class="col-md-6">
          <div class="copyright">
            &copy; 2019 <a href="javascript:void(0);">Ezware Servervices Corporation</a>.
          </div>
        </div>
        <div class="col-md-6">
          <ul class="nav nav-footer justify-content-end">
            <li class="nav-item">
              <a href="javascript:void(0);" class="nav-link">Ezware Servervices Corporation</a>
            </li>
            <li class="nav-item">
              <a href="javascript:void(0);" class="nav-link">About Us</a>
            </li>
            <li class="nav-item">
              <a href="javascript:void(0);" class="nav-link">Blog</a>
            </li>
            <li class="nav-item">
              <a href="javascript:void(0);" class="nav-link">MIT License</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
  <!-- Core -->
  <script src="<?php echo base_url('./assets2/vendor/jquery/jquery.min.js'); ?>"></script>
  <script src="<?php echo base_url('./assets2/vendor/popper/popper.min.js'); ?>"></script>
  <script src="<?php echo base_url('./assets2/vendor/bootstrap/bootstrap.min.js'); ?>"></script>
  <script src="<?php echo base_url('./assets2/vendor/headroom/headroom.min.js'); ?>"></script>
  <!-- Optional JS -->
  <script src="<?php echo base_url('./assets2/vendor/onscreen/onscreen.min.js'); ?>"></script>
  <script src="<?php echo base_url('./assets2/vendor/nouislider/js/nouislider.min.js'); ?>"></script>
  <script src="<?php echo base_url('./assets2/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>
  <!-- Argon JS -->
  <script src="<?php echo base_url('./assets2/js/argon.js?v=1.1.0'); ?>"></script>
</body>

</html>
