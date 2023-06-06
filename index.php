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
		}
		break;
	case 'delete_account':
		$out = sdmq()->delete_account($_POST["userref"]);
		if ($out == "1") {
			echo 'success';
		}
		break;
	case 'change_password':
		$currentpassword = $_POST['currentpassword'];
		$newpassword = $_POST['newpassword'];
		echo sdmq()->change_password($_POST["userref"], $currentpassword, $newpassword);
		break;
	case 'get_dorms':
		echo sdmq()->get_dorms($_GET["userref"]);
		break;
	case 'get_dorm_details':
		echo sdmq()->get_dorm_details($_GET["dormref"], $_GET["userref"]);
		break;
	case 'get_bookmarks':
		echo sdmq()->get_bookmarks($_GET["userref"]);
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
		}
		break;
	case 'post_review':
		$out = sdmq()->post_review($_POST["dormref"], $_POST["userref"], $_POST["rating"], $_POST["comment"], );
		if ($out == "1") {
			echo 'success';
		}
		break;
	case 'get_reviews':
		echo sdmq()->get_reviews($_GET["dormref"]);
		break;
	case 'delete_dorm':
		$out = sdmq()->delete_dorm($_POST["dormref"], $_POST["userref"]);
		if ($out == "1") {
			echo 'success';
		}
		break;
	case 'post_dorm':
		$id = uniqid();
		$userref = $_POST["userref"];

		$name = $_POST["name"];
		$address = $_POST["address"];
		$price = $_POST["price"];
		$slots = $_POST["slots"];
		$desc = $_POST["desc"];
		$hei = $_POST["hei"];
		$amenities = $_POST["amenities"];
		$images = $_FILES['images'];

		$visitors = $_POST["visitors"];
		$pets = $_POST["pets"];
		$curfew = $_POST["curfew"];

		$advdep = $_POST["advance_deposit"];
		$secdep = $_POST["security_deposit"];
		$util = $_POST["utilities"];
		$minstay = $_POST["minimum_stay"];

		$longitude = 0;
		$latitude = 0;

		$uploadStatus = true;
		$filenames = [];

		// Address Geocoding
		if ($coordinates) {
			$latitude = $coordinates['latitude'];
			$longitude = $coordinates['longitude'];
    }

		foreach ($images['tmp_name'] as $index => $tmpName) {
				$name = $images['name'][$index];
				$uploadDir = 'uploads/dormImages/' . $dormref . '/';
				$filename = basename($name);
				$uploadFile = $uploadDir . $filename;
				
				// Move the file to the destination directory
				if (!move_uploaded_file($tmpName, $uploadFile)) {
					$uploadStatus = false;
				} else {
					array_push($fileNames, $filename);
				}
		}

		$dormImages = implode(',', $fileNames);
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
		$slots = $_POST["slots"];
		$desc = $_POST["desc"];
		$hei = $_POST["hei"];
		$amenities = $_POST["amenities"];
		$images = $_FILES['images'];

		$visitors = $_POST["visitors"];
		$pets = $_POST["pets"];
		$curfew = $_POST["curfew"];

		$advdep = $_POST["advance_deposit"];
		$secdep = $_POST["security_deposit"];
		$util = $_POST["utilities"];
		$minstay = $_POST["minimum_stay"];

		// Address Geocoding
		$coordinates = getAddressCoordinates($address);
    if ($coordinates) {
			$latitude = $coordinates['latitude'];
			$longitude = $coordinates['longitude'];
    }

		// Checks if images is empty
		if (!empty($images) && is_array($images['tmp_name'])) {
			// Remove existing files in the folder
			$uploadDir = 'uploads/dormImages/' . $dormref . '/';
			$existingFiles = glob($uploadDir . '*');
			foreach ($existingFiles as $file) {
				if (is_file($file)) {
						unlink($file);
				}
			}

			// Upload images
			$uploadStatus = true;
			$filenames = [];
			foreach ($images['tmp_name'] as $index => $tmpName) {
				$name = $images['name'][$index];
				$filename = basename($name);
				$uploadFile = $uploadDir . $filename;

				// Move the file to the destination directory
				if (!move_uploaded_file($tmpName, $uploadFile)) {
						$uploadStatus = false;
				} else {
						array_push($filenames, $filename);
				}
			}
		}

		$dormImages = implode(',', $fileNames);

		if (empty($dormImages)) {
			$out = sdmq()->update_dorm($id, $userref, $name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $amenities, null, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay);
		} else {
			$out = sdmq()->update_dorm($id, $userref, $name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $amenities, $dormImages, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay);
		}

		if ($uploadStatus && $out == "1") {
			echo 'success';
		} else {
			echo 'failed';
		}
		break;
}

function getAddressCoordinates($address) {
    // Encode the address
    $encodedAddress = urlencode($address);
    
    // Nominatim API endpoint
    $url = "https://nominatim.openstreetmap.org/search?format=json&q={$encodedAddress}";

    // Make a request to the API
    $response = file_get_contents($url);
    
    // Parse the JSON response
    $result = json_decode($response, true);
    
    // Check if the response contains valid data
    if (is_array($result) && !empty($result)) {
        // Extract latitude and longitude
        $latitude = $result[0]['lat'];
        $longitude = $result[0]['lon'];
        
        // Return the coordinates
        return array('latitude' => $latitude, 'longitude' => $longitude);
    }
    
    // Return null if no coordinates found
    return null;
}

function sdmq()
{
	$c = new connection();
	$c = $c->sdm_connect();
	$sdm_q = new sdm_query($c);
	return $sdm_q;
}
