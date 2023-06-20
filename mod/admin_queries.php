<?php
class admin_query
{
	private $c;
	public function __construct($db)
	{
		$this->c = $db;
	}

	public function change_status($btn_value,$user_id) {
		if($btn_value == "1") {
			$vtitle = "DormFinder";
			$vdesc = "Your documents have been verified! You can now publish your Dorm.";
			$vreferencestarter = "1";
			$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
			$current_time = date('Y-m-d H:i:s');
			if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",[$user_id, $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time]
			)) {
				if ($this->QuickFire("UPDATE tbl_documents SET doc1_status=? WHERE user_id=?",[$btn_value,$user_id])) {
					if ($this->QuickFire("UPDATE tbl_users SET is_verified=? WHERE id=?",[$btn_value,$user_id])) {
						return "1";
					}
				}
			}
		} else if ($btn_value == "2") {
			$vtitle = "DormFinder";
			$vdesc = "Your documents have not been verified! Please upload a new document.";
			$vreferencestarter = "1";
			$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
			$current_time = date('Y-m-d H:i:s');
			if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",[$user_id, $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time]
			)) {
				if ($this->QuickFire("DELETE FROM tbl_documents WHERE user_id=?",[$user_id])) {
					if ($this->QuickFire("UPDATE tbl_users SET is_verified=? WHERE id=?",[$btn_value,$user_id])) {
						return "0";
					}
				}
			}
			
		}
	}
	public function open_document($user_id) {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_documents WHERE user_id=?", [$user_id]), true), true);
		$toecho = "";
		$doc_status = "";
		for ($i = 0; $i < count($out); $i++) {
			$toecho.="
					<input type='hidden' id='user_id' value='".$out[$i]['user_id']."' />
					<div class='d-flex mb-3'>
						<label class='align-self-center flex-grow-1'>".$out[$i]['doc_1']."</label>
						<a data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Download' href='http://localhost/DormFinder-Admin/uploads/user/" .$out[$i]['user_id']. "/" . $out[$i]['doc_1'] . "' download='" . $out[$i]['doc_1'] . "' class='btn btn-transparent text-primary p-0 type='button'>Download <i class='fa-light fa-file-arrow-down fa-fw fa-lg'></i></a>
					</div>";
		}
		return $toecho;
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
	public function get_reports()
	{
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dormreports", []), true), true);
		$toecho = "";
		$del_icon="<i class='fa-light fa-trash fa-fw fa-lg'></i>";
		for ($i = 0; $i < count($out); $i++) {
			$toecho .= "<tr>
				<td class='align-middle'>".$out[$i]['id']."</td>
				<td class='align-middle'>".$out[$i]['userref']."</td>
				<td class='align-middle'>".$out[$i]['dormref']."</td>
				<td class='align-middle'>".$out[$i]['comment']."</td>
				<td class='align-middle'>".$out[$i]['createdAt']."</td>
				<td class='align-middle'>
					<button class='btn btn-danger p-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Resolve Report' onclick='resolve_report_admin(this)' data-reportid='".$out[$i]['id']."'>".$del_icon."</button>
				</td>
	  	</tr>";
		}
		return $toecho;
	}
	public function resolve_dorm($reportid)
	{
		if ($this->QuickFire("DELETE FROM `tbl_dormreports` WHERE id=?", [$reportid])) {
			return "1";
		}
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
