<div class="container mt-4 mx-auto ps-5 pe-5 mt-5">
    <div class="row">
        <div class="col-12">
        	<div class="rounded shadow p-3 mb-5">
        		<h4 style="margin-bottom: 20px;font-weight:bold;">Admin User(s)</h4>
        		<div style="overflow:scroll;">
        			<table data-page-length='5' class="table table-striped table-bordered table-responsive" id="usersTable">
        				<thead>
        					<tr>
        						<th scope="col" style="font-family: 'Poppins', sans-serif;">User ID</th>
        						<th scope="col" style="font-family: 'Poppins', sans-serif;">E-mail Address</th>
        						<th scope="col" style="font-family: 'Poppins', sans-serif;">Date created</th>
        						<th scope="col" style="font-family: 'Poppins', sans-serif;">Actions</th>
        					</tr>
        				</thead>
        				<tbody id="users">
        				</tbody>
        			</table>
        		</div>
        	</div>
        </div>
        <div class="col-12">
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
</div>
<script type="text/javascript">
    $(document).ready(function() {
    	get_adminusers();
    	
    	$("#admin-form").submit(function(event) {
            event.preventDefault();
            add_admin();
        });
    });
    
    function get_adminusers() {
    	$.ajax({
    		url: "./admin_api.php",
    		type: "GET",
    		data: {
    			//_token: "{{ csrf_token() }}",
    			tag: "get_adminusers"
    		},
    		complete:function(response) {
    			$('#users').html(response.responseText);
    			$('#usersTable').DataTable();
    		}
    	})
    }
    function delete_admin_user(obj) {
    	var user_id = $(obj).data('id');
    
    	$.ajax({
    		url: "./admin_api.php",
    		type: "POST",
          	dataType: 'json',
    		data: {
    			//_token: "{{ csrf_token() }}",
    			tag: "delete_admin_user",
    			user_id: user_id
    		},
    		success:function(response) {
    			Swal.fire(response['message'], '', 'success')
    		},
    		error: function(xhr, status, error) {
    			alert("An error occurred: " + error);
    		},
    		complete: function() {
    		    setTimeout(() => {
    		        location.reload();
    		    }, 3000)
    		}
    	})
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