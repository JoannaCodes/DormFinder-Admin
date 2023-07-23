<?php
include_once "functions.php";

class sdm_query
{
	private $c;
	public function __construct($db) {
		$this->c = $db;
	}

	public function push_notification($title, $message, $userref) {
		// destination is either FCM Device Key or Topic
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_notif_fcmkeys WHERE user_ref=? GROUP BY fcm_key",[$userref]),true),true);
		$destination = "";

		$fcm_array=array();

		for ($i=0; $i<count($out); $i++) {
			array_push($fcm_array,$out[$i]['fcm_key']);
		}

		$fields = array (
		  // 'to'  => $destination,
			'registration_ids' => $fcm_array,
		    'priority' => 'high',
		    'notification' => array(
					'user_ref' => $userref,
					'body' => $message,
					'title' => $title,
					'sound' => 'default',
					'icon' => '',
					'image'=> ''
		    ),
		    'data' => array(
					'message' => $message,
					'title' => $title,
					'sound' => 'default',
					'icon' => '',
					'image'=> ''
		    )
		);

	    $API_ACCESS_KEY = 'AAAA6nLtQuQ:APA91bEKGMpkgZEFL4WBCB9O0_ESzPhTWOlEtAN57An3FZLn1Uf-bWvsIr5kxZgu4_xJAH81xDgcJpd0RaqqraoeouSjf__51ciCLjzErTyielULcBJgXivadnDTWVWC3csWl_JJt3OF';
	    $headers = array
	    (
	        'Authorization: key=' . $API_ACCESS_KEY,
	        'Content-Type: application/json'
	    );
	    $ch = curl_init();
	    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
	    curl_setopt( $ch,CURLOPT_POST, true );
	    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	    $result = curl_exec($ch );
	    curl_close( $ch );
	    return $result;
	}

	public function check_ifsubmitted($user_id) {
		$out = json_decode($this->QuickLook("SELECT * FROM tbl_documents WHERE user_id=?", [$user_id], true));
		$outx = json_decode($out, true);
		if (count($outx) == 2) {
			if($outx[0]['doc1_status'] == "1") {
				return "1"; //approved
			} else {
				return "0"; //pending
			}
		} else {
			return "2"; //disapproved
		}
	}

	public function send_document($target_file,$target_file2,$user_id) {
		if ($this->QuickFire("INSERT INTO tbl_documents SET doc_1=?, doc1_status=?, user_id=?",[$target_file, "0", $user_id])) {
			if ($this->QuickFire("INSERT INTO tbl_documents SET doc_1=?, doc1_status=?, user_id=?",[$target_file2, "0", $user_id])) {
				return "1";
			}
		}
	}

	public function login_app($username, $password, $fcm) {
		$out = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE username=? OR identifier=? AND password=?", [$username, $username, $password], true));
		$outx = json_decode($out, true);

		if (count($outx) == 1) {
			if (($outx[0]["username"] == $username || $outx[0]["identifier"] == $username)) {
				if ($outx[0]["password"] == $password) {
					echo json_encode(["username" => $outx[0]['username'], "id" => $outx[0]['id'], "status" => true, "mode" => "user", "is_email_verified" => (int)$outx[0]['is_email_verified']]);
					
					$dtnow = date("Y-m-d H:i:s");
				  $this->QuickFire("INSERT INTO tbl_notif_fcmkeys SET user_ref=?, fcm_key=?, created_at=?",[$outx[0]['id'],$fcm,$dtnow]);
				} else {
					echo json_encode(["status" => false]);
				}
			}
	  }
	}

	public function signup_app($id, $email, $username, $password, $verifyKey) {
		$result = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE username = ? AND identifier = ?", [$username, $email], true));
		$existingUser = json_decode($result, true);
	
		if (empty($existingUser)) {
			if ($this->QuickFire("INSERT INTO tbl_users SET id=?, identifier=?, username=?, `password`=?, unique_verifykey=?, updated_at=now(), created_at=now()", [$id, $email, $username, $password, $verifyKey])) {
				return "1";
			}
		} else {
			return "0";
		}
	}

	public function logout_app($userref) {
	  if ($this->QuickFire("DELETE FROM tbl_notif_fcmkeys WHERE user_ref=?",[$userref])) {
			return "1";
		}
	}

  public function clearallnotif($userref) {
		if ($this->QuickFire("DELETE FROM tbl_notifications WHERE user_ref=?",[$userref])) {
			return "0";
		}
	}

	public function DateExplainer($thedate) {
		$future = strtotime($thedate); //Future date.
		$timefromdb = strtotime(date("Y-m-d"));
		$timeleft = ($future - $timefromdb);
		$daysleft = round((($timeleft / 24) / 60) / 60);

		if ($daysleft < 1) {
			$formatted_day = str_replace("-", "", $daysleft);

			if ($formatted_day == 1) {
				$dleft =  "yesterday";
			} else {


				$months = floor($formatted_day / 30);

				if ($months > 0) {
					if ($months == 1) {
						$dleft =  "a month ago";
					} else {
						$dleft =  $months . " months ago";
					}
				} else {
					if ($formatted_day == 0) {
						$dleft =  "today";
					} else {
						$dleft =  $formatted_day . " days ago";
					}
				}
			}
		} else {
			$formatted_day = $daysleft;

			if ($formatted_day == 1) {
				$dleft =  "today";
			} else {

				$months = floor($formatted_day / 30);



				if ($months > 0) {
					$dleft =  $months . " months ago";
				} else {
					$dleft =  $formatted_day . " days ago";
				}
			}

			if ($daysleft == 1) {
				$dleft = "tomorrow";
			} else {

				$months = floor($daysleft / 30);

				if ($months > 0) {
					$dleft =  $months . " months to go";
				} else {
					$dleft =  $daysleft . " days to go";
				}
			}
		}
		return $dleft;
	}

	public function look_usersavednotifs($user_ref) {
		$currdate = date("Y-m-d H:i:s");
		return $this->QuickLook("SELECT *, UNIX_TIMESTAMP(scheduled) as unix_time FROM tbl_notifications WHERE scheduled>? AND user_ref=?", [$currdate,$user_ref]);
	}

	public function look_passednotifications($userref) {
		$thiday = date("Y-m-d");
		return $this->QuickLook("SELECT DATE_FORMAT(scheduled, '%b %e, %Y %l:%i %p') AS formatted_date, ndesc, title  FROM tbl_notifications WHERE
		user_ref=? ORDER BY id DESC", [$userref]);
	}

  public function get_account($id) {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE id=?", [$id]), true), true);
		if (count($out) == 1) {
			return json_encode(json_encode($out[0]));
		}
	}

	public function update_account($id, $identifier) {
		if ($this->QuickFire("UPDATE tbl_users SET identifier=?, updated_at=now() WHERE id=?", [$identifier, $id])) {
			return "1";
		}
	}

	public function delete_account($id) {
		if ($this->QuickFire("DELETE FROM tbl_users WHERE id=?", [$id])) {
			$dorm = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms WHERE id=?", [$id]), true), true);
			$dormImagesPath = 'uploads/dormImages/' . $dorm[0]['id'] . '/';
		    
			if (is_dir($dormImagesPath)) {
				deleteDirectory($dormImagesPath);
			} else {
				echo json_encode(["message" => "Folder does not exist"]);
			}
		}
	}

	public function change_password($id, $currentpassword, $newpassword) {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE id=?", [$id]), true), true);
		if ($out[0]['password'] == $currentpassword) {
			if ($this->QuickFire("UPDATE tbl_users SET password=?, updated_at=now() WHERE id=?", [$newpassword, $id])) {
				return 'success';
			} else {
				return 'failed';
			}
		} else {
			return 'incorrect';
		}
	}

	public function get_dorms($userref) {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms WHERE userref=?", [$userref]), true), true);
		return json_encode(json_encode($out));
	}

	public function get_dorm_details($dormref) {
		$out = json_decode(json_decode($this->QuickLook("SELECT d.*, a.* FROM tbl_dorms d JOIN tbl_amenities a ON d.id = a.dormref WHERE d.id=?", [$dormref]), true), true);
		return json_encode(json_encode($out[0]));
	}

	public function get_bookmarks($userref) {
		$out = json_decode(json_decode($this->QuickLook("SELECT d.* FROM tbl_dorms d INNER JOIN tbl_bookmarks b ON d.id = b.dormref WHERE b.userref=? AND d.hide = 0", [$userref]), true), true);
		return json_encode(json_encode($out));
	}

	public function add_bookmarks($userref, $dormref) {
		if ($this->QuickFire("INSERT INTO tbl_bookmarks SET dormref=?, userref=?", [$dormref, $userref])) {
			return "1";
		}
	}

	public function update_profile($userref, $username, $filename) {
	  $profileQuery = empty($filename) ? "UPDATE tbl_users SET username=?, updated_at=now() WHERE id=?" : "UPDATE tbl_users SET username=?, imageUrl=?, updated_at=now() WHERE id=?";
		$profileParams = empty($filename) ? [$username, $userref] : [$username, $filename, $userref];
				
		if ($this->QuickFire($profileQuery, $profileParams)) {
			return "1";
		}
	}

	public function delete_bookmark($dormref, $userref) {
		if ($this->QuickFire("DELETE FROM `tbl_bookmarks` WHERE dormref=? AND userref=?", [$dormref, $userref])) {
			return "1";
		}
	}

	public function post_review($dormref, $userref, $rating, $comment) {
		if ($this->QuickFire("INSERT INTO tbl_dormreviews SET dormref=?, userref=?, rating=?, comment=?, createdAt=now()", [$dormref, $userref, $rating, $comment])) {
			if ($this->QuickLook("UPDATE tbl_transactions SET has_reviewed=? WHERE userref=? AND dormref=?", [1, $userref, $dormref])) {
				return "1";
			}
		}
	}

	public function get_reviews($dormref) {
		$out = json_decode(json_decode($this->QuickLook("SELECT u.id, u.username, u.imageUrl, r.rating, r.comment, r.createdAt FROM tbl_users u INNER JOIN tbl_dormreviews r ON u.id = r.userref WHERE dormref=?", [$dormref]), true), true);
		return json_encode(json_encode($out));
	}

	public function post_report($id, $dormref, $userref, $comment) {
		if ($this->QuickFire("INSERT INTO tbl_dormreports SET dormref=?, userref=?, comment=?, createdAt=now()", [$dormref, $userref, $comment])) {
			return "1";
		}
	}

	public function delete_dorm($dormref, $userref) {
		if ($this->QuickFire("DELETE FROM `tbl_dorms` WHERE id=? AND userref=?", [$dormref, $userref])) {
			return "1";
		}
	}

	public function popular_dorm() {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms d WHERE d.id IN (SELECT r.dormref FROM tbl_dormreviews r WHERE r.rating>=3)", []), true), true);
		return json_encode(json_encode($out));
	}

	public function latest_dorm() {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms ORDER BY createdAt DESC LIMIT 50", []), true), true);
		return json_encode(json_encode($out));
	}

	public function nearest_dorm($latitude, $longitude) {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms WHERE (6371 * acos(cos(radians(123.456)) * cos(radians(latitude)) * cos(radians(longitude) - radians(789.012)) + sin(radians(123.456)) * sin(radians(latitude))));"), true), true);
		return json_encode(json_encode($out));
	}

	public function update_dorm(
		$dormref,
		$userref,
		$name,
		$address,
		$longitude,
		$latitude,
		$price,
		$payduration, 
		$paypolicy,
		$slots,
		$desc,
		$hei,
		$dormImages,
		$visitors,
		$pets,
		$curfew,
		$advDep,
		$secDep,
		$util,
		$minStay,
		$aircon,
		$elevator,
		$beddings,
		$kitchen,
		$laundry,
		$lounge,
		$parking,
		$security,
		$studyRoom,
		$wifi
		){
		$dormQuery = empty($dormImages)
			? "UPDATE tbl_dorms SET `name`=?, `address`=?, longitude=?, latitude=?, price=?, payment_duration=?, payment_policy=?, slots=?, `desc`=?, hei=?, visitors=?, pets=?, curfew=?, adv_dep=?, sec_dep=?, util=?, min_stay=?, updatedAt=NOW() WHERE id=? AND userref=?"
			: "UPDATE tbl_dorms SET `name`=?, `address`=?, longitude=?, latitude=?, price=?, payment_duration=?, payment_policy=?, slots=?, `desc`=?, hei=?, images=?, visitors=?, pets=?, curfew=?, adv_dep=?, sec_dep=?, util=?, min_stay=?, updatedAt=NOW() WHERE id=? AND userref=?";

		$dormParams = empty($dormImages)
			? [$name, $address, $longitude, $latitude, $price, $payduration, $paypolicy, $slots, $desc, $hei, $visitors, $pets, $curfew, $advDep, $secDep, $util, $minStay, $dormref, $userref]
			: [$name, $address, $longitude, $latitude, $price, $payduration, $paypolicy, $slots, $desc, $hei, $dormImages, $visitors, $pets, $curfew, $advDep, $secDep, $util, $minStay, $dormref, $userref];

		$amenitiesQuery = "UPDATE tbl_amenities SET aircon=?, elevator=?, beddings=?, kitchen=?, laundry=?, lounge=?, parking=?, `security`=?, study_room=?, wifi=? WHERE dormref=?";
		$amenitiesParams = [$aircon, $elevator, $beddings, $kitchen, $laundry, $lounge, $parking, $security, $studyRoom, $wifi, $dormref];

		try {
			if ($this->quickFire($dormQuery, $dormParams)) {
				if ($this->quickFire($amenitiesQuery, $amenitiesParams)) {
					return "1";
				}
			}
		} catch (Exception $e) {
			return "0";
		}
	}

	public function post_dorm(
		$id,
		$userref,
		$name,
		$address,
		$longitude,
		$latitude,
		$price,
		$payduration, 
		$paypolicy,
		$slots,
		$desc,
		$hei,
		$dormImages,
		$visitors,
		$pets,
		$curfew,
		$advdep,
		$secdep,
		$util,
		$minstay,
		$aircon,
		$elevator,
		$beddings,
		$kitchen,
		$laundry,
		$lounge,
		$parking,
		$security,
		$studyRoom,
		$wifi
		){
		$dormquery = "INSERT INTO tbl_dorms SET id=?, userref=?, `name`=?, `address`=?, longitude=?, latitude=?, price=?, payment_duration=?, payment_policy=?, slots=?, `desc`=?, hei=?, images=?, visitors=?, pets=?, curfew=?, adv_dep=?, sec_dep=?, util=?, min_stay=?, createdAt=now(), updatedAt=now()";
		$dormparams = [$id, $userref, $name, $address, $longitude, $latitude, $price, $payduration, $paypolicy, $slots, $desc, $hei, $dormImages, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay];

		$amenitiesQuery = "INSERT INTO tbl_amenities SET dormref=?, aircon=?, elevator=?, beddings=?, kitchen=?, laundry=?, lounge=?, parking=?, security=?, study_room=?, wifi=?";
    $amenitiesParams = [$id, $aircon, $elevator, $beddings, $kitchen, $laundry, $lounge, $parking, $security, $studyRoom, $wifi];

		try {
			if ($this->QuickFire($dormquery, $dormparams)) {
				if ($this->quickFire($amenitiesQuery, $amenitiesParams)) {
					return "1";
				}
			}
		} catch (Exception $e) {
			return "0";
		}
	}

	public function get_verification_status($userref) {
		$out = json_decode(json_decode($this->QuickLook("SELECT is_verified FROM tbl_users WHERE id=?", [$userref]), true), true);
		return json_encode(json_encode($out[0]));
	}

  public function forgot_password($email, $forgotpass) {
		if ($this->QuickFire("UPDATE tbl_users SET is_forgot=1,unique_forgot= ?, updated_at=now() WHERE identifier=?", [$forgotpass, $email])) {
			return "1";
		}
	}

	public function payment($id, $token, $userref, $ownerref, $ownername, $dormref, $amount, $payment_duration, $chatroom_code) {
	  $vtitle = "StudyHive";
		$vdesc = "Received payment from {$token} for {$ownername} with amount: ₱{$amount}";
		$vreferencestarter = uniqid();
		$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
		$current_time = date('Y-m-d H:i:s');
	
		if ($this->QuickFire("INSERT INTO tbl_transactions SET `id`=?, token=?, userref=?, ownerref=?, ownername=?, dormref=?, amount=?", [$id, $token, $userref, $ownerref, $ownername, $dormref, $amount])) {
		  if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",[$ownerref, $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time])) {
			    if($payment_duration == "monthly") {
			        $date = date('Y-m-d', strtotime("+1 month"));
			    } else if($payment_duration == "quarterly") {
			        $date = date('Y-m-d', strtotime("+4 months"));
			    } else if($payment_duration == "annually") {
			        $date = date('Y-m-d', strtotime("+12 months"));
			    }
			  $this->QuickFire("UPDATE tbl_dorms SET who_own_this_dorm=?, paid_time=now(), end_time=? WHERE id=?", [$userref, $date, $dormref]);
			  $this->QuickFire("UPDATE tbl_chatrooms SET pay_rent = 2 WHERE chatroom_code = ?", [$chatroom_code]);
				$this->push_notification($vtitle, $vdesc, $userref);
				$this->push_notification($vtitle, $vdesc, $ownerref);
				
				$out = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE id=?", [$userref], true));
				$outx = json_decode($out, true);
				
				$out1 = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE id=?", [$ownerref], true));
				$out1x = json_decode($out1, true);
		
				$subject = "StudyHive Detailed Payment";
				$body = '
					<!DOCTYPE html>
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
																		Payment Detailed Receipt
																		<table style="width:100%">
																				<thead>
																						<tr>
																								<td>Name</td>
																								<td>'.$outx[0]['username'].'</td>
																						</tr>
																				</thead>
																				<tbody>
																						<tr>
																								<td>Paid Time</td>
																								<td>'.date("M d, Y h:iA", time()).'</td>
																						</tr>
																						<tr>
																								<td>End Time</td>
																								<td>'.$date.'</td>
																						</tr>
																						<tr>
																								<td>Total</td>
																								<td>'.$amount.'</td>
																						</tr>
																				</tbody>
																		</table>
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
					</html>
				';
				$altBody = "
					StudyHive Detailed Payment
				";

				sendEmail($out1x[0]['identifier'], $subject, $body, $altBody);
				sendEmail($outx[0]['identifier'], $subject, $body, $altBody);
				return "1";
		  }
		}
	}

	public function get_transactions($userref, $isowner) {
		$column = $isowner == 'true' ? 't.ownerref' : 't.userref';
		$transactions = json_decode(json_decode($this->QuickLook("SELECT t.*, d.images FROM tbl_transactions t JOIN tbl_dorms d ON t.dormref = d.id WHERE {$column}=? ORDER BY t.timestamp DESC", [$userref]), true), true);
		return json_encode(json_encode($transactions));
	}

	// DB Actions
	public function QuickLook($q, $par = array()) {
		$q = $this->c->prepare($q);
		if ($q->execute($par)) {
			return json_encode(json_encode($q->fetchall(PDO::FETCH_ASSOC)));
		} else {
			return $q->errorInfo();
		}
	}

	public function QuickFire($q, $par = array()) {
		$q = $this->c->prepare($q);
		if ($q->execute($par)) {
			return json_encode(json_encode(array("true")));
		} else {
			return json_encode(json_encode(array("false")));
		}
	}
}
