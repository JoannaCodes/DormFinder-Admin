<div class="container mt-4 mx-auto ps-5 pe-5 mt-5">
    <div class="row">
        <div class="col-12">
			<div class="rounded shadow p-3 mb-5">
				<h4 style="font-weight:bold;margin-bottom: 20px;">Notification Form</h4>
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
		</div>	
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#notif-form").submit(function(event) {
			event.preventDefault();
			send_custom_notif();
    	}); 
    });
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
</script>