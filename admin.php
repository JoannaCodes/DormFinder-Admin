<?php
    session_start();
    if(isset($_SESSION['id'])) {
      // user
      header("Location: ./home.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<title>StudyHive</title>
    	<meta charset="UTF-8" />
			<meta name="description" content="StudyHive" />
			<meta name="keywords" content="StudyHive" />
			<meta name="author" content="StudyHive" />
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="icon" type="image/png" href="./images/logo.png" />
    	<!-- jQuery -->
    	<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    	<!-- Bootstrap -->
    	<link rel="stylesheet" href="https://bootswatch.com/5/cosmo/bootstrap.min.css"/>
    	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    	<!-- Study Hive CSS -->
    	<link href="./css/index.css" rel="stylesheet" type="text/css" />
    	<!-- SweetAlert2 -->
    	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    	<!-- Google Fonts -->
    	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    </head>
    <body style="padding-bottom: 50px;">
    	<nav class="navbar navbar-expand-lg navbar-light bg-light">
    		<div class="container">
    			<a class="navbar-brand" href="#">
						<img src="./images/logo.png" style="width: 30px;height: 30px;margin-right: 10px;margin-top: -10px;">
						StudyHive
    			</a>
    		</div>
    	</nav>

    	<div class="container mt-5">
    		<div class="row">
    		    <div class="col-0 col-xs-0 col-sm-0 col-md-9 col-lg-9 col-lg-9">
    		      <img src="./images/undraw_Login_re_4vu2.png" class="img-fluid"/>
    		    </div>
    			<div class="col-12 col-xs-12 col-sm-12 col-md-3 col-lg-3 col-lg-3 mt-4">
    				<h2 class="mb-4">Login to StudyHive</h2>
    				<form id="login-form">
    					<div class="mb-3">
    						<label for="email" class="form-label" style="margin-bottom: -23px; margin-left: 12px; font-size: 12px; display: block; width: max-content;">Email address</label>
    						<input type="email" class="form-control" id="email" name="email" style="padding-top: 24px;" required>
    					</div>
    					<div class="mb-3">
    						<label for="password" class="form-label" style="margin-bottom: -23px; margin-left: 12px; font-size: 12px; display: block; width: max-content;">Password</label>
    						<input type="password" class="form-control" id="password" name="password" style="padding-top: 24px;" required>
    					</div>
    					<div class="d-grid gap-2 mt-4">
    						<button type="submit" class="btn btn-primary" id="login-btn">Login</button>
    					</div>
    				</form>
    			</div>
    		</div>
    	</div>

    	<div class="container mt-5 w-50">
    		<div class="row justify-content-center">
    		  &copy; 2023 - <b style="color: #0E898B;display:contents;">StudyHive</b>
    		</div>
      </div>

    	<script>
        	$(document).ready(function() {
        		$("#login-form").submit(function(event) {
        			event.preventDefault(); // prevent form from submitting normally
                  
            	var btn = $('#login-btn');
        
        			btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        			btn.prop('disabled', true);
        
        			// get the form data
        			var formData = {
        				'email': $('#email').val(),
        				'password': $('#password').val(),
        				'tag': "login"
        			};
        
        			// send the form data using AJAX
        			$.ajax({
        				type: 'POST',
        				url: './admin_api.php', // the PHP script that handles the login request
        				data: formData,
        				dataType: 'json',
        				encode: true
        			}).done(function(data) {
        				if(data['status'] === true || data['status'] === "true") {
        				    Swal.fire({
											icon: 'success',
											title: 'Good job!',
											text: 'Successfully login! Wait for 5 seconds to redirect you in main page.',
										})
                            
        				    setTimeout(() => {
        				        window.location.href="./home.php";
        				    }, 3000)
        					
        				} else {
        				    Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Invalid email/password.',
										})
        				}

                btn.html('Login');
        				btn.prop('disabled', false);
        			})
        			.fail(function(data) {
        				console.log(data);
        			});
        		});
        	});
        </script>
    </body>
</html>
