<div class="container mt-4 mx-auto ps-5 pe-5 mt-5">
    <div class="row">
        <div class="col-12">
			<div class="rounded shadow p-3 mb-5">
				<h4 style="font-weight:bold;margin-bottom:20px;">Document Verifier</h4>
				<div style="overflow: scroll;">
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

<script type="text/javascript">
    $(document).ready(function() {
    	get_userdoc();
    });
    
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
</script>
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