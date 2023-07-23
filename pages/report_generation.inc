<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<link rel="stylesheet" href="https://bootswatch.com/5/cosmo/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/gh/duyplus/fontawesome-pro/css/all.min.css" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
<link href="./css/admin.css" rel="stylesheet" type="text/css" />

<div class="container mt-4 mx-auto ps-5 pe-5 mt-5" id="print">
    <div class="row">
        <div class="col-lg-12">
            <h1 style="text-align:center;margin-top: 30px;">Study Hive Report Generation</h1>
        </div>
    </div>
    <div class="row">
	    <div class="col-xl-4 col-lg-6">
          <div class="card card-stats mb-4 mb-xl-0 mt-3">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0" style="font-size: 13px;">Verified User(s)</h5>
                  <span class="h2 font-weight-bold mb-0" id="verified-count1">0</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-danger text-white rounded-circle shadow" style="padding: 5px 10px;background:#0E898B!important;">
                    <i class="fa-solid fa-user"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-lg-6">
          <div class="card card-stats mb-4 mb-xl-0 mt-3">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0" style="font-size: 13px;">Unverified User(s)</h5>
                  <span class="h2 font-weight-bold mb-0" id="notverified-count1">0</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-danger text-white rounded-circle shadow" style="padding: 5px 10px;background:#0E898B!important;">
                    <i class="fa-solid fa-user"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-lg-6">
          <div class="card card-stats mb-4 mb-xl-0 mt-3">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0" style="font-size: 13px;">Dorm(s)</h5>
                  <span class="h2 font-weight-bold mb-0" id="dorms-count1">0</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-danger text-white rounded-circle shadow" style="padding: 7px 10px;background:#0E898B!important;">
                    <i class="fa-solid fa-house"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-lg-6">
          <div class="card card-stats mb-4 mb-xl-0 mt-3">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0" style="font-size: 13px;">Report(s)</h5>
                  <span class="h2 font-weight-bold mb-0" id="reports-count1">0</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-danger text-white rounded-circle shadow" style="padding: 7px 12px;background:#0E898B!important;">
                    <i class="fa-solid fa-flag"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-lg-6">
          <div class="card card-stats mb-4 mb-xl-0 mt-3">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0" style="font-size: 13px;">Online User(s)</h5>
                  <span class="h2 font-weight-bold mb-0" id="online-count1">0</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-danger text-white rounded-circle shadow" style="padding: 7px 12px;background:#0E898B!important;">
                    <i class="fa-solid fa-user"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-lg-6">
          <div class="card card-stats mb-4 mb-xl-0 mt-3">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0" style="font-size: 13px;">Admin User(s)</h5>
                  <span class="h2 font-weight-bold mb-0" id="admins-count1">0</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-danger text-white rounded-circle shadow" style="padding: 7px 12px;background:#0E898B!important;">
                    <i class="fa-solid fa-lock"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="row">
	    <div class="col-lg-6">
	        <div class="card py-3 px-3 mt-3">
	            <h4 style="font-weight:bold;">Chart for Registered Users</h4>
	            <div style="height: 250px;">
	                <canvas id="examChart1"></canvas>
	            </div>
	        </div>
	        
	    </div>
	    <div class="col-lg-6">
	        <div class="card py-3 px-3 mt-3">
	            <h4 style="font-weight:bold;">Chart for Transaction(s)</h4>
	            <div style="height: 250px;">
	                <canvas id="examChart21"></canvas>
	            </div>
	        </div>
	        
	    </div>
	    
    </div>
    <div class="row">
	    <div class="col-lg-12">
	        <div class="card py-3 px-3 mt-3">
	            <h4 style="font-weight:bold;">Top review(s)</h4>
	            <div id="top_dorms1">
	            </div>
	        </div>
	        
	    </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
	        <div class="card py-3 px-3 mt-3">
	            <h4 style="font-weight:bold;">Dorm Listing(s)</h4>
                <table class="table table-striped table-bordered table-responsive" id="dormsTable1">
					<thead style="background-color: white;">
						<tr>
							<th>Dorm ID</th>
							<th>Username</th>
							<th>Dorm Name</th>
							<th>Address</th>
							<th>Date Created</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody id="dorms1">
					</tbody>
				</table>
	        </div>
	    </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script type="text/javascript">
    var user_registered_date1 = [];
    var user_registered1 = [];

	$(document).ready(function() {
    	get_statistics1();
    	get_user_statistics1();
    	get_top_reviews_in_dorm1();
    	get_transaction_statistics1();
    	get_report_dormlisting1();
    	
	});
	
    function get_report_dormlisting1() {
    	$.ajax({
    		url: "./admin_api.php",
    		type: "GET",
    		data: {
    			//_token: "{{ csrf_token() }}",
    			tag: "get_report_dormlisting"
    		},
    		complete:function(response) {
    			$('#dorms1').html(response.responseText);
    		}
    	})
    }

    function get_statistics1() {
    	$.ajax({
    		url: "./admin_api.php",
    		type: "POST",
          	dataType: 'json',
    		data: {
    			//_token: "{{ csrf_token() }}",
    			tag: "statistics",
    		},
    		success:function(response) {
    			
    			$("#verified-count1").text(response.verified);
    			$("#dorms-count1").text(response.dorms);
    			$("#reports-count1").text(response.reports);
    			$("#online-count1").text(response.online);
    			$("#admins-count1").text(response.admins);
    			$("#notverified-count1").text(response.not_verified);
    		},
    		error: function(xhr, status, error) {
    			alert("An error occurred: " + error);
    		},
    	})
    }
    
    function get_user_statistics1() {
    	$.ajax({
    		url: "./admin_api.php",
    		type: "POST",
          	dataType: 'json',
    		data: {
    			//_token: "{{ csrf_token() }}",
    			tag: "get_user_statistics",
    		},
    		success:function(response) {
    		    var newData1 = [];
    		    var newData21 = [];
    		    response.user_statistics.map((item) => {
    		        newData1.push(moment(item.created_at).format('MM/DD/YYYY'));
    		    })
    		    response.user_statistics2.map((item) => {
    		        newData21.push({
    		            t: moment(item.t).format('MM/DD/YYYY'),
    		            y: item.y
    		        });
    		    })
    		    user_registered_date1 = newData1;
    			user_registered1 = newData21;
    			chartNow1();
    		},
    		error: function(xhr, status, error) {
    			alert("An error occurred: " + error);
    		},
    	})
    }
    
    function get_transaction_statistics1() {
    	$.ajax({
    		url: "./admin_api.php",
    		type: "POST",
          	dataType: 'json',
    		data: {
    			//_token: "{{ csrf_token() }}",
    			tag: "get_transaction_statistics",
    		},
    		success:function(response) {
    		    var newData1 = [];
    		    var newData21 = [];
    		    response.transactions_statistics.map((item) => {
    		        newData1.push(moment(item.created_at).format('MM/DD/YYYY'));
    		    })
    		    response.transactions_statistics2.map((item) => {
    		        newData21.push({
    		            t: moment(item.t).format('MM/DD/YYYY'),
    		            y: item.y
    		        });
    		    })
    		    transactions_date1 = newData1;
    			transactions1 = newData21;
    			chartNow21();
    		},
    		error: function(xhr, status, error) {
    			alert("An error occurred: " + error);
    		},
    	})
    }
    
    function chartNow1() {
        var ctx = document.getElementById("examChart1").getContext("2d");

        var myChart = new Chart(ctx, {
            type: 'line',
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                plugins: {
                    legend: false // Hide legend
                }
            },
            data: {
                labels: user_registered_date1,
                datasets: [{
                    label: 'Registered User(s)',
                    data: user_registered1,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });
    }
    
    function chartNow21() {
        var ctx = document.getElementById("examChart21").getContext("2d");

        var myChart = new Chart(ctx, {
            type: 'line',
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                plugins: {
                    legend: false // Hide legend
                }
            },
            data: {
                labels: transactions_date1,
                datasets: [{
                    label: 'Transaction(s)',
                    data: transactions1,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });
    }
    
    function get_top_reviews_in_dorm1() {
    	$.ajax({
    		url: "./admin_api.php",
    		type: "POST",
          	dataType: 'json',
    		data: {
    			//_token: "{{ csrf_token() }}",
    			tag: "top_reviews_in_dorm",
    		},
    		success:function(response) {
    		    response.dorms.map((item) => {
    		        const hasHalfStar = parseFloat(item.average_rating) % 1 !== 0; 
    		        
    		        var x = `<div class="topreviews-info">
    		            <b>${item.name}</b><br/>`;
    		            for(var i = 1; i <= 5; i++) {
    		                if(i <= parseInt(item.average_rating)) {
    		                    x = x + '<span class="fa-solid fa-star-sharp" style="color:orange;"></span>';
    		                } else {
    		                    x = x + '<span class="fa-regular fa-star-sharp"></span>';
    		                }
    		            }
    		            x = x + `<span style="margin-left:5px;font-weight:bold;">${parseFloat(item.average_rating).toFixed(2)}</span>
    		        </div>`;
    		        $('#top_dorms1').prepend(x);
    		    })
    		},
    		error: function(xhr, status, error) {
    			alert("An error occurred: " + error);
    		},
    	})
    }
</script>
