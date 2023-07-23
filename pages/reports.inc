<div class="container mt-4 mx-auto ps-5 pe-5 mt-5">
    <div class="row">
    	<div class="col-12">
    		<div class="rounded shadow p-3 mb-5">
    			<h4 style="font-weight:bold;margin-bottom: 20px;">Report(s)</h4>
				<div style="overflow:scroll;">
					<table data-page-length='5' class="table table-striped table-bordered table-responsive" id="reportsTable">
						<thead>
							<tr>
								<th scope="col">Report ID</th>
								<th scope="col">User</th>
								<th scope="col">Dorm Reported</th>
								<th scope="col">Message</th>
								<th scope="col">Date Created</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody id="reports">
						</tbody>
					</table>
				</div>
			</div>
    	</div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
    	get_reports();
    });
    
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
    		    setTimeout(() => {
    		        location.reload();
    		    }, 3000)
    			
    		}
    	})
    }
</script>