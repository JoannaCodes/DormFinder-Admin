<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>StudyHive | Forgot Password</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" type="image/png" href="./images/logo.png" />
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    <body style="padding-top:50px;">
        <div class="container">
        	<div class="row">
        		<div class="col-sm-4 mx-auto">
        		    <img src="./images/logo.png" class="mb-3" style="width: 100px;margin:0 auto;display:block;"/>
        		    <div class="card bg-light mb-3">
        		        <div class="card-header">Change Password</div>
        		        <div class="card-body">
        		            <form id="forgotPass">
                		        <label>New Password</label>
                                <div class="form-group"> 
                                    <input type="password" id="pass" value="" class="form-control" placeholder="New Password" required=""> 
                                </div> 
                		        <label>Confirm Password</label>
                                <div class="form-group"> 
                                    <input type="password" id="confirm_pass" value="" class="form-control" placeholder="Confirm Password" required=""> 
                                </div>
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </form>
                        </div>
                    </div>
        		</div>  
        	</div>
        </div>
        <script type="text/javascript">
            $('#forgotPass').submit(function(e) {
                e.preventDefault();
                
                var pass = $('#pass').val();
                var confirm_pass = $('#confirm_pass').val();
                
                if(pass === confirm_pass) {
                    $.ajax({
                		url: "./index.php?forgotpass=<?=$_GET['forgotpass']; ?>",
                		type: "POST",
                      	dataType: 'json',
                		data: {
                			pass: pass,
                			forgotpass: "<?=$_GET['forgotpass'];?>",
                			submit: '1'
                		},
                		success:function(response) {
                		    
                		    alert("Successfully!");
                		    window.location.replace("https://studyhive.social");
                		    return false;
                		},
                		error: function(xhr, status, error) {
                			alert("An error occurred: " + error);
                		},
                	})
                } else {
                    alert("Password and Confirm Password doesn\'t match!");
                    return false;
                }
            });
        </script>
    </body>
</html>

