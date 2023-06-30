<?php
include_once "functions.php";

class sdm_query
{
	private $c;
	public function __construct($db)
	{
		$this->c = $db;
	}

	public function check_ifsubmitted($user_id) 
	{
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
	public function send_document($target_file,$target_file2,$user_id)
	{
		if ($this->QuickFire("INSERT INTO tbl_documents SET doc_1=?, doc1_status=?, user_id=?",[$target_file, "0", $user_id])) {
			if ($this->QuickFire("INSERT INTO tbl_documents SET doc_1=?, doc1_status=?, user_id=?",[$target_file2, "0", $user_id])) {
				return "1";
			}
		}
	}
	public function login_app($username, $password, $fcm)
	{
		$out = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE username=? OR identifier=? AND password=?", [$username, $username, $password], true));
		$outx = json_decode($out, true);

		if (count($outx) == 1) {
			if (($outx[0]["username"] == $username || $outx[0]["identifier"] == $username)) {
				if ($outx[0]["password"] == $password) {
					echo json_encode(["username" => $outx[0]['username'], "id" => $outx[0]['id'], "status" => true, "mode" => "user"]);
					
					$dtnow = date("Y-m-d H:i:s");
				    $this->QuickFire("INSERT INTO tbl_notif_fcmkeys SET user_ref=?, fcm_key=?, created_at=?",[$outx[0]['id'],$fcm,$dtnow]);
				} else {
					echo json_encode(["status" => false]);
			}
		}
	  }
	}
	public function signup_app($email, $username, $password)
	{
        $result = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE username = ? AND identifier = ?", [$username, $email], true));
        $existingUser = json_decode($result, true);
    
        if (empty($existingUser)) {
    		$id = uniqid();
    		if ($this->QuickFire("INSERT INTO tbl_users SET id=?, identifier=?, username=?, password=?, updated_at=now(), created_at=now()", [$id, $email, $username, $password])) 
    		{
    			return "1";
    	    }
        } else {
    		return "0";
		}
	}
  public function clearallnotif($userref) 
  {
		if ($this->QuickFire("DELETE FROM tbl_notifications WHERE user_ref=?",[$userref])) {
			return "0";
		}
	}
	public function DateExplainer($thedate)
	{
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
	public function look_usersavednotifs($user_ref)
	{
		$currdate = date("Y-m-d H:i:s");
		return $this->QuickLook("SELECT *, UNIX_TIMESTAMP(scheduled) as unix_time FROM tbl_notifications WHERE scheduled>? AND user_ref=?", [$currdate,$user_ref]);
	}
	public function look_passednotifications($userref)
	{
		$thiday = date("Y-m-d");
		return $this->QuickLook("SELECT DATE_FORMAT(scheduled, '%b %e, %Y %l:%i %p') AS formatted_date, ndesc, title  FROM tbl_notifications WHERE
		user_ref=? ORDER BY id DESC", [$userref]);
	}
  public function get_account($id)
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE id=?", [$id]), true), true);
		if (count($out) == 1) {
			return json_encode(json_encode($out[0]));
		}
	}
	public function update_account($id, $identifier)
	{
		if ($this->QuickFire("UPDATE tbl_users SET identifier=?, updated_at=now() WHERE id=?", [$identifier, $id])) {
			return "1";
		}
	}
	public function delete_account($id)
	{
		if ($this->QuickFire("DELETE FROM tbl_users WHERE id=?", [$id])) {
		    $dorm = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms WHERE id=?", [$id]), true), true);
		    $dormImagesPath = 'uploads/dormImages/' . $dorm[0]['id'] . '/';
		    
		    if (is_dir($dormImagesPath)) {
                deleteDirectory($dormImagesPath);
            } else {
               echo json_encode(["message" => "Folder does not exist"]);
            }

			return "1";
		}
	}
	public function change_password($id, $currentpassword, $newpassword)
	{
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
	public function get_dorms($userref)
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms WHERE userref=?", [$userref]), true), true);
		return json_encode(json_encode($out));
	}
	public function get_dorm_details($dormref)
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT d.*, a.* FROM tbl_dorms d JOIN tbl_amenities a ON d.id = a.dormref WHERE d.id=?", [$dormref]), true), true);
		return json_encode(json_encode($out[0]));
	}
	public function get_bookmarks($userref)
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT d.* FROM tbl_dorms d INNER JOIN tbl_bookmarks b ON d.id = b.dormref WHERE b.userref=?", [$userref]), true), true);
		return json_encode(json_encode($out));
	}
	public function add_bookmarks($userref, $dormref)
	{
		if ($this->QuickFire("INSERT INTO tbl_bookmarks SET dormref=?, userref=?", [$dormref, $userref])) {
			return "1";
		}
	}
	public function update_profile($userref, $username, $filename)
	{
		if ($this->QuickFire("UPDATE tbl_users SET username=?, imageUrl=?, updated_at=now() WHERE id=?", [$username, $filename, $userref])) {
			return "1";
		}
	}
	public function delete_bookmark($dormref, $userref)
	{
		if ($this->QuickFire("DELETE FROM `tbl_bookmarks` WHERE dormref=? AND userref=?", [$dormref, $userref])) {
			return "1";
		}
	}
	public function post_review($dormref, $userref, $rating, $comment)
	{
		if ($this->QuickFire("INSERT INTO tbl_dormreviews SET dormref=?, userref=?, rating=?, comment=?, createdAt=now()", [$dormref, $userref, $rating, $comment])) {
		    if ($this->QuickLook("UPDATE tbl_transactions SET has_reviewed=? WHERE userref=? AND dormref=?", [1, $userref, $dormref])) {
		        return "1";
		    }
		}
	}
	public function get_reviews($dormref)
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT u.id, u.username, u.imageUrl, r.rating, r.comment, r.createdAt FROM tbl_users u INNER JOIN tbl_dormreviews r ON u.id = r.userref WHERE dormref=?", [$dormref]), true), true);
		return json_encode(json_encode($out));
	}
	public function post_report($id, $dormref, $userref, $comment)
	{
		if ($this->QuickFire("INSERT INTO tbl_dormreports SET dormref=?, userref=?, comment=?, createdAt=now()", [$dormref, $userref, $comment])) {
			return "1";
		}
	}
	public function delete_dorm($dormref, $userref)
	{
		if ($this->QuickFire("DELETE FROM `tbl_dorms` WHERE id=? AND userref=?", [$dormref, $userref])) {
			return "1";
		}
	}
	public function popular_dorm()
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms d WHERE d.id IN (SELECT r.dormref FROM tbl_dormreviews r WHERE r.rating>=3)", []), true), true);
		return json_encode(json_encode($out));
	}
	public function latest_dorm()
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms ORDER BY createdAt DESC LIMIT 50", []), true), true);
		return json_encode(json_encode($out));
	}
	public function nearest_dorm($latitude, $longitude)
	{
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
				? "UPDATE tbl_dorms SET `name`=?, `address`=?, longitude=?, latitude=?, price=?, slots=?, `desc`=?, hei=?, visitors=?, pets=?, curfew=?, adv_dep=?, sec_dep=?, util=?, min_stay=?, updatedAt=NOW() WHERE id=? AND userref=?"
				: "UPDATE tbl_dorms SET `name`=?, `address`=?, longitude=?, latitude=?, price=?, slots=?, `desc`=?, hei=?, images=?, visitors=?, pets=?, curfew=?, adv_dep=?, sec_dep=?, util=?, min_stay=?, updatedAt=NOW() WHERE id=? AND userref=?";

		$dormParams = empty($dormImages)
				? [$name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $visitors, $pets, $curfew, $advDep, $secDep, $util, $minStay, $dormref, $userref]
				: [$name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $dormImages, $visitors, $pets, $curfew, $advDep, $secDep, $util, $minStay, $dormref, $userref];

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
		$dormquery = "INSERT INTO tbl_dorms SET id=?, userref=?, `name`=?, `address`=?, longitude=?, latitude=?, price=?, slots=?, `desc`=?, hei=?, images=?, visitors=?, pets=?, curfew=?, adv_dep=?, sec_dep=?, util=?, min_stay=?, createdAt=now(), updatedAt=now()";
		$dormparams = [$id, $userref, $name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $dormImages, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay];

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
	public function get_verification_status($userref) 
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT is_verified FROM tbl_users WHERE id=?", [$userref]), true), true);
		return json_encode(json_encode($out[0]));
	}
  	public function forgot_password($email, $password) {
		if ($this->QuickFire("UPDATE tbl_users SET `password`=?, updated_at=now() WHERE identifier=?", [$password, $email])) {
			return "1";
		}
	}
	public function payment($id, $token, $userref, $ownerref, $ownername, $dormref, $amount) {
	    $vtitle = "StudyHive";
		$vdesc = "Received payment from {$token} for {$ownername} with amount: ₱{$amount}";
		$vreferencestarter = uniqid();
		$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
		$current_time = date('Y-m-d H:i:s');
	
		if ($this->QuickFire("INSERT INTO tbl_transactions SET `id`=?, token=?, userref=?, ownerref=?, ownername=?, dormref=?, amount=?", [$id, $token, $userref, $ownerref, $ownername, $dormref, $amount])) 
		{
			if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",[$ownerref, $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time])) 
			{
				return "1";
			}
		}
	}
	public function get_transactions($userref) {
        $transactions = json_decode(json_decode($this->QuickLook("SELECT t.*, d.images FROM tbl_transactions t JOIN tbl_dorms d ON t.dormref = d.id WHERE t.userref=? ORDER BY t.timestamp DESC", [$userref]), true), true);
    
        return json_encode(json_encode($transactions));
    }

	// DB Actions
	public function QuickLook($q, $par = array())
	{
		$q = $this->c->prepare($q);
		if ($q->execute($par)) {
			return json_encode(json_encode($q->fetchall(PDO::FETCH_ASSOC)));
		} else {
			return $q->errorInfo();
		}
	}

	public function QuickFire($q, $par = array())
	{
		$q = $this->c->prepare($q);
		if ($q->execute($par)) {
			return json_encode(json_encode(array("true")));
		} else {
			return json_encode(json_encode(array("false")));
		}
	}
}
