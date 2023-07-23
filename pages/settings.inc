<div class="container mt-4 mx-auto ps-5 pe-5 mt-5">
    <div class="row">
        <div class="col-12">
			<div class="rounded shadow p-3 mb-5">
				<h4 style="font-weight:bold;margin-bottom: 20px;">Settings</h4>
				<form id="changepass-form">
				    <fieldset>
				        <legend>Change Password</legend>
    					<div class="form-group">
    						<label for="currentpass">Current Password</label>
    						<input type="password" class="form-control" id="currentpass" placeholder="Enter Current Password" required>
    					</div>
    					<div class="form-group mt-3">
    						<label for="newpass">New Password</label>
    						<input type="password" class="form-control" id="newpass" placeholder="Enter New Password" required>
    					</div>
    					<div class="form-group mt-3">
    						<label for="retypenewpass">Re-type New Password</label>
    						<input type="password" class="form-control" id="retypenewpass" placeholder="Enter Re-type New Password" required>
    					</div>
    					<div class="d-grid gap-2 mt-3">
    						<button type="submit" class="btn btn-primary" id="save-btn">Save</button>
    					</div>
					</fieldset>
				</form>
			</div>
		</div>	
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#changepass-form").submit(function(event) {
			event.preventDefault();
			changePass();
    	}); 
    });
    function changePass(){
    	var currentpass = $('#currentpass').val();
    	var newpass = $('#newpass').val();
    	var retypenewpass = $('#retypenewpass').val();
    	var btn = $('#save-btn');
    
    	btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    	btn.prop('disabled', true);
    
    	$.ajax({
    		url: "./admin_api.php", // Replace with the correct URL or file path
    		type: "POST",
          	dataType: 'json',
    		data: {
    			tag: "changePass",
    			currentpass: currentpass,
    			newpass: newpass,
    			retypenewpass: retypenewpass
    		},
    		success: function(response) {
    		    if(response['status'] == 200) {
    			    Swal.fire(response['message'], '', 'success');
    			    $('#currentpass').val("");
    			    $('#newpass').val("");
    			    $('#retypenewpass').val("");
    		    } else {
    		        Swal.fire(response['message'], '', 'error')
    		    }
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
</script>