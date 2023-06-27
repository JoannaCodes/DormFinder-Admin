<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods:POST");
include_once "inc/conn.php";
include_once "mod/queries.php";
include_once "functions.php";

$domain = 'http://studyhive.social/';
$tag = '';

if (isset($_POST["tag"])) {
	//POST
	$tag = $_POST["tag"];
} else if (isset($_GET["tag"])) {
	//GET
	$tag = $_GET["tag"];
}

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

$authKey = $_SERVER['HTTP_AUTH_KEY'] ?? '';

if($tag === "") {
    header("Location: ./dormfinder_admin.php");
}

if (authenticate($authKey)) {
	$action = _validate($_POST['tag'] ?? $_GET['tag']);

	if($action !== NULL) {
		switch ($tag) {
			case 'login_app':
				echo sdmq()->login_app($_POST["username"], $_POST["password"]);
				break;
			case 'login_app_guest':
				echo json_encode(["username" => 'guest', "id" => uniqid(), "status" => true, "mode" => "guest"]);
				break;
			case 'signup_app':
				echo sdmq()->signup_app($_POST["email"], $_POST["username"], $_POST["password"]);
				break;	
			case 'fetch_saved_notif':
				echo sdmq()->look_usersavednotifs($_GET['user_ref']);
				break;
			case 'check_ifsubmitted':
				echo sdmq()->check_ifsubmitted($_GET["user_id"]);
				break;
			case 'clearallnotif':
				echo sdmq()->clearallnotif($_GET["userref"]);
				break;
			case 'send_document':
				$target_dir = "uploads/user/" . $_POST['user_id'];
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
			case 'upload_image':
				$document = $_FILES['document'];
				$uploadDir = './uploads/';
				$uploadFile = $uploadDir . basename($document['name']);
				$fileName = basename($document['name']);
		
				$out = sdmq()->send_document($fileName);
				if ($out == "1") {
					if (move_uploaded_file($document['tmp_name'], $uploadFile)) {
						echo 'File is valid, and was successfully uploaded.';
					} else {
						echo 'Upload failed.';
					}
				} else {
					echo "Query failed.";
				}
				break;
			case "getmorenotification":
				echo sdmq()->look_morepastnotif($_GET["userref"], $_GET["idstoftech"]);
				break;
			case "getnotificationspast":
				echo sdmq()->look_passednotifications($_GET["userref"]);
				break;
			case "get_account":
				echo sdmq()->get_account($_GET["userref"]);
				break;
			case "update_account":
				$out = sdmq()->update_account($_POST["userref"], $_POST["identifier"]);
				if ($out == "1") {
					echo 'success';
				} else {
					echo 'failed';
				}
				break;
			case 'delete_account':
				$out = sdmq()->delete_account($_POST["userref"]);
				if ($out == "1") {
					echo 'success';
				} else {
					echo 'failed';
				}
				break;
			case 'change_password':
				$currentpassword = $_POST['currentpassword'];
				$newpassword = $_POST['newpassword'];
		
				$out = sdmq()->change_password($_POST["userref"], $currentpassword, $newpassword);
				echo $out;
				break;
			case 'get_dorms':
				echo sdmq()->get_dorms($_GET["userref"]);
				break;
			case 'get_dorm_details':
				echo sdmq()->get_dorm_details($_GET["dormref"]);
				break;
			case 'get_bookmarks':
				echo sdmq()->get_bookmarks($_GET["userref"]);
				break;
			case 'add_bookmarks':
					echo sdmq()->add_bookmarks($_POST["userref"], $_POST["dormref"]);
				break;
			case 'popular_dorm':
					echo sdmq()->popular_dorm();
				break;
			case 'latest_dorm':
					echo sdmq()->latest_dorm();
				break;
			case 'nearest_dorm':
				$lon = $_POST['longitude'];
				$lat = $_POST['latitude'];
				
				$out = sdmq()->nearest_dorm($lon, $lat);
				if (empty($out)) {
					echo json_encode(['message' => 'No data available']);
				} else {
					echo $out;
				}
				break;
			case "update_profile":
				$image = $_FILES['image'];
				$userref = $_POST["userref"];
				$username = $_POST["username"];
		
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
			case 'delete_bookmark':
				$out = sdmq()->delete_bookmark($_POST["dormref"], $_POST["userref"]);
				if ($out == "1") {
					echo 'success';
				} else {
					echo 'failed';
				}
				break;
			case 'post_review':
				$out = sdmq()->post_review($_POST["dormref"], $_POST["userref"], $_POST["rating"], $_POST["comment"], );
				if ($out == "1") {
					echo 'success';
				} else {
					echo 'failed';
				}
				break;
			case 'get_reviews':
				echo sdmq()->get_reviews($_GET["dormref"] ?? 0);
				break;
			case 'post_report':
				$id = uniqId();
					$out = sdmq()->post_report($id, $_POST["dormref"], $_POST["userref"], $_POST["comment"], );
					if ($out == "1") {
						echo 'success';
					} else {
						echo 'failed';
					}
				break;
			
			case 'delete_dorm':
				$dormref = $_POST["dormref"];
				$userref = $_POST["userref"];
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
				$userref = $_POST["userref"];
		
				$name = $_POST["name"];
				$address = $_POST["address"];
				$price = $_POST["price"];
				$slots = (int)$_POST["slots"];
				$desc = $_POST["desc"];
				$hei = $_POST["hei"];
				$images = $_FILES["images"];
		
				$visitors = $_POST["visitors"];
				$pets = $_POST["pets"];
				$curfew = $_POST["curfew"];
		
				$aircon = $_POST['aircon'];
				$elevator = $_POST['elevator'];
				$beddings = $_POST['beddings'];
				$kitchen = $_POST['kitchen'];
				$laundry = $_POST['laundry'];
				$lounge = $_POST['lounge'];
				$parking = $_POST['parking'];
				$security = $_POST['security'];
				$study_room = $_POST['study_room'];
				$wifi = $_POST['wifi'];
		
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
		
				$advdep = $_POST["advance_deposit"];
				$secdep = $_POST["security_deposit"];
				$util = $_POST["utilities"];
				$minstay = $_POST["minimum_stay"];
		
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
				$dormref = $_POST["dormref"];
				$userref = $_POST["userref"];
		
				$name = $_POST["name"];
				$address = $_POST["address"];
				$price = $_POST["price"];
				$slots = (int)$_POST["slots"];
				$desc = $_POST["desc"];
				$hei = $_POST["hei"];
		
				$visitors = $_POST["visitors"];
				$pets = $_POST["pets"];
				$curfew = $_POST["curfew"];
		
				$aircon = $_POST['aircon'];
				$elevator = $_POST['elevator'];
				$beddings = $_POST['beddings'];
				$kitchen = $_POST['kitchen'];
				$laundry = $_POST['laundry'];
				$lounge = $_POST['lounge'];
				$parking = $_POST['parking'];
				$security = $_POST['security'];
				$study_room = $_POST['study_room'];
				$wifi = $_POST['wifi'];
		
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
		
				$advdep = $_POST["advance_deposit"];
				$secdep = $_POST["security_deposit"];
				$util = $_POST["utilities"];
				$minstay = $_POST["minimum_stay"];
		
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
					$existingFiles = glob($uploadDir . '*');
					foreach ($existingFiles as $file) {
						if (is_file($file)) {
							unlink($file);
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
				$userref = $_GET["userref"];
				echo sdmq()->get_verification_status($userref);
				break;
			case 'forgot_password':
				$email = $_POST['email'];
				$password = generatePassword();
				
				$subject = "Forgot Password - Login Credentials Inside";
				$body = "
					<h1>Forgot Password - StudyHive</h1>
					<p>You have requested to reset your password. Here is your temporary password:</p>
					<p><strong>New Password:</strong> {$password}</p>
					<p>You can use this temporary password to login and then change your password to a more secure one or you can use this as your new password.</p>
					<p>If you did not request a password reset, please disregard this email.</p>
					<p>Thank you,</p>
					<p>The StudyHive Team</p>
				";
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
				
			default:
				header("Location: ./dormfinder_admin.php");
			break;
		}
	} 
} else {
	echo json_encode(["message" => "Invalid authentication key!"]);
}

function sdmq()
{
	$c = new connection();
	$c = $c->sdm_connect();
	$sdm_q = new sdm_query($c);
	return $sdm_q;
}
