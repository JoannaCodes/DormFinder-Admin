<?php
    session_start();
    if(!isset($_SESSION['id'])) {
      // user
      header("Location: ./admin.php");
    }
    
    $page = $_GET['page'];
    
    function active($page) {
        return $_GET['page'] == $page ? 'active' : '';
    }
?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<title>StudyHive | Admin Dashboard</title>
		<meta charset="UTF-8" />
        <meta name="description" content="StudyHive" />
        <meta name="keywords" content="StudyHive" />
        <meta name="author" content="StudyHive" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="./images/logo.png" />
		<!-- jQuery -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<!-- Bootstrap 5 -->
		<link rel="stylesheet" href="https://bootswatch.com/5/cosmo/bootstrap.min.css">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
		
		<!-- DataTables -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>
		
		<!-- Font Awesome 6 Pro -->
		<link href="https://cdn.jsdelivr.net/gh/duyplus/fontawesome-pro/css/all.min.css" rel="stylesheet" type="text/css" />
		
		<!-- Poppins font -->
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
		
		<!-- SweetAlert2 -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		
		<!-- Axios -->
		<script type="text/javascript" src="https://unpkg.com/axios/dist/axios.min.js"></script>
		
		<link href="./css/admin.css" rel="stylesheet" type="text/css" />
	</head>
	<body id="studyHive">
        <header class="header" id="header">
            <div class="header_toggle"><i class='fa-regular fa-bars' id="header-toggle"></i></div>
        </header>

        <div class="l-navbar" id="nav-bar">
            <nav class="nav">
                <div>
                    <a href="#" class="nav_logo">
                        <img src="./images/logo_white.png" height="20" />
                        <span class="nav_logo-name nav_name">StudyHive</span>
                    </a>
                    <div class="nav_list">
                        <a href="./home.php" class="nav_link <?=active('');?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard"><i class="fa-solid fa-home"></i> <span class="nav_name">Dashboard</span> </a>
                        <a href="?page=admin_users" class="nav_link <?=active('admin_users');?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Admin User(s)"><i class="fa-solid fa-lock"></i> <span class="nav_name">Admin User(s)</span> </a>
                        <a href="?page=users" class="nav_link <?=active('users');?>" data-bs-toggle="tooltip" data-bs-placement="right" title="User(s)"><i class="fa-solid fa-user"></i> <span class="nav_name">User(s)</span> </a>
                        <a href="?page=dorms" class="nav_link <?=active('dorms');?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Listing(s)"><i class="fa-solid fa-house-building"></i> <span class="nav_name">Listing(s)</span> </a>
                        <a href="?page=reports" class="nav_link <?=active('reports');?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Report(s)"><i class="fa-solid fa-flag"></i> <span class="nav_name">Report(s)</span> </a>
                        <a href="?page=notification_form" class="nav_link <?=active('notification_form');?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Notification Form"><i class="fa-solid fa-envelope"></i> <span class="nav_name">Notification Form</span> </a>
                        <a href="?page=document_verifier" class="nav_link <?=active('document_verifier');?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Document Verifier"><i class="fa-solid fa-file"></i> <span class="nav_name">Document Verifier</span> </a>
                        <a href="?page=payment_transaction_history" class="nav_link <?=active('payment_transaction_history');?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment Transaction History"><i class="fa-solid fa-money-simple-from-bracket"></i> <span class="nav_name">Payment Transaction History</span> </a>
                        <a href="?page=settings" class="nav_link <?=active('settings');?>" data-bs-toggle="tooltip" data-bs-placement="right" title="Settings"><i class="fa-solid fa-gear"></i> <span class="nav_name">Settings</span> </a>
                    </div>
                </div>
                <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" class="nav_link logout"><i class="fa-solid fa-right-from-bracket"></i> <span class="nav_name">Log Out</span></a>
            </nav>
        </div>

        <div class="py-5">
            <?php
                switch($page) {
                    case 'admin_users':
                        include('./pages/admin_users.inc');
                    break;
                    case 'users':
                        include('./pages/users.inc');
                    break;
                    case 'reports':
                        include('./pages/reports.inc');
                    break;
                    case 'dorms':
                        include('./pages/dorms.inc');
                    break;
                    case 'notification_form':
                        include('./pages/notification_form.inc');
                    break;
                    case 'document_verifier':
                        include('./pages/document_verifier.inc');
                    break;
                    case 'payment_transaction_history':
                        include('./pages/payment_transaction_history.inc');
                    break;
                    case 'settings':
                        include('./pages/settings.inc');
                    break;
                    default:
                        include('./pages/dashboard.inc');
                        include('./pages/report_generation.inc');
                    break;
                }
            ?>

            <div style="clear:both;"></div>

            <div class="container mt-4 mx-auto ps-5 pe-5 mt-5">
                <div class="row">
                    <div class="col-12">
                        © 2023 - <b style="color: #0E898B;">StudyHive</b>
                    </div>
                </div>
            </div>
        </div>

        <script> 
            $(document).ready(function() {
                function showNavbar(toggleId, navId, bodyId, headerId) {
                    const toggle = $('#' + toggleId);
                    const nav = $('#' + navId);
                    const bodypd = $('#' + bodyId);
                    const headerpd = $('#' + headerId);
            
                    // Validate that all variables exist
                    if (toggle.length && nav.length && bodypd.length && headerpd.length) {
                        toggle.on('click', function() {
                            // show navbar
                            nav.toggleClass('nav-show');
                            // change icon
                            toggle.toggleClass('fa-xmark');
                            // add padding to body
                            bodypd.toggleClass('studyHive');
                            // add padding to header
                            headerpd.toggleClass('studyHive');
                        });
                    }
                }
            
                showNavbar('header-toggle', 'nav-bar', 'studyHive', 'header');
            
                const linkColor = $('.nav_link');
            
                function colorLink() {
                    if (linkColor.length) {
                        linkColor.removeClass('active');
                        $(this).addClass('active');
                    }
                }
            
                linkColor.on('click', colorLink);
            });
        </script>

        <!-- Modal -->
        <div class="modal fade" id="logoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        	<div class="modal-dialog modal-dialog-centered">
        		<div class="modal-content">
        			<div class="modal-body text-center">
        				<h5 class="mb-3 mx-auto">Are you sure you want to logout?</h5>
        				<div class="d-flex flex-row-reverse">
        					<button type="button" class="align-self-center btn btn-danger" onclick="logout()">Yes</button>
        					<button type="button" class="align-self-center btn btn-light me-2" data-bs-dismiss="modal">No</button>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>

        <script type="text/javascript">
            function logout() {
            	window.location.href="./logout.php";
            }
        </script>
	</body>
</html>