<div class="container mt-4 mx-auto ps-5 pe-5 mt-5" id="dashboard">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-primary" role="alert">Hello, <b><?=$_SESSION['name'];?></b>! Welcome to <b>StudyHive - Admin Dashboard</b>!</div>
            <br>
            <button id="submit" class="btn btn-success" id="reportbtn">Report PDF</button>
        </div>
    </div>
    <div class="row">
	    <div class="col-xl-4 col-lg-6">
          <div class="card card-stats mb-4 mb-xl-0 mt-3">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0" style="font-size: 13px;">Verified User(s)</h5>
                  <span class="h2 font-weight-bold mb-0" id="verified-count">0</span>
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
                  <span class="h2 font-weight-bold mb-0" id="notverified-count">0</span>
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
                  <span class="h2 font-weight-bold mb-0" id="dorms-count">0</span>
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
                  <span class="h2 font-weight-bold mb-0" id="reports-count">0</span>
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
                  <span class="h2 font-weight-bold mb-0" id="online-count">0</span>
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
                  <span class="h2 font-weight-bold mb-0" id="admins-count">0</span>
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
	                <canvas id="examChart" style="display: block; height: 250px; width: 566px;"></canvas>
	            </div>
	        </div>
	        
	    </div>
	    <div class="col-lg-6">
	        <div class="card py-3 px-3 mt-3">
	            <h4 style="font-weight:bold;">Chart for Transaction(s)</h4>
	            <div style="height: 250px;">
	                <canvas id="examChart2" style="display: block; height: 250px; width: 566px;"></canvas>
	            </div>
	        </div>
	        
	    </div>
	    
    </div>
    <div class="row">
	    <div class="col-lg-12">
	        <div class="card py-3 px-3 mt-3">
	            <h4 style="font-weight:bold;">Top review(s)</h4>
	            <div style="height: 250px;overflow-y:scroll;" id="top_dorms">
	            </div>
	        </div>
	        
	    </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script type="text/javascript">
    var user_registered_date = [];
    var user_registered = [];

	$(document).ready(function() {
    	get_statistics();
    	get_user_statistics();
    	get_top_reviews_in_dorm();
    	get_transaction_statistics();
    	
	});
	
	const autoType = (param) => {
        let el = document.querySelector(param.el);
        let speed = param.speed;
        let max_number = param.max_number; 
        
        [...Array(max_number+1).keys()].map((i) => {
            setTimeout(() => {
                el.textContent = i;
            }, speed * i);
        });
    };

    function get_statistics() {
    	$.ajax({
    		url: "./admin_api.php",
    		type: "POST",
          	dataType: 'json',
    		data: {
    			//_token: "{{ csrf_token() }}",
    			tag: "statistics",
    		},
    		success:function(response) {
    			
    			autoType({ el: "#verified-count", speed: 100, max_number: response.verified });
    			autoType({ el: "#dorms-count", speed: 100, max_number: response.dorms });
    			autoType({ el: "#reports-count", speed: 100, max_number: response.reports });
    			autoType({ el: "#online-count", speed: 100, max_number: response.online });
    			autoType({ el: "#admins-count", speed: 100, max_number: response.admins });
    			autoType({ el: "#notverified-count", speed: 100, max_number: response.not_verified });
    		},
    		error: function(xhr, status, error) {
    			alert("An error occurred: " + error);
    		},
    	})
    }
    
    function get_user_statistics() {
    	$.ajax({
    		url: "./admin_api.php",
    		type: "POST",
          	dataType: 'json',
    		data: {
    			//_token: "{{ csrf_token() }}",
    			tag: "get_user_statistics",
    		},
    		success:function(response) {
    		    var newData = [];
    		    var newData2 = [];
    		    response.user_statistics.map((item) => {
    		        newData.push(moment(item.created_at).format('MM/DD/YYYY'));
    		    })
    		    response.user_statistics2.map((item) => {
    		        newData2.push({
    		            t: moment(item.t).format('MM/DD/YYYY'),
    		            y: item.y
    		        });
    		    })
    		    user_registered_date = newData;
    			user_registered = newData2;
    			chartNow();
    		},
    		error: function(xhr, status, error) {
    			alert("An error occurred: " + error);
    		},
    	})
    }
    
    function get_transaction_statistics() {
    	$.ajax({
    		url: "./admin_api.php",
    		type: "POST",
          	dataType: 'json',
    		data: {
    			//_token: "{{ csrf_token() }}",
    			tag: "get_transaction_statistics",
    		},
    		success:function(response) {
    		    var newData = [];
    		    var newData2 = [];
    		    response.transactions_statistics.map((item) => {
    		        newData.push(moment(item.created_at).format('MM/DD/YYYY'));
    		    })
    		    response.transactions_statistics2.map((item) => {
    		        newData2.push({
    		            t: moment(item.t).format('MM/DD/YYYY'),
    		            y: item.y
    		        });
    		    })
    		    transactions_date = newData;
    			transactions = newData2;
    			chartNow2();
    		},
    		error: function(xhr, status, error) {
    			alert("An error occurred: " + error);
    		},
    	})
    }
    
    function chartNow() {
        var ctx = document.getElementById("examChart").getContext("2d");

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
                labels: user_registered_date,
                datasets: [{
                    label: 'Registered User(s)',
                    data: user_registered,
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
    
    function chartNow2() {
        var ctx = document.getElementById("examChart2").getContext("2d");

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
                labels: transactions_date,
                datasets: [{
                    label: 'Transaction(s)',
                    data: transactions,
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
    
    function get_top_reviews_in_dorm() {
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
    		        $('#top_dorms').prepend(x);
    		    })
    		},
    		error: function(xhr, status, error) {
    			alert("An error occurred: " + error);
    		},
    	})
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>
<div class="modal" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false" style="background: #0000006e;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Successfully!</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        Please wait to download the pdf
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    var count = 0;
    $(document).ready(function() {
        $('#dashboard').css('display','block');
       $("#print").css("display", 'none'); 
    });
    $('#submit').click(function () { 
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            alert("Error! You must use desktop version!");
            return false;
        }
        $('#myModal').modal({backdrop: 'static', keyboard: false}, 'show').show();


        save();
        
        setTimeout(() => {
            $("#print").css("display", 'none');
            $('#dashboard').css('display','block');
            save();
        },5000)
        
        
    });
    function save() {
        var w = document.getElementById("print").offsetWidth;
        var h = document.getElementById("print").offsetHeight;
  
        const element = document.getElementById('print');
        $('#dashboard').css('display','none');
        $("#print").css("display", 'block'); 
        html2canvas(element).then((canvas) => {
            const pdf = new jsPDF("p", "mm", "a4");
            pdf.addImage(canvas.toDataURL("image/jpeg"), "JPEG", 0, 0, 210, 240);
            if(count != 0) {
                pdf.save("studyhive.report-generation.pdf");
                $("#print").css("display", 'none');
                $('#dashboard').css('display','block');
                $('#myModal').modal({backdrop: 'static', keyboard: false}, 'show').hide();
            }
            count++;
            
        });
    }
</script>