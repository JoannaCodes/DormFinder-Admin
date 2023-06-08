<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods:POST");
include_once "inc/conn.php";
include_once "mod/queries.php";

$domain = 'http:/192.168.0.24/DormFinder-Admin/';
$tag = '';

if (isset($_POST["tag"])) {
	//POST
	$tag = $_POST["tag"];
} else if (isset($_GET["tag"])) {
	//GET
	$tag = $_GET["tag"];
}

switch ($tag) {
	case 'clearallnotif':
		echo sdmq()->clearallnotif($_GET["userref"]);
		break;
	case 'change_status':
		echo sdmq()->change_status($_POST["btn_value"]);
		break;
	case 'open_document':
		echo sdmq()->open_document($_POST["user_id"]);
		break;
	case 'send_document':
		$target_dir = "uploads/user/";
		$target_file = $target_dir . basename($_FILES["document1"]["name"]);
		$target_file2 = $target_dir . basename($_FILES["document2"]["name"]);
		$filename1 = basename($_FILES["document1"]["name"]);
		$filename2 = basename($_FILES["document2"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

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
		if($imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx") {
			echo "Sorry, only PDF, DOC, and DOCX files are allowed.";
			$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
			// If everything is ok, try to upload file
		} else {
			$out = sdmq()->send_document($filename1,$filename2);
			if ($out == "1") {
				if (move_uploaded_file($_FILES["document1"]["tmp_name"], $target_file) && move_uploaded_file($_FILES["document2"]["tmp_name"], $target_file2)) {
				echo "The file ". htmlspecialchars( basename( $_FILES["document1"]["name"])). " and ". htmlspecialchars( basename( $_FILES["document2"]["name"])). " has been uploaded.";
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
	case 'login':
		echo sdmq()->login_dormfinder($_POST["email"], $_POST["password"]);
		break;
	case 'login_app':
		echo sdmq()->login_app($_POST["username"], $_POST["password"]);
			break;
	case 'signup_app':
		echo sdmq()->signup_app($_POST["email"], $_POST["username"], $_POST["password"]);
			break;	
	case 'verify_document':
		echo sdmq()->verify_document($_POST['id'], $_POST['docvalue']);
		break;
	case 'fetch_saved_notif':
		echo sdmq()->look_usersavednotifs();
		break;
	case 'get_submitdocuments':
		echo sdmq()->get_submitdocuments();
		break;
	case 'get_dormlisting':
		echo sdmq()->get_dormlisting();
		break;
	case 'get_users':
		echo sdmq()->get_users();
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
		// change domain to web hosts domain
		$fileName = $domain . $uploadDir . basename($image['name']);

		// Check if the folder already exists
		if (!file_exists($uploadDir)) {
			mkdir($uploadDir, 0777, true);
		}

		$out = sdmq()->update_profile($userref, $username, $fileName);
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
		echo sdmq()->get_reviews($_GET["dormref"]);
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
		$amenities = $_POST["amenities"];
		$images = $_FILES["images"];

		$visitors = $_POST["visitors"];
		$pets = $_POST["pets"];
		$curfew = $_POST["curfew"];

		// Convert to boolean
		$visitors = (int)filter_var($visitors, FILTER_VALIDATE_BOOLEAN);
		$pets = (int)filter_var($pets, FILTER_VALIDATE_BOOLEAN);
		$curfew = (int)filter_var($curfew, FILTER_VALIDATE_BOOLEAN);

		$advdep = $_POST["advance_deposit"] !== "" ? $_POST["advance_deposit"] : "N/A";
		$secdep = $_POST["security_deposit"] !== "" ? $_POST["security_deposit"] : "N/A";
		$util = $_POST["utilities"] !== "" ? $_POST["utilities"] : "N/A";
		$minstay = $_POST["minimum_stay"] !== "" ? $_POST["minimum_stay"] : "N/A";

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
		if (!file_exists($uploadDir)) {
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
		$out = sdmq()->post_dorm($id, $userref, $name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $amenities, $dormImages, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay);
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
		$amenities = $_POST["amenities"];

		$visitors = $_POST["visitors"];
		$pets = $_POST["pets"];
		$curfew = $_POST["curfew"];

		// Convert to boolean
		$visitors = (int)filter_var($visitors, FILTER_VALIDATE_BOOLEAN);
		$pets = (int)filter_var($pets, FILTER_VALIDATE_BOOLEAN);
		$curfew = (int)filter_var($curfew, FILTER_VALIDATE_BOOLEAN);

		$advdep = $_POST["advance_deposit"] !== "" ? $_POST["advance_deposit"] : "N/A";
		$secdep = $_POST["security_deposit"] !== "" ? $_POST["security_deposit"] : "N/A";
		$util = $_POST["utilities"] !== "" ? $_POST["utilities"] : "N/A";
		$minstay = $_POST["minimum_stay"] !== "" ? $_POST["minimum_stay"] : "N/A";

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

		if (isset($_FILES['images']) && is_array($_FILES['images']['tmp_name'])) {
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
		
		$out = sdmq()->update_dorm($dormref, $userref, $name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $amenities, $dormImages, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay);
		if ($uploadStatus && $out == "1") {
			echo 'success';
		} else {
			echo 'failed';
		}
		break;
	case 'send_custom_notif':
		$userref = $_POST["userref"];
		$message = $_POST["notifMessage"];

		$out = sdmq()->send_custom_notif($userref, $message);
		if ($out == "1") {
			echo "Notification sent to user:" . $userref;
		} else {
			echo "Failed to send notification to user:" . $userref;
		}
		break;
	case 'add_admin':
		$email = $_POST["email"];
		$password = generatePassword();
		// $message = '';

		// // Email content
		// $subject = "Admin Account";
		// $message .= "Congratulations! Your admin account has been successfully created.\n";
		// $message .= "Please find below your login credentials:\n";
		// $message .= "Email: " . $email . "\n";
		// $message .= "Password: " . $password . "\n\n";
		// $message .= "If you have any questions or need further assistance, feel free to contact us.\n\n";
		// $message .= "Best regards,\nThe UniHive Admin Team";

		// // Sender and recipient details
		// $from = "cyla1wp@ezztt.com"; // Replace with the actual sender email address: unihive-admin@gmail.com
		// $headers = "From: " . $from . "\r\n";
		// $headers .= "Reply-To: " . $from . "\r\n";
		// $headers .= "MIME-Version: 1.0\r\n";
		// $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

		$out = sdmq()->new_admin($email, $password);

		if ($out == "1") {
				echo "New admin added";
		} else {
				echo "Failed to add admin";
		}
		break;
	case 'delete_dorm_admin':
		$userref = $_POST["userref"];
		$dormref = $_POST["dormref"];

		$out = sdmq()->delete_dorm_admin($userref, $dormref);
		if ($out == "1") {
				echo "Dorm deleted. Notification sent to owner";
		} else {
				echo "Failed to delete dorm";
		}
		break;
	case 'send_dorm_notif':
		$userref = $_POST["userref"];
		$dormref = $_POST["dormref"];

		$out = sdmq()->send_dorm_notif($userref, $dormref);
		if ($out == "1") {
				echo "Notification sent to dorm owner";
		} else {
				echo "Failed to send notification";
		}
		break;
}

function getAddressCoordinates($address) {
    // Encode the address
    $encodedAddress = urlencode($address);
    
    // Nominatim API endpoint
    $url = "https://nominatim.openstreetmap.org/search.php?q=" . $encodedAddress . "&format=jsonv2";

		// Make a request to the API
    $opts = array(
			'http' => array(
					'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.93 Safari/537.36'
			)
		);
		$context = stream_context_create($opts);
		$response = file_get_contents($url, false, $context);
    
    $result = json_decode($response, true);
    if (is_array($result) && !empty($result)) {
        // Extract latitude and longitude
        $latitude = $result[0]['lat'];
        $longitude = $result[0]['lon'];

        return array('latitude' => $latitude, 'longitude' => $longitude);
    }

    return null;
}

function deleteDirectory($dir) {
	if (!file_exists($dir)) {
		return true;
	}

	if (!is_dir($dir)) {
		return unlink($dir);
	}

	foreach (scandir($dir) as $item) {
		if ($item == '.' || $item == '..') {
			continue;
		}

		if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
			return false;
		}
	}

	return rmdir($dir);
}

function generatePassword() {
	// Define the character set for the password
	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';

	// Get the total number of characters in the character set
	$characterCount = strlen($characters);

	// Initialize an empty password string
	$password = '';

	// Generate the password
	for ($i = 0; $i <= 8; $i++) {
			// Get a random index within the character set
			$randomIndex = mt_rand(0, $characterCount - 1);

			// Append the randomly selected character to the password string
			$password .= $characters[$randomIndex];
	}

	return $password;
}

function sdmq()
{
	$c = new connection();
	$c = $c->sdm_connect();
	$sdm_q = new sdm_query($c);
	return $sdm_q;
}
