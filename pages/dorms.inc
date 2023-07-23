<div class="container mt-4 mx-auto ps-5 pe-5 mt-5">
    <div class="row">
		<div class="col-12">
			<div class="rounded shadow p-3 mb-5">
				<h4 style="font-weight:bold;margin-bottom: 20px;">Student Accommodation Listing(s)</h4>
				<div class="mb-3 float-right" id="datatableButtons">
				    <button type="button" class="btn btn-outline-primary" id="selectAllButton">Select All</button>
				    <button type="button" class="btn btn-outline-danger" onclick='delete_dorm_admin(this)'>Delete</button>
				    <button type="button" class="btn btn-outline-info" onclick='showhide_dorm_admin(this)'>Hide/Unhide</button>
				    <button type="button" class="btn btn-outline-success" onclick='send_dorm_notif(this)'>Send Update Notification</button>
				</div>
				<div style="overflow: scroll;">
					<table class="table table-striped table-bordered table-responsive" id="dormsTable">
						<thead style="background-color: white;">
							<tr>
							    <th style="width:97px;">Select</th>
								<th>Dorm ID</th>
								<th>User ID</th>
								<th>Name</th>
								<th>Address</th>
								<th>Date Created</th>
								<th>Date Updated</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody id="dorms">
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="rounded shadow p-3 mb-5">
				<h4 style="font-weight:bold;margin-bottom: 20px;">Due Date Listing(s)</h4>
				<div style="overflow: scroll;">
					<table class="table table-striped table-bordered table-responsive" id="duedormsTable">
						<thead style="background-color: white;">
							<tr>
								<th>Dorm ID</th>
								<th>User ID</th>
								<th>Name</th>
								<th>Address</th>
								<th>Paid Date</th>
								<th>End Date</th>
							</tr>
						</thead>
						<tbody id="due_date_dorms">
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
    	get_duedatelisting();
    });
    
    // const alertButton = document.getElementById('alertButton');
    // alertButton.addEventListener('click', function() {
    //     const checkboxes = document.querySelectorAll('.form-check-input');
    //     const selectedCheckboxes = [];
    
    //     checkboxes.forEach(function(checkbox) {
    //         if (checkbox.checked) {
    //           const dormRef = checkbox.dataset.dormref;
    //           const userRef = checkbox.dataset.userref;
    //           selectedCheckboxes.push({ userref: userRef, dormref: dormRef });
    //         }
    //     });
    
    //     if (selectedCheckboxes.length > 0) {
    //       alert('Selected checkboxes: ' + selectedCheckboxes.join(', '));
    //     } else {
    //       alert('No checkboxes selected.');
    //     }
    // });
    
    document.getElementById('selectAllButton').addEventListener('click', toggleCheckboxes);
    
    function toggleCheckboxes() {
        var table = $('#dormsTable').DataTable();
        var button = document.getElementById('selectAllButton');
        var newState;
    
        table.rows().every(function() {
            var rowNode = this.node();
            var checkbox = rowNode.querySelector('.form-check-input');
            checkbox.checked = !checkbox.checked; // Toggle the checkbox state
        });
    
        // Determine the new state for the button
        if (button.getAttribute('data-state') === 'selected') {
            newState = 'unselected';
            button.innerText = 'Select All';
        } else {
            newState = 'selected';
            button.innerText = 'Unselect All';
        }
    
        // Update the data-state attribute with the new state
        button.setAttribute('data-state', newState);
    }
    
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
    			$('#dormsTable').DataTable({
    			    "paging": true,
                    "pageLength": 5,
    			});
    		}
    	})
    }
    
    function get_duedatelisting() {
    	$.ajax({
    		url: "./admin_api.php",
    		type: "GET",
    		data: {
    			//_token: "{{ csrf_token() }}",
    			tag: "get_duedatelisting"
    		},
    		complete:function(response) {
    			$('#due_date_dorms').html(response.responseText);
    			$('#duedormsTable').DataTable({
    			    "paging": true,
                    "pageLength": 5,
    			});
    		}
    	})
    }
    
    function send_dorm_notif(obj) {
        var table = $('#dormsTable').DataTable();
        var selectedItems = [];
        
        table.rows().nodes().to$().find('.form-check-input:checked').each(function() {
            var row = $(this).closest('tr');
            var userref = row.find('[data-userref]').data('userref');
            var dormref = row.find('[data-dormref]').data('dormref');
            var value = this.value;
            selectedItems.push({ userref: userref, dormref: dormref, value: value });
        });
    
    	if (selectedItems.length > 0) {
            // Generate summary of selected items
            var summary = 'Selected items:\n';
              selectedItems.forEach(function(item) {
                summary += item.value + ',\n';
            });
            
            Swal.fire({
                title: 'Are you sure you want to proceed?',
                text: summary,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
            }).then(function(result) {
                if (result.isConfirmed) {
                  $.ajax({
                    url: './admin_api.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                      tag: 'send_dorm_notif',
                      items: selectedItems,
                    },
                    success: function(response) {
                      Swal.fire(response['message'], '', 'success');
                    },
                    error: function(xhr, status, error) {
                      alert('An error occurred: ' + error);
                    },
                  });
                } else {
                  Swal.fire('Action canceled', '', 'info')
                }
            });
        } else {
            alert('No checkboxes selected.');
        }
    }
    
    function delete_dorm_admin(obj) {
    	var table = $('#dormsTable').DataTable();
        var selectedItems = [];
        
        table.rows().nodes().to$().find('.form-check-input:checked').each(function() {
            var row = $(this).closest('tr');
            var userref = row.find('[data-userref]').data('userref');
            var dormref = row.find('[data-dormref]').data('dormref');
            var value = this.value;
            selectedItems.push({ userref: userref, dormref: dormref, value: value });
        });
        
        if (selectedItems.length > 0) {
            // Generate summary of selected items
            var summary = 'Selected items:\n';
              selectedItems.forEach(function(item) {
                summary += item.value + ',\n';
            });
            
            Swal.fire({
        		title: 'Continue deleting this dorm listing(s)',
        		text: summary,
        		icon: 'question',
        		showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
        	}).then((result) => {
        		if (result.isConfirmed) {
        			$.ajax({
        				url: "./admin_api.php",
        				type: "POST",
        				data: {
        					tag: "delete_dorm_admin",
        					items: selectedItems,
        				},
        				success:function(response) {
        					SSwal.fire(response['message'], '', 'success')
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
        } else {
            alert('No checkboxes selected.');
        }
    }
    
    function showhide_dorm_admin(obj) {
    	var table = $('#dormsTable').DataTable();
        var selectedItems = [];
        
        table.rows().nodes().to$().find('.form-check-input:checked').each(function() {
            var row = $(this).closest('tr');
            var userref = row.find('[data-userref]').data('userref');
            var dormref = row.find('[data-dormref]').data('dormref');
            var value = this.value;
            selectedItems.push({ userref: userref, dormref: dormref, value: value });
        });
        
        if (selectedItems.length > 0) {
            // Generate summary of selected items
            var summary = 'Selected items:\n';
              selectedItems.forEach(function(item) {
                summary += item.value + ',\n';
            });
            
            Swal.fire({
        		title: 'Continue action',
        		text: summary,
        		icon: 'question',
        		showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
        	}).then((result) => {
        		if (result.isConfirmed) {
        		    $.ajax({
                		url: "./admin_api.php",
                		type: "POST",
                      	dataType: 'json',
                		data: {
                			tag: "showhide_dorm_admin",
                			items: selectedItems,
                		},
                		success:function(response) {
                			Swal.fire(response['message'], '', 'success')
                			get_dormlisting();
                		},
                		error: function(xhr, status, error) {
                			alert("An error occurred: " + error);
                		},
                		complete: function() {
            				location.reload();
            			}
                	})
        		} else if (result.isDenied) {
        			Swal.fire('Deletion canceled', '', 'info')
        		}
        	})
        } else {
            alert('No checkboxes selected.');
        }
    }
</script>