<div class="container mt-4 mx-auto ps-5 pe-5 mt-5">
    <div class="row">
    	<div class="col-12">
    		<div class="rounded shadow p-3 mb-5">
    			<h4 style="font-weight:bold;margin-bottom: 20px;">Payment Transaction History</h4>
				<div style="overflow:scroll;">
					<table data-page-length='5' class="table table-striped table-bordered table-responsive" id="paymentTransactionHistoryTable">
						<thead>
							<tr>
								<th scope="col">Transaction #</th>
								<th scope="col">Token</th>
								<th scope="col">Customer</th>
								<th scope="col">Tenant</th>
								<th scope="col">Amount</th>
								<th scope="col">Date created</th>
							</tr>
						</thead>
						<tbody id="payment_transaction_history">
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
    			tag: "get_payment_transaction_history"
    		},
    		complete:function(response) {
    			$('#payment_transaction_history').html(response.responseText);
    			$('#paymentTransactionHistoryTable').DataTable();
    		}
    	})
    }
</script>