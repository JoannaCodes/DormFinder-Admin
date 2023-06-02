<!DOCTYPE html>
<html>
<head>
	<title>DormFinder</title>
	<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css"/>
	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<style type="text/css">
	.navbar {
		box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
	}

	.navbar-brand {
		font-weight: 600;
	}

	.navbar-nav .nav-link {
		font-weight: 500;
	}

	.navbar-nav .nav-link:hover {
		color: #007bff;
	}
</style>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">DormFinder</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
		</div>
	</nav>
	<div class="container mt-5 w-50">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<h2 class="mb-4">Login to DormFinder</h2>
				<form id="login-form">
					<div class="mb-3">
						<label for="email" class="form-label">Email address</label>
						<input type="email" class="form-control" id="email" name="email" required>
					</div>
					<div class="mb-3">
						<label for="password" class="form-label">Password</label>
						<input type="password" class="form-control" id="password" name="password" required>
					</div>
					<button type="submit" class="btn btn-primary">Login</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
<script>
	check_cookies();
	function check_cookies() {
		if("id" in localStorage) {
			window.location.href="http://localhost/DormFinder-Admin/dormfinder_home.php";
		}
	}
	$(document).ready(function() {
		$("#login-form").submit(function(event) {
			event.preventDefault(); // prevent form from submitting normally

			// get the form data
			var formData = {
				'email': $('#email').val(),
				'password': $('#password').val(),
				'tag': "login"
			};

			// send the form data using AJAX
			$.ajax({
				type: 'POST',
				url: 'http://localhost/DormFinder-Admin/index.php', // the PHP script that handles the login request
				data: formData,
				dataType: 'json',
				encode: true
			}).done(function(data) {
				if(data['status'] == "true") {
					localStorage.setItem('id', data['id']);
					window.location.href="http://localhost/DormFinder-Admin/dormfinder_home.php";
				} else {
					alert("email/password is not correct");
				}
			})
			.fail(function(data) {
				console.log(data);
				// handle errors here
			});
		});
	});
</script>
