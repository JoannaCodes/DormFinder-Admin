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
		<link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro-v6@44659d9/css/all.min.css" rel="stylesheet" type="text/css" />
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
						<th>Document</th>
						<th>Status</th>
						<th width="10">Action</th>
					</tr>
				</thead>
				<tbody id="table1">
				</tbody>
			</table>
		</div>
	</body>
</html>


<script>
function logout() {
	localStorage.clear();
	window.location.href="http://localhost/dormfinder_php/dormfinder_admin.php";
}
check_cookies();
function check_cookies() {
	if("id" in localStorage) {

	} else {
		window.location.href="http://localhost/dormfinder_php/dormfinder_admin.php";
	}
}
// Initialize DataTable
function verify_document(obj) {
	var id = $(obj).data('id');
	var docvalue = $(obj).data('docvalue');
	$.ajax({
		url: "http://localhost/dormfinder_php/index.php",
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
	$.ajax({
		url: "http://localhost/dormfinder_php/index.php",
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
});
</script>