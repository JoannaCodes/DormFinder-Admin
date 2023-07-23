<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>StudyHive | Verify Email</title>
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
        		        <div class="card-header">Verify Email Address</div>
        		        <div class="card-body">
        		            <form id="verify">
                		        <label>Verify your email address</label>
                		        <p>To complete registration and use StudyHive, you'll need to verify your email address.</p>
                                <button type="submit" class="btn btn-primary btn-block">Verify</button>
                            </form>
                        </div>
                    </div>
        		</div>  
        	</div>
        </div>
        <script type="text/javascript">
            $('#verify').submit(function(e) {
                e.preventDefault();
                
                $.ajax({
            		url: "./index.php?email_verification=<?=$_GET['email_verification']; ?>",
            		type: "POST",
                  	dataType: 'json',
            		data: {
            			verifykey: "<?=$_GET['email_verification'];?>",
            			submit: '1'
            		},
            		success:function(response) {
            		    alert("Successful! Please go to StudyHive and login");
            		    window.location.replace("https://studyhive.social");
            		    return false;
            		},
            		error: function(xhr, status, error) {
            			alert("An error occurred: " + error);
            		},
            	})
            });
        </script>
    </body>
</html>
