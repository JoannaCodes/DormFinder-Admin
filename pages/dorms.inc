<div class="container mt-4 mx-auto ps-5 pe-5 mt-5">
    <div class="row">
		<div class="col-12">
			<div class="rounded shadow p-3 mb-5">
				<h4 style="font-weight:bold;margin-bottom: 20px;">Student Accommodation Listing(s)</h4>
				<div style="overflow: scroll;">
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
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
    	get_dormlisting();
    });
    
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
</script>