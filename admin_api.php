<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods:POST");
include_once "inc/conn.php";
include_once "functions.php";
include_once "mod/admin_queries.php";

$domain = 'http://studyhive.social/';
$tag = '';

if (isset($_POST["tag"])) {
	//POST
	$tag = $_POST["tag"];
} else if (isset($_GET["tag"])) {
	//GET
	$tag = $_GET["tag"];
}

switch ($tag) {
  // Admin Queries
  case 'change_status':
    $out = adminq()->change_status($_POST["btn_value"],$_POST['user_id']);
    echo $out;

    $folderPath = 'uploads/user/' . $_POST['user_id'] . '/';

    if($out == "0") {
      if (is_dir($folderPath)) {
        deleteDirectory($folderPath);
      } else {
        echo json_encode(["message" => "Folder does not exist"]);
      }
    }
    break;
  case 'open_document':
    echo adminq()->open_document($_POST["user_id"]);
    break;
  case 'send_custom_notif':
    $userref = $_POST["userref"];
    $message = $_POST["notifMessage"];

    $out = adminq()->send_custom_notif($userref, $message);
    if ($out == "1") {
      echo json_encode(["message" => "Notification sent to user: {$userref}"]);
    } else if ($out == "0") {
      echo json_encode(["message" => "User {$userref} does not exist"]);
    } else {
      echo json_encode(["message" => "Failed to send notification to user: {$userref}"]);
    }
    break;
  case 'add_admin':
    $email = $_POST["email"];
    $password = generatePassword();
    $subject = "Invitation to Admin Website - Login Credentials Inside";
    $body = "
      <h1>Welcome to the StudyHive Team</h1>
      <p>Congratulations! You have been invited as a new admin.</p>
      <p>Please use the following login credentials to access the admin website:</p>
      <ul>
          <li><strong>Email:</strong> {$email}</li>
          <li><strong>Password:</strong> {$password}</li>
      </ul>
      <p>Visit the <a href={$domain}>Admin Website</a> to log in.</p>
    ";

    $altBody = "
        Welcome to the Admin Website

        Congratulations! You have been invited as a new admin.

        Please use the following login credentials to access the admin website:

        - Username: {$email}
        - Password: {$password}

        Visit the Admin Website ({$domain}) to log in.
    ";

    if (adminq()->new_admin($email, $password) == "1" && sendEmail($email, $subject, $body, $altBody)) {
        echo json_encode(["message" => "New admin added"]);
    } else {
        echo json_encode(["message" => "Failed to add admin"]);
    }
    break;
  case 'delete_dorm_admin':
    $userref = $_POST["userref"];
    $dormref = $_POST["dormref"];

      $folderPath = 'uploads/dormImages/' . $dormref . '/';

    if (is_dir($folderPath)) {
      if (deleteDirectory($folderPath)) {
        $out = adminq()->delete_dorm_admin($userref, $dormref);
        if ($out == "1") {
          echo json_encode(["message" => "Dorm deleted, notification sent to owner"]);
        } else {
          echo json_encode(["message" => "Failed to deleted dorm listing"]);
        }
      } else {
        echo 'failed.';
      }
    } else {
      echo json_encode(["message" => "Folder does not exist"]);
    }
    break;
  case 'send_dorm_notif':
    $userref = $_POST["userref"];
    $dormref = $_POST["dormref"];

    $out = adminq()->send_dorm_notif($userref, $dormref);
    if ($out == "1") {
          echo json_encode(["message" => "Notification sent to dorm owner"]);
    } else {
          echo json_encode(["message" => "Failed to send notification"]);
    }
    break;
  case 'login':
    echo adminq()->login_dormfinder($_POST["email"], $_POST["password"]);
    break;
  case 'get_submitdocuments':
    echo adminq()->get_submitdocuments();
    break;
  case 'get_dormlisting':
    echo adminq()->get_dormlisting();
    break;
  case 'get_users':
    echo adminq()->get_users();
    break;
  case 'get_reports':
    echo adminq()->get_reports();
    break;
  case 'resolve_report_admin':
    $reportid = $_POST["reportid"];

    $out = adminq()->resolve_dorm($reportid);
    if ($out == "1") {
      echo json_encode(["message" => "Report resolved {$reportid}"]);
    } else {
      echo json_encode(["message" => "Failed to resolve report: {$reportid}"]);
    }
    break;
  
  default:
    header("Location: ./dormfinder_admin.php");
  break;
}

function adminq()
{
	$c = new connection();
	$c = $c->sdm_connect();
	$admin_q = new admin_query($c);
	return $admin_q;
}
