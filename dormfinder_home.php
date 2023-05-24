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
		<div class="container mt-4 mx-auto ps-5 pe-5 w-50">
			<table class="table table-striped table-bordered" id="sampleTable">
				<thead>
					<tr>
						<th>User ID</th>
						<th>Status</th>
						<th>Date Submitted</th>
					</tr>
				</thead>
				<tbody id="table1">
				</tbody>
			</table>
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
</script>