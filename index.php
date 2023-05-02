<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods:POST");
include_once "inc/conn.php";
include_once "mod/queries.php";



if (isset($_POST["tag"])) {
	//POST
	$tag = $_POST["tag"];
} else if (isset($_GET["tag"])) {
	//GET
	$tag = $_GET["tag"];
}

switch ($tag) {
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
	case 'verify_document':
		echo sdmq()->verify_document($_POST['id'], $_POST['docvalue']);
		break;
	case 'fetch_saved_notif':
		echo sdmq()->look_usersavednotifs();
		break;
	case 'get_submitdocuments':
		echo sdmq()->get_submitdocuments();
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
			echo 'Account Updated';
		} else {
			echo 'Cannot Update Account. Please Try Again';
		}
		break;
	case 'delete_account':
		$out = sdmq()->delete_account($_POST["userref"]);
		if ($out == "1") {
			echo 'Account Deleted';
		} else {
			echo 'Cannot Delete Account. Pleass Try Again';
		}
		break;
	case 'change_password':
		$currentpassword = $_POST['currentpassword'];
		$newpassword = $_POST['newpassword'];
		echo sdmq()->change_password($_POST["userref"], $currentpassword, $newpassword);
		break;
}
function sdmq()
{
	$c = new connection();
	$c = $c->sdm_connect();
	$sdm_q = new sdm_query($c);
	return $sdm_q;
}
