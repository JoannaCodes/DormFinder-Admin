<?php
function _validate($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);

	return $data;
}

function authenticate($authKey)
{
		$validKey = '%n91qDOmDz(8fAQP';
		
		if ($authKey === $validKey) {
				return true;
		} else {
				return false;
		}
}

function sdmq()
{
	$c = new connection();
	$c = $c->sdm_connect();
	$sdm_q = new sdm_query($c);
	return $sdm_q;
}


$authKey = $_SERVER['HTTP_AUTH_KEY'] ?? '';

if (authenticate($authKey)) {
    
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods:POST");
    
    include_once "inc/conn.php";
    include_once "mod/queries.php";
    include_once "functions.php";
    
    $domain = 'https://studyhive.social/';
    $tag = '';
    
    if (isset($_POST["tag"])) {
    	//POST
    	$tag = $_POST["tag"];
    } else if (isset($_GET["tag"])) {
    	//GET
    	$tag = $_GET["tag"];
    }

	$action = _validate($_POST['tag'] ?? $_GET['tag']);

	if($action !== NULL) {
		switch ($tag) {
			case 'login_app':
				// echo sdmq()->login_app(_validate($_POST["username"]), _validate($_POST["password"]));
				echo sdmq()->login_app(_validate($_POST["username"]), _validate($_POST["password"]), _validate($_POST["fcm"]));
				break;
			case 'login_app_guest':
				echo json_encode(["username" => 'guest', "id" => uniqid(), "status" => true, "mode" => "guest"]);
				break;
			case 'signup_app':
				echo sdmq()->signup_app(_validate($_POST["email"]), _validate($_POST["username"]), _validate($_POST["password"]));
				break;	
			case 'logout_app':
			    echo sdmq()->logout_app(_validate($_POST['userref']));
			    break;
			case 'fetch_saved_notif':
				echo sdmq()->look_usersavednotifs(_validate($_GET['user_ref']));
				break;
			case 'getnotificationspast':
			    echo sdmq()->look_passednotifications(_validate($_GET['userref']));
			    break;
			case 'check_ifsubmitted':
				echo sdmq()->check_ifsubmitted(_validate($_GET["user_id"]));
				break;
			case 'clearallnotif':
				echo sdmq()->clearallnotif(_validate($_GET["userref"]));
				break;
			case 'send_document':
				$target_dir = "uploads/userDocs/" . $_POST['user_id'];
				$target_file = $target_dir . "/" . basename($_FILES["document1"]["name"]);
				$target_file2 = $target_dir . "/" . basename($_FILES["document2"]["name"]);
				$filename1 = basename($_FILES["document1"]["name"]);
				$filename2 = basename($_FILES["document2"]["name"]);
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
		
				// Check if user folder exists, create it if it doesn't
				if (!file_exists($target_dir)) {
					mkdir($target_dir, 0777, true);
				}
		
				// Check if file already exists
				if (file_exists($target_file)) {
					echo "Sorry, file already exists.";
					$uploadOk = 0;
				}
		
				// Check file size
				if ($_FILES["document1"]["size"] > 5000000) {
					echo "Sorry, your file is too large.";
					$uploadOk = 0;
				}
		
				// Allow certain file formats
				if ($imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx") {
					echo "Sorry, only PDF, DOC, and DOCX files are allowed.";
					$uploadOk = 0;
				}
		
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
					echo "Sorry, your file was not uploaded.";
				} else {
					$user_id = $_POST['user_id'];
					$out = sdmq()->send_document($filename1, $filename2, $user_id);
					if ($out == "1") {
						if (move_uploaded_file($_FILES["document1"]["tmp_name"], $target_file) && move_uploaded_file($_FILES["document2"]["tmp_name"], $target_file2)) {
							echo "The file " . htmlspecialchars(basename($_FILES["document1"]["name"])) . " and " . htmlspecialchars(basename($_FILES["document2"]["name"])) . " has been uploaded. We will notify you once you are verified thank you.";
						} else {
							echo "Sorry, there was an error uploading your file.";
						}
					} else {
						echo "Query failed.";
					}
				}
				break;
			case "get_account":
				echo sdmq()->get_account(_validate($_GET["userref"]));
				break;
			case "update_account":
				$out = sdmq()->update_account(_validate($_POST["userref"]), _validate($_POST["identifier"]));
				if ($out == "1") {
					echo 'success';
				} else {
					echo 'failed';
				}
				break;
			case 'delete_account':
			    $userref = _validate($_POST["userref"]);
			    $dormImagesPath = 'uploads/dormImages/' . $dormref . '/';
			    $userImagesPath = 'uploads/userImages/' . $userref . '/';
			    $userDocsPath = 'uploads/userDocs/' . $userref . '/';
		
				if (is_dir($userImagesPath) || is_dir($userDocsPath)) {
					if (deleteDirectory($userImagesPath) || deleteDirectory($userDocsPath)) {
						$out = sdmq()->delete_account($userref);
						if ($out == "1") {
							echo 'success';
						}
					} else {
						echo 'failed.';
					}
				} else {
					echo 'Folder does not exist.';
				}
				break;
			case 'change_password':
				$currentpassword = _validate($_POST['currentpassword']);
				$newpassword = _validate($_POST['newpassword']);
				$userref = _validate($_POST['userref']);
		
				$out = sdmq()->change_password($userref, $currentpassword, $newpassword);
				echo $out;
				break;
			case 'get_dorms':
				echo sdmq()->get_dorms(_validate($_GET["userref"]));
				break;
			case 'get_dorm_details':
				echo sdmq()->get_dorm_details(_validate($_GET["dormref"]));
				break;
			case 'get_bookmarks':
				echo sdmq()->get_bookmarks(_validate($_GET["userref"]));
				break;
			case "update_profile":
				$image = $_FILES['image'];
				$userref = _validate($_POST["userref"]);
				$username = _validate($_POST["username"]);
		
				$uploadDir = 'uploads/userImages/' . $userref . '/';
				$uploadFile = $uploadDir . basename($image['name']);
				$fileName = $uploadDir . basename($image['name']);
		
				// Check if the folder already exists
				if (!is_dir($uploadDir)) {
					mkdir($uploadDir, 0777, true);
				}
		
				$out = sdmq()->update_profile($userref, $username, $domain . $fileName);
				if (move_uploaded_file($image['tmp_name'], $uploadFile) || $out == "1") {
					echo 'success';
				} else {
					echo 'failed';
				}
				break;
			case 'post_review':
				$out = sdmq()->post_review(_validate($_POST["dormref"]), _validate($_POST["userref"]), _validate($_POST["rating"]), _validate($_POST["comment"]));
				if ($out == "1") {
					echo 'success';
				} else {
					echo 'failed';
				}
				break;
			case 'get_reviews':
				echo sdmq()->get_reviews(_validate($_GET["dormref"]) ?? 0);
				break;
			case 'post_report':
				$id = uniqId();
				$out = sdmq()->post_report($id, _validate($_POST["dormref"]), _validate($_POST["userref"]), _validate($_POST["comment"]));
				if ($out == "1") {
					echo 'success';
				} else {
					echo 'failed';
				}
				break;
			case 'delete_dorm':
				$dormref = _validate($_POST["dormref"]);
				$userref = _validate($_POST["userref"]);
				$folderPath = 'uploads/dormImages/' . $dormref . '/';
		
				if (is_dir($folderPath)) {
					if (deleteDirectory($folderPath)) {
						$out = sdmq()->delete_dorm($dormref, $userref);
						if ($out == "1") {
							echo 'success';
						}
					} else {
						echo 'failed.';
					}
				} else {
					echo 'Folder does not exist.';
				}
				break;
			case 'post_dorm':
				$id = uniqid();
				$userref = _validate($_POST["userref"]);
		
				$name = _validate($_POST["name"]);
				$address = _validate($_POST["address"]);
				$price = _validate($_POST["price"]);
				$slots = (int)_validate($_POST["slots"]);
				$desc = _validate($_POST["desc"]);
				$hei = _validate($_POST["hei"]);
				$images = $_FILES["images"];
		
				$visitors = _validate($_POST["visitors"]);
				$pets = _validate($_POST["pets"]);
				$curfew = _validate($_POST["curfew"]);
		
				$aircon = _validate($_POST['aircon']);
				$elevator = _validate($_POST['elevator']);
				$beddings = _validate($_POST['beddings']);
				$kitchen = _validate($_POST['kitchen']);
				$laundry = _validate($_POST['laundry']);
				$lounge = _validate($_POST['lounge']);
				$parking = _validate($_POST['parking']);
				$security = _validate($_POST['security']);
				$study_room = _validate($_POST['study_room']);
				$wifi = _validate($_POST['wifi']);

				$advdep = _validate($_POST["advance_deposit"]);
				$secdep = _validate($_POST["security_deposit"]);
				$util = _validate($_POST["utilities"]);
				$minstay = _validate($_POST["minimum_stay"]);
		
				// Convert to boolean
				$visitors = (int)filter_var($visitors, FILTER_VALIDATE_BOOLEAN);
				$pets = (int)filter_var($pets, FILTER_VALIDATE_BOOLEAN);
				$curfew = (int)filter_var($curfew, FILTER_VALIDATE_BOOLEAN);
		
				$aircon = (int)filter_var($aircon, FILTER_VALIDATE_BOOLEAN);
				$elevator = (int)filter_var($elevator, FILTER_VALIDATE_BOOLEAN);
				$beddings = (int)filter_var($beddings, FILTER_VALIDATE_BOOLEAN);
				$kitchen = (int)filter_var($kitchen, FILTER_VALIDATE_BOOLEAN);
				$laundry = (int)filter_var($laundry, FILTER_VALIDATE_BOOLEAN);
				$lounge = (int)filter_var($lounge, FILTER_VALIDATE_BOOLEAN);
				$parking = (int)filter_var($parking, FILTER_VALIDATE_BOOLEAN);
				$security = (int)filter_var($security, FILTER_VALIDATE_BOOLEAN);
				$study_room = (int)filter_var($study_room, FILTER_VALIDATE_BOOLEAN);
				$wifi = (int)filter_var($wifi, FILTER_VALIDATE_BOOLEAN);
		
				$latitude = 0;
				$longitude = 0;
		
				// Upload images
				$uploadStatus = true;
				$filenames = [];
				$dormImages = '';
		
				// Address Geocoding
				$coordinates = getAddressCoordinates($address);
				if ($coordinates) {
					$latitude = $coordinates['latitude'];
					$longitude = $coordinates['longitude'];
				}
		
				$uploadDir = 'uploads/dormImages/' . $id . '/';
				if (!is_dir($uploadDir)) {
					mkdir($uploadDir, 0777, true);
				}
		
				foreach ($images['tmp_name'] as $index => $tmpName) {
					$imageName = $images['name'][$index];
					$filename = basename($imageName);
					$uploadFile = $uploadDir . $filename;
					
					// Move the file to the destination directory
					if (move_uploaded_file($tmpName, $uploadFile)) {
						array_push($filenames, $filename);
					} else {
						$uploadStatus = false;
						echo 'Failed to upload images';
					}
				}
		
				$dormImages = implode(',', $filenames);
				$out = sdmq()->post_dorm($id, $userref, $name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $dormImages, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay, $aircon, $elevator, $beddings, $kitchen, $laundry, $lounge, $parking, $security, $study_room, $wifi);
				if ($uploadStatus && $out == "1") {
					echo 'success';
				} else {
					echo 'failed';
				}
				break;
			case 'update_dorm':
				$dormref = _validate($_POST["dormref"]);
				$userref = _validate($_POST["userref"]);
		
				$name = _validate($_POST["name"]);
				$address = _validate($_POST["address"]);
				$price = _validate($_POST["price"]);
				$slots = (int)_validate($_POST["slots"]);
				$desc = _validate($_POST["desc"]);
				$hei = _validate($_POST["hei"]);
		
				$visitors = _validate($_POST["visitors"]);
				$pets = _validate($_POST["pets"]);
				$curfew = _validate($_POST["curfew"]);
		
				$aircon = _validate($_POST['aircon']);
				$elevator = _validate($_POST['elevator']);
				$beddings = _validate($_POST['beddings']);
				$kitchen = _validate($_POST['kitchen']);
				$laundry = _validate($_POST['laundry']);
				$lounge = _validate($_POST['lounge']);
				$parking = _validate($_POST['parking']);
				$security = _validate($_POST['security']);
				$study_room = _validate($_POST['study_room']);
				$wifi = _validate($_POST['wifi']);

				$advdep = _validate($_POST["advance_deposit"]);
				$secdep = _validate($_POST["security_deposit"]);
				$util = _validate($_POST["utilities"]);
				$minstay = _validate($_POST["minimum_stay"]);
		
				// Convert to boolean
				$visitors = (int)filter_var($visitors, FILTER_VALIDATE_BOOLEAN);
				$pets = (int)filter_var($pets, FILTER_VALIDATE_BOOLEAN);
				$curfew = (int)filter_var($curfew, FILTER_VALIDATE_BOOLEAN);
		
				$aircon = (int)filter_var($aircon, FILTER_VALIDATE_BOOLEAN);
				$elevator = (int)filter_var($elevator, FILTER_VALIDATE_BOOLEAN);
				$beddings = (int)filter_var($beddings, FILTER_VALIDATE_BOOLEAN);
				$kitchen = (int)filter_var($kitchen, FILTER_VALIDATE_BOOLEAN);
				$laundry = (int)filter_var($laundry, FILTER_VALIDATE_BOOLEAN);
				$lounge = (int)filter_var($lounge, FILTER_VALIDATE_BOOLEAN);
				$parking = (int)filter_var($parking, FILTER_VALIDATE_BOOLEAN);
				$security = (int)filter_var($security, FILTER_VALIDATE_BOOLEAN);
				$study_room = (int)filter_var($study_room, FILTER_VALIDATE_BOOLEAN);
				$wifi = (int)filter_var($wifi, FILTER_VALIDATE_BOOLEAN);
		
				$latitude = 0;
				$longitude = 0;
		
				// Upload images
				$uploadStatus = true;
				$filenames = [];
				$dormImages = '';
		
				// Address Geocoding
				$coordinates = getAddressCoordinates($address);
				if ($coordinates) {
					$latitude = $coordinates['latitude'];
					$longitude = $coordinates['longitude'];
				}
		
				if (isset($_FILES['images'])) {
					$images = $_FILES['images'];
		
					// Remove existing files in the folder
					$uploadDir = 'uploads/dormImages/' . $dormref . '/';
    				if (!is_dir($uploadDir)) {
    					mkdir($uploadDir, 0777, true);
    				} else {
    				    $existingFiles = glob($uploadDir . '*');
    					foreach ($existingFiles as $file) {
    						if (is_file($file)) {
    							unlink($file);
    						}
    					}
    				}
		
					foreach ($images['tmp_name'] as $index => $tmpName) {
						$imageName = $images['name'][$index];
						$filename = basename($imageName);
						$uploadFile = $uploadDir . $filename;
		
						// Move the file to the destination directory
						if (move_uploaded_file($tmpName, $uploadFile)) {
							array_push($filenames, $filename);
						} else {
							$uploadStatus = false;
							echo 'Failed to upload images';
						}
					}
		
					// Compile filenames
					$dormImages = implode(',', $filenames);
				}
				
				$out = sdmq()->update_dorm($dormref, $userref, $name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $dormImages, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay, $aircon, $elevator, $beddings, $kitchen, $laundry, $lounge, $parking, $security, $study_room, $wifi);
				if ($uploadStatus && $out == "1") {
					echo 'success';
				} else {
					echo 'failed';
				}
				break;
			
			case 'get_verification_status':
				$userref = _validate($_GET["userref"]);
				echo sdmq()->get_verification_status($userref);
				break;
			case 'forgot_password':
				$email = _validate($_POST['email']);
				$password = generatePassword();
				
				$subject = "Forgot Password - Login Credentials Inside";
				$body = '<!DOCTYPE html>
                            <html lang="en">
                            <head>
                                <meta charset="UTF-8">
                                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <link rel="stylesheet" href="https://use.typekit.net/pua2gnh.css">
                                <title>StudyHive</title>
                                <style>.container{ font-family: "proxima-nova", sans-serif !important; align-items: center; background-color: #F4F7F9; } .email{ font-family: "proxima-nova", sans-serif !important; align-items: center; background-repeat: repeat; height: auto; width: auto; padding-top: 40px; padding-bottom: 40px; margin-left: auto; margin-right: auto; } .content{ text-decoration: none; font-family: "proxima-nova", sans-serif !important; background-color: #ffffff; padding: 80px; width: 680px; margin-left: auto; margin-right: auto; } .footer{ text-decoration: none; padding: 32px 80px; width: 680px; text-align: center; align-items: center; margin-left: auto; margin-right: auto; } .footer .git{ margin-top: 10px; } .icons{ margin: 0 15.75px; } .content .logo{ margin-bottom: 50.52px; } .p-space{ margin-bottom: 40px; } .btn{ margin-left: 25%; width: 328px; } .blue-btn{ margin-bottom: 8px; padding: 0px; } .link{ background-color: #F4F7F9; border-radius: 5px; padding: 8px 16px; cursor: pointer; word-break: break-all; } .link a{ color: #0B6B9F; } .copyright{ color: #566376; text-align: center; } .copyright-first{ margin-left: auto; margin-right: auto; font-size: 13px; width: 519px; } .small-italic{ font-size: 12px; font-style: italic; } .copyright a{ color: #566376; } .t-p a{ text-decoration: underline; cursor: pointer; } p{ font-size: 19px; } button{ background-color: #0E898B; border: none; color: #fff; letter-spacing: 2px; border-radius: 5px; padding: 17px 34px; cursor: pointer; transition: .1s ease; width: 328px; } .button{ padding: 17px 0px; } button:hover { background-color: #0b6768; } .program-details{ padding: 8px 56px 32px 56px; border: 1px solid #E6EAED; border-radius: 10px; margin-bottom: 24px; } .program-details-header{ text-align: center; } .program-details-header p{ margin: 0px; } .program-details-body{ margin-top: 32px; } .program-details-body div{ margin-bottom: 16px; } .program-details-body p{ margin: 0px; } .sm-bold{ font-size: 14px; font-weight: 600; } .sm-normal{ font-size: 14px; font-weight: 14px; } .ul{ margin: 0px; } .table-td{ width: 204px; padding: 24px 24px 24px 0px; } table tr th{ text-align: left; } .support{ color: #0E898B; text-decoration: none; cursor: pointer; }
                                </style>
                            </head>
                        
                            <body>
                                <div class="container">
                                    <div class="email">
                                        <div class="content">
                                            <div class="logo" style="font-weight: bold; font-size: 25px;">
                                                StudyHive
                                            </div>
                        
                                            <div>
                                                <p class="p-space">
                                                    <h1>Forgot Password - StudyHive</h1>
                                                    <p>You have requested to reset your password. Here is your temporary password:</p>
                                                    <p><strong>New Password:</strong>'.$password.'</p>
                                                    <p>You can use this temporary password to login and then change your password to a more secure one or you can use this as your new password.</p>
                                                    <p>If you did not request a password reset, please disregard this email.</p>
                                                    <p>Thank you,</p>
                                                    <p>The StudyHive Team</p>
                                                </p>
                                            </div>
                        
                                            <br><div class="copyright">
                                                <p class="copyright-first">
                                                    © 2023 StudyHive. All rights reserved.
                                                </p>
                                                <p class="small-italic">
                                                    This is a system generated email. We might not be able to read your message if you respond here.
                                                </p><br>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </body>
                        </html>';
				$altBody = "
					Forgot Password - StudyHive
					You have requested to reset your password. Here is your temporary password:
					- New Password: {$password}
					You can use this temporary password to login and then change your password to a more secure one.
					If you did not request a password reset, please disregard this email.
					Thank you,
					The StudyHive Team
				";
				if (sdmq()->forgot_password($email, $password) == "1" && sendEmail($email, $subject, $body, $altBody)) {
						echo "success";
				} else {
						echo "failed";
				}
				break;
			case "payment":
			    $id = uniqid();
			    $token = _validate($_POST['token']);
			    $userref = _validate($_POST['userref']);
			    $ownerref = _validate($_POST['ownerref']);
			    $ownername = _validate($_POST['ownername']);
			    $dormref = _validate($_POST['dormref']);
			    $amount = _validate($_POST['amount']);
			    
			    $out = sdmq()->payment($id, $token, $userref, $ownerref, $ownername, $dormref, $amount);
				if ($out == "1") {
					echo 'success';
				} else {
					echo 'failed';
				}
			    break;
			case "get_transactions":
			    $userref = _validate($_GET['userref']);
					$isowner = _validate($_GET['is_owner']);
			    echo sdmq()->get_transactions($userref, $isowner);
				break;
		}
	} 
} else {
    include('./pages/index.inc');
}
