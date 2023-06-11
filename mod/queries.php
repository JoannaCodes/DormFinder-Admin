<?php
class sdm_query
{
	private $c;
	public function __construct($db)
	{
		$this->c = $db;
	}

	// Admin Queries
	public function change_status($btn_value) {
		if($btn_value == "1") {
			$vtitle = "DormFinder";
			$vdesc = "Your documents have been verified! You can now publish your Dorm.";
			$vreferencestarter = "1";
			$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
			$current_time = date('Y-m-d H:i:s');
			if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",["1122", $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time]
			)) {
				if ($this->QuickFire("UPDATE tbl_documents SET doc1_status=? WHERE user_id='1122'",[$btn_value])) {
					return "1";
				}
			}
		} else if ($btn_value == "2") {
			$vtitle = "DormFinder";
			$vdesc = "Your documents have not been verified! Please upload a new document.";
			$vreferencestarter = "1";
			$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
			$current_time = date('Y-m-d H:i:s');
			if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",["1122", $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time]
			)) {
				if ($this->QuickFire("DELETE FROM tbl_documents WHERE user_id='1122'",[])) {
					return "0";
				}
			}
			
		}
	}
	public function open_document($user_id) {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_documents WHERE user_id=?", [$user_id]), true), true);
		$toecho = "";
		$doc_status = "";
		for ($i = 0; $i < count($out); $i++) {
			$toecho.="<div class='d-flex mb-3'>
						<label class='align-self-center flex-grow-1'>".$out[$i]['doc_1']."</label>
						<a data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Download' href='http://localhost/dormfinder_php/uploads/" . $out[$i]['doc_1'] . "' download='" . $out[$i]['doc_1'] . "' class='btn btn-transparent text-primary p-0 type='button'>Download <i class='fa-light fa-file-arrow-down fa-fw fa-lg'></i></a>
					</div>";
		}
		return $toecho;
	}
	public function send_document($target_file,$target_file2)
	{
		if ($this->QuickFire("INSERT INTO tbl_documents SET doc_1=?, doc1_status=?, user_id=?",[$target_file, "0", "1122"])) {
			if ($this->QuickFire("INSERT INTO tbl_documents SET doc_1=?, doc1_status=?, user_id=?",[$target_file2, "0", "1122"])) {
				return "1";
			}
		}
	}
	public function login_dormfinder($email, $password)
	{
		$out = json_decode($this->QuickLook("SELECT * FROM tbl_adminusers WHERE email=? AND password=?", [$email, $password], true));
		$outx = json_decode($out, true);
		if (count($outx) == 1) {
			if ($outx[0]['password'] == $password) {
				echo json_encode(["email" => $outx[0]['email'], "id" => $outx[0]['id'], "status" => "true"]);
			} else {
				echo json_encode(["email" => "none", "id" => "none", "status" => "false"]);
			}
		}
	}
	public function verify_document($id, $docvalue)
	{
		$vtitle = "DormFinder";
		$vdesc = "Your documents have been verified! You can now publish your Dorm.";
		$vreferencestarter = "1";
		$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
		$current_time = date('Y-m-d H:i:s');
		if ($this->QuickFire(
			"INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",
			["1122", $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time]
		)) {
			if ($this->QuickFire(
				"UPDATE tbl_documents SET doc1_status=? WHERE id=?",
				[$docvalue, $id]
			)) {
				return "1";
			}
		}
	}
	public function get_submitdocuments()
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_documents GROUP BY user_id", []), true), true);
		$toecho = "";
		$doc_status = "";
		for ($i = 0; $i < count($out); $i++) {
			switch ($out[$i]['doc1_status']) {
				case '0':
					$doc_status = "Unverified";
				break;
				case '1':
					$doc_status = "Verified";
				break;
			}
			$toecho .= "<tr>
				<td class='align-middle'><button class='btn btn-link text-primary' data-user_id='".$out[$i]['user_id']."' data-doc_status='".$out[$i]['doc1_status']."' onclick='open_userdoc(this)'>" . $out[$i]['user_id'] . "</button></td>
				<td class='align-middle'>" . $doc_status . "</td>
	        </tr>";
		}
		return $toecho;
	}
	public function get_dormlisting()
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms", []), true), true);
		$toecho = "";
		$del_icon="<i class='fa-light fa-trash fa-fw fa-lg'></i>";
		$notif_icon="<i class='fa-light fa-bell fa-fw fa-lg'></i>";
		for ($i = 0; $i < count($out); $i++) {
			$toecho .= "<tr>
				<td class='align-middle'>".$out[$i]['id']."</td>
				<td class='align-middle'>".$out[$i]['userref']."</td>
				<td class='align-middle'>".$out[$i]['name']."</td>
				<td class='align-middle'>".$out[$i]['address']."</td>
				<td class='align-middle'>".$out[$i]['createdAt']."</td>
				<td class='align-middle'>".$out[$i]['updatedAt']."</td>
				<td class='align-middle'>
					<button class='btn btn-primary p-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Send Update Notification' onclick='send_dorm_notif(this)' data-dormref='".$out[$i]['id']."' data-userref='".$out[$i]['userref']."'>".$notif_icon."</button>
					<button class='btn btn-danger p-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete Dorm Listing' onclick='delete_dorm_admin(this)' data-dormref='".$out[$i]['id']."' data-userref='".$out[$i]['userref']."'>".$del_icon."</button>
				</td>
	  	</tr>";
		}
		return $toecho;
	}
	public function get_users()
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_users", []), true), true);
		$toecho = "";
		for ($i = 0; $i < count($out); $i++) {
			$toecho .= "<tr>
				<td class='align-middle'>".$out[$i]['id']."</td>
				<td class='align-middle'>".$out[$i]['username']."</td>
				<td width='25%' class='align-middle'>".$out[$i]['is_verified']."</td>
	  	</tr>";
		}
		return $toecho;
	}
	public function send_custom_notif($userref, $message)
	{
		$vtitle = "UniHive";
		$vdesc = $message;
		$vreferencestarter = uniqid();
		$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
		$current_time = date('Y-m-d H:i:s');

		$out = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE id=?", [$userref], true));
		$outx = json_decode($out, true);
		if (count($outx) == 1) {
			if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",[$userref, $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time])) {
				return "1";
			}
		} else {
			return "0";
		}
	}
	public function new_admin($email, $password)
	{
		if ($this->QuickFire("INSERT INTO tbl_adminusers SET email=?, `password`=?, date_created=now()", [$email, $password])){
			return "1";
		}
	}
	public function delete_dorm_admin($userref, $dormref)
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT `name` FROM tbl_dorms WHERE id=? AND userref=?", [$dormref, $userref]), true), true);

		$vtitle = "UniHive";
		$vdesc = "We would like to notify you that your listing named '" . $out[0]['name'] . "', has been removed. If you believe this removal was a mistake, kindly reach out to our team for assistance.";
		$vreferencestarter = uniqid();
		$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
		$current_time = date('Y-m-d H:i:s');

		if ($this->QuickFire("DELETE FROM `tbl_dorms` WHERE id=?", [$dormref])) {
			if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",[$userref, $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time])) {
				return "1";
			}
		}
	}
	public function send_dorm_notif($userref, $dormref)
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT name FROM tbl_dorms WHERE id=? AND userref=?", [$dormref, $userref]), true), true);

		$vtitle = "UniHive";
		$vdesc = "This is a reminder to update your listing named '" . $out[0]['name'] . "', for the upcoming semester. Please ensure your listing is up to date. If your listing is already current, you may disregard this message.";
		$vreferencestarter = uniqid();
		$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
		$current_time = date('Y-m-d H:i:s');
		if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",[$userref, $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time])) {
			return "1";
		}
	}

	// App Queries
	public function login_app($username, $password)
	{
		$out = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE username=? AND `password`=?", [$username, $password], true));
		$outx = json_decode($out, true);
		if (count($outx) == 1) {
			if ($outx[0]['password'] == $password && $outx[0]['username'] == $username) {
				echo json_encode(["username" => $outx[0]['username'], "id" => $outx[0]['id'], "status" => true]);
			} else {
				echo json_encode(["status" => false]);
			}
		}
	}
	public function signup_app($email, $username, $password)
	{
		$id = uniqid();
		if ($this->QuickFire("INSERT INTO tbl_users SET id=? , identifier=?, username=?, password=?, updated_at=now(), created_at=now()", [$id, $email, $username, $password])) {
			return "1";
		}
	}
  public function clearallnotif($userref) 
  {
		if ($this->QuickFire("DELETE FROM tbl_notifications WHERE user_ref=?",[$userref])) {
			return "0";
		}
	}
  public function look_morepastnotif($userref, $idstoftech)
	{
		$thiday = date("Y-m-d H:i:s");
		$tofetch = "";
		$allid = explode(",", $idstoftech);
		$cleanedallid = array();



		for ($i = 0; $i < count($allid); $i++) {
			if ($allid[$i] != "" && $allid[$i] != null) {
				array_push($cleanedallid,  $allid[$i]);
			}
		}
		$allid = 	$cleanedallid;

		if (count($allid) != 0) {
			$tofetch .= " AND (";
		}

		for ($i = 0; $i < count($allid); $i++) {
			if ($allid[$i] != "" && $allid[$i] != null) {
				$tofetch .= " id != '" . $allid[$i] . "'";


				if (($i + 1) < count($allid)) {
					$tofetch .= " AND ";
				}
			}
		}
		$lastvalue = "";
		if (count($allid) != 0) {

			$tofetch .= ")";
		}

		$q = "SELECT * FROM tbl_notifications WHERE
		user_ref=? AND
		scheduled <= ? " . $tofetch . " ORDER BY scheduled DESC LIMIT 25";
		$out = json_decode(json_decode($this->QuickLook($q, [$userref, $thiday]), true), true);

		for ($i = 0; $i < count($out); $i++) {

			$out[$i]["scheduled"] = $this->DateExplainer($out[$i]["scheduled"])   . ";" . date("F d, Y g:i a", strtotime($out[$i]["scheduled"]));
		}

		return json_encode(json_encode($out));
	}
	public function look_passednotifications($userref)
	{
		$thiday = date("Y-m-d H:i:s");
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_notifications WHERE
		user_ref=? AND
		scheduled <= ? ORDER BY scheduled DESC LIMIT 8", [$userref, $thiday]), true), true);

		for ($i = 0; $i < count($out); $i++) {
			$out[$i]["scheduled"] = $this->DateExplainer($out[$i]["scheduled"])   . ";" . date("F d, Y g:i a", strtotime($out[$i]["scheduled"]));
		}

		return json_encode(json_encode($out));
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
	public function look_usersavednotifs()
	{
		$currdate = date("Y-m-d H:i:s");
		return $this->QuickLook("SELECT *, UNIX_TIMESTAMP(scheduled) as unix_time FROM tbl_notifications WHERE scheduled>?", [$currdate]);
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
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms WHERE id=?", [$dormref]), true), true);
		return json_encode(json_encode($out[0]));
	}
	public function get_bookmarks($userref)
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms d INNER JOIN tbl_bookmarks b ON d.id = b.dormref WHERE b.userref=?", [$userref]), true), true);
		return json_encode(json_encode($out));
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
			return "1";
		}
	}
	public function get_reviews($dormref)
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT u.id, u.username, u.imageUrl, r.rating, r.comment, r.createdAt FROM tbl_users u INNER JOIN tbl_dormreviews r ON u.id = r.userref WHERE dormref=?", [$dormref]), true), true);
		return json_encode(json_encode($out));
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
	public function update_dorm($dormref, $userref, $name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $amenities, $dormImages, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay)
	{
		$dormquery = empty($dormImages) ?
    	"UPDATE tbl_dorms SET `name`=?, `address`=?, longitude=?, latitude=?, price=?, slots=?, `desc`=?, hei=?, amenities=?, visitors=?, pets=?, curfew=?, adv_dep=?, sec_dep=?, util=?, min_stay=?, updatedAt=now() WHERE id=? AND userref=?" :
    	"UPDATE tbl_dorms SET `name`=?, `address`=?, longitude=?, latitude=?, price=?, slots=?, `desc`=?, hei=?, amenities=?, images=?, visitors=?, pets=?, curfew=?, adv_dep=?, sec_dep=?, util=?, min_stay=?, updatedAt=now() WHERE id=? AND userref=?";

		$dormparams = empty($dormImages) ?
			[$name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $amenities, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay, $dormref, $userref] :
			[$name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $amenities, $dormImages, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay, $dormref, $userref];


		try {
			if ($this->QuickFire($dormquery, $dormparams)) {
				return "1";
			} else {
				return "0";
			}

			return "1";
		} catch (Exception $e) {
			return "0";
		}
	}
	public function post_dorm($id, $userref, $name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $amenities, $dormImages, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay)
	{
		$dormquery = "INSERT INTO tbl_dorms SET id=?, userref=?, `name`=?, `address`=?, longitude=?, latitude=?, price=?, slots=?, `desc`=?, hei=?, amenities=?, images=?, visitors=?, pets=?, curfew=?, adv_dep=?, sec_dep=?, util=?, min_stay=?, createdAt=now(), updatedAt=now()";
		$dormparams = [$id, $userref, $name, $address, $longitude, $latitude, $price, $slots, $desc, $hei, $amenities, $dormImages, $visitors, $pets, $curfew, $advdep, $secdep, $util, $minstay];

		try {
			if ($this->QuickFire($dormquery, $dormparams)) {
				return "1";
			} else {
				return "0";
			}
			return "success";
		} catch (Exception $e) {
			return "failed";
		}
	}

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
