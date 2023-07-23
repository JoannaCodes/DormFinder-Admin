<div class="container mt-4 mx-auto ps-5 pe-5 mt-5">
    <div class="row">
        <div class="col-12">
        	<div class="rounded shadow p-3 mb-5">
        		<h4 style="margin-bottom: 20px;font-weight:bold;">User(s)</h4>
        		<div style="overflow:scroll;">
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
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
    	get_users();
    });
    
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
</script>