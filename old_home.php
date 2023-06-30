<?php
    session_start();
    if(!isset($_SESSION['id'])) {
      // user
      header("Location: ./admin.php");
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
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
		
		<!-- DataTables -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>
		
		<!-- Font Awesome 6 Pro -->
		<link href="https://cdn.jsdelivr.net/gh/duyplus/fontawesome-pro/css/all.min.css" rel="stylesheet" type="text/css" />
		
		<!-- Study Hive CSS -->
		<link href="./css/index.css" rel="stylesheet" type="text/css" />
		
		<!-- Poppins font -->
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
		
		<!-- SweetAlert2 -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		
		<!-- Axios -->
		<script type="text/javascript" src="https://unpkg.com/axios/dist/axios.min.js"></script>
	</head>
	<body style="padding-bottom: 50px;">
		<!-- Navbar -->
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container">
				<a class="navbar-brand" href="#" style="font-family: 'Poppins', sans-serif;">
				    <img src="./images/logo.png" style="width: 30px;height: 30px;margin-right: 10px;margin-top: -10px;">
				    StudyHive Admin
				</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#studyHiveNavBar" aria-controls="studyHiveNavBar" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="studyHiveNavBar">
                    <ul class="navbar-nav ms-md-auto">
                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-user" style="margin-right: 5px;"></i> <?=$_SESSION['name'];?></a>
                          <div class="dropdown-menu">
                            <span style="margin: 0 14px; font-weight: bold; color: #bababa;">SETTINGS</span>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Logout</a>
                          </div>
                        </li>
                    </ul>
                </div>
			</div>
		</nav>
        <!-- -->
        <div class="container mt-4 mx-auto ps-5 pe-5 mt-5">
            <div class="row">
    		    <div class="col-xl-3 col-lg-6">
                  <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-uppercase text-muted mb-0">Verified User(s)</h5>
                          <span class="h2 font-weight-bold mb-0" id="verified-count">0</span>
                        </div>
                        <div class="col-auto">
                          <div class="icon icon-shape bg-danger text-white rounded-circle shadow" style="padding: 5px 10px;background:#0E898B!important;">
                            <i class="fa-solid fa-user"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                  <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-uppercase text-muted mb-0">Unverified User(s)</h5>
                          <span class="h2 font-weight-bold mb-0" id="notverified-count">0</span>
                        </div>
                        <div class="col-auto">
                          <div class="icon icon-shape bg-danger text-white rounded-circle shadow" style="padding: 5px 10px;background:#0E898B!important;">
                            <i class="fa-solid fa-user"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                  <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-uppercase text-muted mb-0">Dorm(s)</h5>
                          <span class="h2 font-weight-bold mb-0" id="dorms-count">0</span>
                        </div>
                        <div class="col-auto">
                          <div class="icon icon-shape bg-danger text-white rounded-circle shadow" style="padding: 7px 10px;background:#0E898B!important;">
                            <i class="fa-solid fa-house"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                  <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-uppercase text-muted mb-0">Report(s)</h5>
                          <span class="h2 font-weight-bold mb-0" id="reports-count">0</span>
                        </div>
                        <div class="col-auto">
                          <div class="icon icon-shape bg-danger text-white rounded-circle shadow" style="padding: 7px 10px;background:#0E898B!important;">
                            <i class="fa-solid fa-flag"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
		</div>
		
		<!-- Tables -->
		<div class="container mt-4 mx-auto ps-5 pe-5 mt-5">
			<div class="row mb-4">
				<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
					<div class="rounded shadow p-3 mb-5">
						<h4 style="font-family: 'Poppins', sans-serif;">Dorm Listing</h4>
						<div style="height: 500px;overflow: scroll;">
							<table data-page-length='5' class="table table-striped table-bordered table-responsive" id="dormsTable">
								<thead style="background-color: white;">
									<tr>
										<th>Dorm ID</th>
										<th>User ID</th>
										<th>Name</th>
										<th>Address</th>
										<th>Date Created</th>
										<th>Date Updated</th>
										<th style="width:97px;">Actions</th>
									</tr>
								</thead>
								<tbody id="dorms">
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-3 col-lg-3">
					<div class="rounded shadow p-3 mb-5">
						<h4 style="font-family: 'Poppins', sans-serif;">Notification Form</h4>
						<form id="notif-form">
							<div class="form-group">
									<label for="userref">User ID</label>
									<input type="text" class="form-control" id="userref" placeholder="Enter User ID" required>
							</div>
							<div class="form-group mt-2">
									<label for="notifMessage">Notification Message</label>
									<textarea style="resize: none;" class="form-control" id="notifMessage" rows="4" placeholder="Enter Notification Message" required></textarea>
							</div>
							<div class="d-grid gap-2 mt-3">
									<button type="submit" class="btn btn-primary" id="notif-btn">Send</button>
							</div>
						</form>
					</div>
					<div class="rounded shadow p-3 mb-5">
						<h4 style="font-family: 'Poppins', sans-serif;">Add Admin</h4>
						<form id="admin-form">
							<div class="form-group">
								<label for="admin">Email</label>
								<input type="email" class="form-control" id="admin" name="admin" placeholder="Enter Admin Email Address" required>
							</div>
							<div class="d-grid gap-2 mt-4">
								<button type="submit" class="btn btn-primary" id="admin-btn">Add</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="row mb-4">
				<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
					<div class="rounded shadow p-3 mb-5">
						<h4 style="font-family: 'Poppins', sans-serif;">Users</h4>
						<div style="height: 400px;overflow:scroll;">
							<table data-page-length='5' class="table table-striped table-bordered table-responsive" id="usersTable">
								<thead>
									<tr>
										<th scope="col" style="font-family: 'Poppins', sans-serif;">User ID</th>
										<th scope="col" style="font-family: 'Poppins', sans-serif;">Username</th>
										<th scope="col" style="font-family: 'Poppins', sans-serif;">Status</th>
									</tr>
								</thead>
								<tbody id="users">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
					<div class="rounded shadow p-3 mb-5">
						<h4 style="font-family: 'Poppins', sans-serif;">Document Verifier</h4>
						<div style="height: 400px;overflow: scroll;">
							<table data-page-length='5' class="table table-striped table-bordered table-responsive" id="sampleTable">
								<thead>
									<tr>
										<th style="font-family: 'Poppins', sans-serif;">User ID</th>
										<th style="font-family: 'Poppins', sans-serif;">Status</th>
									</tr>
								</thead>
								<tbody id="table1">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-12">
					<div class="rounded shadow p-3 mb-5">
						<h4 style="font-family: 'Poppins', sans-serif;">Reports</h4>
							<div style="height: 500px;overflow:scroll;">
								<table data-page-length='5' class="table table-striped table-bordered table-responsive" id="reportsTable">
									<thead>
										<tr>
											<th scope="col" style="font-family: 'Poppins', sans-serif;">Report ID</th>
											<th scope="col" style="font-family: 'Poppins', sans-serif;">User</th>
											<th scope="col" style="font-family: 'Poppins', sans-serif;">Dorm Reported</th>
											<th scope="col" style="font-family: 'Poppins', sans-serif;">Message</th>
											<th scope="col" style="font-family: 'Poppins', sans-serif;">Date Created</th>
											<th scope="col" style="font-family: 'Poppins', sans-serif;">Actions</th>
										</tr>
									</thead>
									<tbody id="reports">
									</tbody>
								</table>
							</div>
						</div>
				</div>
			</div>
			
			<div class="row">
			    <div class="col-12">
                     &copy; 2023 - <b style="font-family: 'Poppins', sans-serif; color: #0E898B;">StudyHive</b>
                </div>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
        <div class="modal fade" id="open_userdocumodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        	<div class="modal-dialog">
        		<div class="modal-content">
        			<div class="modal-header">
        				<h5 class="modal-title" id="exampleModalLabel">DormFinder</h5>
        				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        			</div>
        			<div class="modal-body">
        				<input type='hidden' id="status_value" />
        				<p>User <span class='userid_data'></span>:</p>
        				<div class="fill_data w-100">
        				</div>
        			</div>
        			<div class="modal-footer">
        				<button type="button" class="btn btn-danger" data-value='2' onclick="change_status(this)">Unverify</button>
        				<button type="button" class="btn btn-success" data-value='1' onclick="change_status(this)">Verify</button>
        			</div>
        		</div>
        	</div>
        </div>
        <script>
        function change_status(obj) {
        	var doc1_statusval = $('#status_value').val();
        	var btn_value = $(obj).data('value');
        	var user_id = $('#user_id').val();
        	Swal.fire({
        		title: 'Do you want to save the changes?',
        		showDenyButton: true,
        		showCancelButton: false,
        		confirmButtonText: 'Yes',
        		denyButtonText: `No`,
        	}).then((result) => {
        		/* Read more about isConfirmed, isDenied below */
        		if (result.isConfirmed) {
        			$.ajax({
        				url: "./admin_api.php",
        				type: "POST",
        				data: {
        					//_token: "{{ csrf_token() }}",
        					tag: "change_status",
        					btn_value: btn_value,
        					user_id: user_id
        				},
        				complete:function(response) {
        					Swal.fire('Changes are saved!', '', 'success')
        					get_userdoc();
        					$('#open_userdocumodal').modal('hide');
        				}
        			})
        		} else if (result.isDenied) {
        			Swal.fire('Changes are not saved', '', 'info')
        			$('#open_userdocumodal').modal('hide');
        		}
        	})
        }
        function open_userdoc(obj) {
        	var user_id = $(obj).data('user_id');
        	var status_val = $(obj).data('doc_status');
        	$('.userid_data').text(user_id);
        	$('#status_value').val(status_val);
        	$.ajax({
        		url: "./admin_api.php",
        		type: "POST",
        		data: {
        			//_token: "{{ csrf_token() }}",
        			tag: "open_document",
        			user_id: user_id
        		},
        		complete:function(response) {
        			$('.fill_data').html(response.responseText);
        			 $('#open_userdocumodal').modal('show');
        		}
        	})
        }
          
        function logout() {
        	window.location.href="./logout.php";
        }
        
        function delete_dorm_admin(obj) {
        	var dormref = $(obj).data('dormref');
        	var userref = $(obj).data('userref');
        
        	Swal.fire({
        		title: `Continue deleting this dorm listing with id: ${dormref}`,
        		showDenyButton: true,
        		showCancelButton: false,
        		confirmButtonText: 'Yes',
        		denyButtonText: `No`,
        	}).then((result) => {
        		/* Read more about isConfirmed, isDenied below */
        		if (result.isConfirmed) {
        			$.ajax({
        				url: "./admin_api.php",
        				type: "POST",
        				data: {
        					//_token: "{{ csrf_token() }}",
        					tag: "delete_dorm_admin",
        					userref: userref,
        					dormref: dormref
        				},
        				success:function(response) {
        					Swal.fire('Dorm listing Deleted', '', 'success')
        				},
        				error: function(xhr, status, error) {
        					Swal.fire('An error occured', '', 'error')
        					location.reload();
        				},
        				complete: function() {
        					location.reload();
        				}
        			})
        		} else if (result.isDenied) {
        			Swal.fire('Deletion canceled', '', 'info')
        		}
        	})
        }
        
        function send_dorm_notif(obj) {
        	var userref = $(obj).data('userref');
        	var dormref = $(obj).data('dormref');
        	$.ajax({
        		url: "./admin_api.php",
        		type: "POST",
              	dataType: 'json',
        		data: {
        			//_token: "{{ csrf_token() }}",
        			tag: "send_dorm_notif",
        			userref: userref,
        			dormref: dormref
        		},
        		success:function(response) {
        			Swal.fire(response['message'], '', 'success')
        		},
        		error: function(xhr, status, error) {
        			alert("An error occurred: " + error);
        		},
        	})
        }
        
        function resolve_report_admin(obj) {
        	var reportid = $(obj).data('reportid');
        
        	$.ajax({
        		url: "./admin_api.php",
        		type: "POST",
              	dataType: 'json',
        		data: {
        			//_token: "{{ csrf_token() }}",
        			tag: "resolve_report_admin",
        			reportid: reportid
        		},
        		success:function(response) {
        			Swal.fire(response['message'], '', 'success')
        		},
        		error: function(xhr, status, error) {
        			alert("An error occurred: " + error);
        		},
        		complete: function() {
        			location.reload();
        		}
        	})
        }
        
        const autoType = (param) => {
            let el = document.querySelector(param.el);
            let speed = param.speed;
            let max_number = param.max_number; 
            
            if(max_number != parseInt(el.textContent)) {
                [...Array(max_number + 1).keys()].map((i) => {
                    setTimeout(() => {
                        el.textContent = i;
                    }, speed * i);
                });
            }
        };
            
        function showhide_dorm_admin(obj) {
        	var userref = $(obj).data('userref');
        	var dormref = $(obj).data('dormref');
        	var hide_icon="<i class='fa-light fa-eye-slash fa-fw fa-lg'></i>";
		    var show_icon="<i class='fa-light fa-eye-slash fa-fw fa-lg'></i>";
		    
        	$.ajax({
        		url: "./admin_api.php",
        		type: "POST",
              	dataType: 'json',
        		data: {
        			//_token: "{{ csrf_token() }}",
        			tag: "showhide_dorm_admin",
        			userref: userref,
        			dormref: dormref
        		},
        		success:function(response) {
        			Swal.fire(response['message'], '', 'success')
        			$('#dorms').html('');
        			get_dormlisting();
        		},
        		error: function(xhr, status, error) {
        			alert("An error occurred: " + error);
        		},
        	})
        }
        
        function get_statistics() {
        	$.ajax({
        		url: "./admin_api.php",
        		type: "POST",
              	dataType: 'json',
        		data: {
        			//_token: "{{ csrf_token() }}",
        			tag: "statistics",
        		},
        		success:function(response) {
        			autoType({ el: "#notverified-count", speed: 100, max_number: response.not_verified });
        			autoType({ el: "#verified-count", speed: 100, max_number: response.verified });
        			autoType({ el: "#dorms-count", speed: 100, max_number: response.dorms });
        			autoType({ el: "#reports-count", speed: 100, max_number: response.reports });
        		},
        		error: function(xhr, status, error) {
        			alert("An error occurred: " + error);
        		},
        	})
        }
        
        // Initialize DataTable
        $(document).ready(function() {
        	get_userdoc();
        	get_dormlisting();
        	get_users();
        	get_reports();
        	
        	get_statistics();
        	setInterval(() => {
        	    get_statistics();
        	}, 1000);
        	
        	$("#notif-form").submit(function(event) {
        			event.preventDefault();
        			send_custom_notif();
        	});
        	$("#admin-form").submit(function(event) {
                event.preventDefault();
                add_admin();
            });
        });
        
        //reports
        function get_reports() {
        	$.ajax({
        		url: "./admin_api.php",
        		type: "GET",
        		data: {
        			//_token: "{{ csrf_token() }}",
        			tag: "get_reports"
        		},
        		complete:function(response) {
        			$('#reports').html(response.responseText);
        			$('#reportsTable').DataTable();
        		}
        	})
        }
        
        
        function get_userdoc() {
        	$.ajax({
        		url: "./admin_api.php",
        		type: "GET",
        		data: {
        			//_token: "{{ csrf_token() }}",
        			tag: "get_submitdocuments"
        		},
        		complete:function(response) {
        			$('#table1').html(response.responseText);
        			$('#sampleTable').DataTable();
        
        			var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        			var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        				return new bootstrap.Tooltip(tooltipTriggerEl)
        			})
        		}
        	})
        }
        
        function get_dormlisting() {
        	$.ajax({
        		url: "./admin_api.php",
        		type: "GET",
        		data: {
        			//_token: "{{ csrf_token() }}",
        			tag: "get_dormlisting"
        		},
        		complete:function(response) {
        			$('#dorms').html(response.responseText);
        			$('#dormsTable').DataTable();
        		}
        	})
        }
        
        function get_users() {
        	$.ajax({
        		url: "./admin_api.php",
        		type: "GET",
        		data: {
        			//_token: "{{ csrf_token() }}",
        			tag: "get_users"
        		},
        		complete:function(response) {
        			$('#users').html(response.responseText);
        			$('#usersTable').DataTable();
        		}
        	})
        }
        
        function send_custom_notif(){
        	var userref = $('#userref').val();
        	var notifMessage = $('#notifMessage').val();
        	var btn = $('#notif-btn');
        
        	btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        	btn.prop('disabled', true);
        
        	$.ajax({
        		url: "./admin_api.php", // Replace with the correct URL or file path
        		type: "POST",
              	dataType: 'json',
        		data: {
        			tag: "send_custom_notif",
        			userref: userref,
        			notifMessage: notifMessage
        		},
        		success: function(response) {
        			Swal.fire(response['message'], '', 'success')
        		},
        		error: function(xhr, status, error) {
        			alert("An error occurred: " + error);
        			btn.html('Send');
        			btn.prop('disabled', false);
        		},
        		complete: function() {
        			btn.html('Send');
        			btn.prop('disabled', false);
        		}
        	});
        }
        
        function add_admin() {
        	var adminEmail = $('#admin').val();
        	var btn = $('#admin-btn');
        
        	btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        	btn.prop('disabled', true);
        
        	$.ajax({
        			url: "./admin_api.php", // Replace with the correct URL or file path
        			type: "POST",
              		dataType: 'json',
        			data: {
        					tag: "add_admin",
        					email: adminEmail
        			},
        			success: function(response) {
                      Swal.fire(response['message'], '', 'success')
                      btn.html('Add');
                      btn.prop('disabled', false);
        			},
        			error: function(xhr, status, error) {
                      alert("An error occurred: " + error);
                      btn.html('Add');
                      btn.prop('disabled', false);
        			},
        			complete: function() {
        				btn.html('Add');
        				btn.prop('disabled', false);
        			}
        	});
        }
        </script>
	</body>
</html>