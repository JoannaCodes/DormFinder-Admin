<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>DormFinder</title>
		<!-- jQuery -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<!-- Bootstrap 5 CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
		<!-- Bootstrap 5 JS -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
		<!-- DataTables CSS -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.css"/>
		<!-- DataTables JS -->
		<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>
		<!-- Font Awesome 6 Pro -->
		<link href="https://cdn.jsdelivr.net/gh/duyplus/fontawesome-pro/css/all.min.css" rel="stylesheet" type="text/css" />
		<!-- sweetalert 2 -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	</head>
	<body>
		<!-- Navbar -->
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container-fluid">
				<a class="navbar-brand" href="#">DormFinder</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<span class="navbar-text">
					<label>Hi, Admin<span class="account_name"></span></label>
					<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
					 Logout
					</button>
				</span>
			</div>
		</nav>
		<!-- Modal -->
		<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-body text-center">
						<h5 class="mb-3 mx-auto">Are you sure you want to logout?</h5>
						<div class="d-flex flex-row-reverse">
							<button type="button" class="align-self-center btn btn-danger" onclick="logout()">Yes</button>
							<button type="button" class="align-self-center btn btn-light me-2" data-bs-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Sample table -->
		<div class="container-fluid mt-4 mx-auto ps-5 pe-5">
			<div class="row mb-4">
				<div class="col-9">
					<div class="rounded shadow p-3">
						<h4>Dorm Listing</h4>
						<div style="height: 500px;">
							<table data-page-length='5' class="table table-striped table-bordered table-responsive" id="dormsTable">
								<thead style="background-color: white;">
									<tr>
										<th>Dorm ID</th>
										<th>User ID</th>
										<th>Name</th>
										<th>Address</th>
										<th>Date Created</th>
										<th>Date Updated</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody id="dorms">
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div style="display:flex;flex-direction:column;justify-content:space-between;" class="col-3">
					<div class="rounded shadow p-3">
						<h4>Notification Form</h4>
						<form id="notif-form">
							<div class="form-group">
									<label for="userref">User ID</label>
									<input type="text" class="form-control" id="userref" placeholder="Enter User ID" required>
							</div>
							<div class="form-group">
									<label for="notifMessage">Notification Message</label>
									<textarea style="resize: none;" class="form-control" id="notifMessage" rows="4" placeholder="Enter Notification Message" required></textarea>
							</div>
							<div class="d-grid gap-2 mt-4">
									<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</form>
					</div>
					<div class="rounded shadow p-3">
						<h4>Add Admin</h4>
						<form id="admin-form">
							<div class="form-group">
								<label for="admin">Email</label>
								<input type="text" class="form-control" id="admin" name="admin" placeholder="Enter Admin Email Address">
							</div>
							<div class="d-grid gap-2 mt-4">
								<button type="submit" class="btn btn-primary">Add</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="row mb-4">
				<div class="col-6">
					<div class="rounded shadow p-3">
						<h4>Users</h4>
						<div style="height: 300px;">
							<table data-page-length='5' class="table table-striped table-bordered table-responsive" id="usersTable">
								<thead>
									<tr>
										<th scope="col">User ID</th>
										<th scope="col">Username</th>
										<th scope="col">Verification Status</th>
									</tr>
								</thead>
								<tbody id="users">
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-6">
					<div class="rounded shadow p-3">
						<h4>Document Verifier</h4>
						<div style="height: 300px;">
							<table data-page-length='5' class="table table-striped table-bordered table-responsive" id="sampleTable">
								<thead>
									<tr>
										<th>User ID</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody id="table1">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

<!-- Modal -->
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
				url: "http://localhost/DormFinder-Admin/index.php",
				type: "POST",
				data: {
					_token: "{{ csrf_token() }}",
					tag: "change_status",
					btn_value: btn_value
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
		url: "http://localhost/DormFinder-Admin/index.php",
		type: "POST",
		data: {
			_token: "{{ csrf_token() }}",
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
	localStorage.clear();
	window.location.href="http://localhost/DormFinder-Admin/dormfinder_admin.php";
}
check_cookies();
function check_cookies() {
	if("id" in localStorage) {

	} else {
		window.location.href="http://localhost/DormFinder-Admin/dormfinder_admin.php";
	}
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
				url: "http://localhost/DormFinder-Admin/index.php",
				type: "POST",
				data: {
					_token: "{{ csrf_token() }}",
					tag: "delete_dorm_admin",
					userref: userref,
					dormref: dormref
				},
				complete:function(response) {
					Swal.fire('Dorm listing Deleted', '', 'success')
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
		url: "http://localhost/DormFinder-Admin/index.php",
		type: "POST",
		data: {
			_token: "{{ csrf_token() }}",
			tag: "send_dorm_notif",
			userref: userref,
			dormref: dormref
		},
		complete:function(response) {
			alert(response.responseText)
		}
	})
}

// Initialize DataTable
function verify_document(obj) {
	var id = $(obj).data('id');
	var docvalue = $(obj).data('docvalue');
	$.ajax({
		url: "http://localhost/DormFinder-Admin/index.php",
		type: "POST",
		data: {
			_token: "{{ csrf_token() }}",
			tag: "verify_document",
			id: id,
			docvalue: docvalue
		},
		complete:function(response) {
			alert("Document is Verified!");
			location.reload();
			console.log(response.responseText);
		}
	})
}

$(document).ready(function() {
	get_userdoc();
	get_dormlisting();
	get_users();
	$("#notif-form").submit(function(event) {
			event.preventDefault();
			send_custom_notif();
	});
	$("#admin-form").submit(function(event) {
        event.preventDefault();
        add_admin();
    });
});

function get_userdoc() {
	$.ajax({
		url: "http://localhost/DormFinder-Admin/index.php",
		type: "GET",
		data: {
			_token: "{{ csrf_token() }}",
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
		url: "http://localhost/DormFinder-Admin/index.php",
		type: "GET",
		data: {
			_token: "{{ csrf_token() }}",
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
		url: "http://localhost/DormFinder-Admin/index.php",
		type: "GET",
		data: {
			_token: "{{ csrf_token() }}",
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

	$.ajax({
		url: "http://localhost/DormFinder-Admin/index.php", // Replace with the correct URL or file path
		type: "POST",
		data: {
			tag: "send_custom_notif",
			userref: userref,
			notifMessage: notifMessage
		},
		complete: function(response) {
			alert(response.responseText);
			location.reload();
		}
	});
}

function add_admin() {
	var adminEmail = $('#admin').val();

	$.ajax({
			url: "http://localhost/DormFinder-Admin/index.php", // Replace with the correct URL or file path
			type: "POST",
			data: {
					tag: "add_admin",
					email: adminEmail
			},
			complete: function(response) {
					alert(response.responseText);
					location.reload();
			}
	});
}
</script>