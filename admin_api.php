<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods:POST");

include_once "inc/conn.php";
include_once "functions.php";
include_once "mod/admin_queries.php";

$domain = 'http://studyhive.social/admin.php';
$tag = '';

    
if (isset($_POST["tag"])) {
	//POST
	$tag = $_POST["tag"];
} else if (isset($_GET["tag"])) {
	//GET
	$tag = $_GET["tag"];
}

function access() {
    if(!isset($_SESSION['id'])) {
        // Error, admin must login to access api.
        $array = array('message' => 'You don\'t have access here!', 'status' => 403);
        echo json_encode($array);
    }
}
switch ($tag) {
  // Admin Queries
    case 'change_status':
        access();
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
        access();
        echo adminq()->open_document($_POST["user_id"]);
    break;
    case 'send_custom_notif':
        access();
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
        access();
    $email = $_POST["email"];
    $password = generatePassword();
    $subject = "Invitation to Admin Website - Login Credentials Inside";
    $body = '
      <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://use.typekit.net/pua2gnh.css">
            <title>StudyHive</title>
            <style>
                .container{ font-family: "proxima-nova", sans-serif !important; align-items: center; background-color: #F4F7F9; } .email{ font-family: "proxima-nova", sans-serif !important; align-items: center; background-repeat: repeat; height: auto; width: auto; padding-top: 40px; padding-bottom: 40px; margin-left: auto; margin-right: auto; } .content{ text-decoration: none; font-family: "proxima-nova", sans-serif !important; background-color: #ffffff; padding: 80px; width: 680px; margin-left: auto; margin-right: auto; } .footer{ text-decoration: none; padding: 32px 80px; width: 680px; text-align: center; align-items: center; margin-left: auto; margin-right: auto; } .footer .git{ margin-top: 10px; } .icons{ margin: 0 15.75px; } .content .logo{ margin-bottom: 50.52px; } .p-space{ margin-bottom: 40px; } .btn{ margin-left: 25%; width: 328px; } .blue-btn{ margin-bottom: 8px; padding: 0px; } .link{ background-color: #F4F7F9; border-radius: 5px; padding: 8px 16px; cursor: pointer; word-break: break-all; } .link a{ color: #0B6B9F; } .copyright{ color: #566376; text-align: center; } .copyright-first{ margin-left: auto; margin-right: auto; font-size: 13px; width: 519px; } .small-italic{ font-size: 12px; font-style: italic; } .copyright a{ color: #566376; } .t-p a{ text-decoration: underline; cursor: pointer; } p{ font-size: 19px; } button{ background-color: #0E898B; border: none; color: #fff; letter-spacing: 2px; border-radius: 5px; padding: 17px 34px; cursor: pointer; transition: .1s ease; width: 328px; } .button{ padding: 17px 0px; } button:hover { background-color: #0b6768; } .program-details{ padding: 8px 56px 32px 56px; border: 1px solid #E6EAED; border-radius: 10px; margin-bottom: 24px; } .program-details-header{ text-align: center; } .program-details-header p{ margin: 0px; } .program-details-body{ margin-top: 32px; } .program-details-body div{ margin-bottom: 16px; } .program-details-body p{ margin: 0px; } .sm-bold{ font-size: 14px; font-weight: 600; } .sm-normal{ font-size: 14px; font-weight: 14px; } .ul{ margin: 0px; } .table-td{ width: 204px; padding: 24px 24px 24px 0px; } table tr th{ text-align: left; } .support{ color: #0E898B; text-decoration: none; cursor: pointer; }
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
                                <h1>Welcome to the StudyHive Team</h1>
                                <p>Congratulations! You have been invited as a new admin.</p>
                                <p>Please use the following login credentials to access the admin website:</p>
                                <ul>
                                    <li><strong>Email:</strong> '.$email.'</li>
                                    <li><strong>Password:</strong> '.$password.'</li>
                                </ul>
                            </p>
                        </div>
                        <div class="btn">
                            <div class="blue-btn">
                                <a href="'.$domain.'"><button class="button">LOG IN</button>
                                </a>
                            </div>
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
    </html>
    ';

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
        access();
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
        access();
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
        access();
        echo adminq()->get_submitdocuments();
    break;
    case 'get_dormlisting':
        access();
        echo adminq()->get_dormlisting();
    break;
    case 'get_users':
        access();
        echo adminq()->get_users();
    break;
    case 'get_adminusers':
        access();
        echo adminq()->get_adminusers();
    break;
    case 'get_reports':
        access();
        echo adminq()->get_reports();
    break;
    case 'get_payment_transaction_history':
        access();
        echo adminq()->get_payment_transaction_history();
    break;
    case 'resolve_report_admin':
        access();
        $reportid = $_POST["reportid"];
    
        $out = adminq()->resolve_dorm($reportid);
        if ($out == "1") {
          echo json_encode(["message" => "Report resolved {$reportid}"]);
        } else {
          echo json_encode(["message" => "Failed to resolve report: {$reportid}"]);
        }
    break;
    case 'showhide_dorm_admin':
        access();
        $userref = $_POST["userref"];
        $dormref = $_POST["dormref"];
    
        $out = adminq()->showhide_dorm_admin($userref, $dormref);
        if ($out == "1") {
          echo json_encode(["message" => "A notification has been sent to the owner regarding this action."]);
        } else {
          echo json_encode(["message" => "Failed to do hide dorm"]);
        }
    break;
    case 'statistics':
        access();
        echo json_encode(adminq()->statistics());
    break;
    case 'get_user_statistics':
        access();
        echo json_encode(adminq()->get_user_statistics());
    break;
    case 'get_transaction_statistics':
        access();
        echo json_encode(adminq()->get_transaction_statistics());
    break;
    case 'top_reviews_in_dorm':
        access();
        echo json_encode(adminq()->top_reviews_in_dorm());
    break;
    case 'delete_admin_user':
        access();
        $out = adminq()->delete_admin_user($_POST['user_id']);
        if ($out == "1") {
          echo json_encode(["message" => "Removed admin user"]);
        } else {
          echo json_encode(["message" => "Failed to remove admin user"]);
        }
    break;
    case 'changePass':
        access();
        echo adminq()->changePass($_POST["currentpass"], $_POST["newpass"], $_POST["retypenewpass"]);
    break;
    default:
        access();
    break;
}

function adminq()
{
	$c = new connection();
	$c = $c->sdm_connect();
	$admin_q = new admin_query($c);
	return $admin_q;
}
