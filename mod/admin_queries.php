<?php
session_start();
class admin_query
{
	private $c;
	public function __construct($db) {
		$this->c = $db;
	}
	
	public function _validateXSS($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);

		return $data;
	}

	public function push_notification($title, $message, $userref) {
		// destination is either FCM Device Key or Topic
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_notif_fcmkeys WHERE user_ref=? GROUP BY fcm_key",[$userref]),true),true);
		$destination = "";

		$fcm_array=array();

		for ($i=0; $i<count($out); $i++) {
			array_push($fcm_array,$out[$i]['fcm_key']);
		}
		$fields = array(
		  // 'to'  => $destination,
			'registration_ids' => $fcm_array,
		    'priority' => 'high',
		    'notification' => array(
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

	public function change_status($btn_value,$user_id) {
		if($btn_value == "1") {
			$vtitle = "StudyHive";
			$vdesc = "Your documents have been verified! You can now publish your Dorm.";
			$vreferencestarter = uniqid();
			$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
			$current_time = date('Y-m-d H:i:s');
			if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",[$user_id, $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time]
			)) {
				if($this->push_notification($vtitle, $vdesc, $user_id)) {
					if ($this->QuickFire("UPDATE tbl_documents SET doc1_status=? WHERE user_id=?",[$btn_value,$user_id])) {
						if ($this->QuickFire("UPDATE tbl_users SET is_verified=? WHERE id=?",[$btn_value,$user_id])) {
							return "1";
						}
					}
				}
			}
		} else if ($btn_value == "2") {
			$vtitle = "StudyHive";
			$vdesc = "Your documents have not been verified! Please upload a new document.";
			$vreferencestarter = uniqid();
			$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
			$current_time = date('Y-m-d H:i:s');
			if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",[$user_id, $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time])) {
				if($this->push_notification($vtitle, $vdesc, $user_id)) {
					if ($this->QuickFire("UPDATE tbl_users SET is_verified=? WHERE id=?",["0",$user_id])) {
							if ($this->QuickFire("DELETE FROM tbl_documents WHERE user_id=?",[$user_id])) {
							return "0";
						}
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
					<a data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Download' href='https://studyhive.social/uploads/userDocs/" .$out[$i]['user_id']. "/" . $out[$i]['doc_1'] . "' download='" . $out[$i]['doc_1'] . "' class='btn btn-transparent text-primary p-0 type='button'>Download <i class='fa-light fa-file-arrow-down fa-fw fa-lg'></i></a>
				</div>
			";
		}
		return $toecho;
	}

	public function login_dormfinder($email, $password) {
		$out = json_decode($this->QuickLook("SELECT * FROM tbl_adminusers WHERE email=? AND password=?", [$email, $password], true));
		$outx = json_decode($out, true);
		if (count($outx) == 1) {
			if ($outx[0]['password'] == $password && $outx[0]['email'] == $email) {
				echo json_encode(["email" => $outx[0]['email'], "id" => $outx[0]['id'], "status" => "true"]);
              	
				// SESSION
				session_regenerate_id();
				$_SESSION['id'] = $outx[0]['id'];
				$_SESSION['name'] = explode("@",$outx[0]['email'])[0];
				session_write_close();
			} else {
				echo json_encode(["email" => "none", "id" => "none", "status" => "false"]);
			}
		} else {
		    echo json_encode(["email" => "none", "id" => "none", "status" => "false"]);
		}
	}

	public function get_submitdocuments() {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_documents GROUP BY user_id", []), true), true);
		$toecho = "";
		$doc_status = "";
		for ($i = 0; $i < count($out); $i++) {
			switch ($out[$i]['doc1_status']) {
				case '0':
					$doc_status = "Unverified";
					$style = "bg-danger";
				break;
				case '1':
					$doc_status = "Verified";
					$style = "bg-success";
				break;
			}
			$toecho .= "
				<tr>
					<td class='align-middle'><button class='btn btn-link text-primary' data-user_id='".$out[$i]['user_id']."' data-doc_status='".$out[$i]['doc1_status']."' onclick='open_userdoc(this)'>" . $out[$i]['user_id'] . "</button></td>
					<td class='align-middle'><span class='badge ".$style."'>" . $this->_validateXSS($doc_status) . "</span></td>
	      </tr>
			";
		}
		return $toecho;
	}

	public function get_dormlisting() {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms", []), true), true);
		$toecho = "";
		$del_icon="<i class='fa-light fa-trash fa-fw fa-lg'></i>";
		$notif_icon="<i class='fa-light fa-bell fa-fw fa-lg'></i>";
		$hide_icon="<i class='fa-light fa-eye-slash fa-fw fa-lg'></i>";
		$show_icon="<i class='fa-light fa-eye-slash fa-fw fa-lg'></i>";
		for ($i = 0; $i < count($out); $i++) {
		    switch ($out[$i]['hide']) {
				case '0':
					$display = "<span class='badge bg-success'>Active</span>";
					$style = "text-success";
				break;
				case '1':
					$display = "<span class='badge bg-info'>Hidden</span>";
					$style = "text-info";
				break;
			}
			$toecho .= "
				<tr>
					<td>
						<div class='form-check'>
							<input class='form-check-input' type='checkbox' value='".$out[$i]['name']."' id='checkbox' data-dormref='".$out[$i]['id']."' data-userref='".$out[$i]['userref']."'>
						</div>
					</td>
					<td class='align-middle'>".$out[$i]['id']."</td>
					<td class='align-middle'>".$out[$i]['userref']."</td>
					<td class='align-middle'>".$this->_validateXSS($out[$i]['name'])."</td>
					<td class='align-middle'>".$this->_validateXSS($out[$i]['address'])."</td>
					<td class='align-middle'>".$out[$i]['createdAt']."</td>
					<td class='align-middle'>".$out[$i]['updatedAt']."</td>
					<td class='align-middle ".$style."'>".$display."</td>
				</tr>
			";
		}
		return $toecho;
	}

	public function get_duedatelisting() {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms WHERE who_own_this_dorm != ''", []), true), true);
		$toecho = "";
		if(count($out) == 0) {
		    $toecho .= "
				<tr>
					<td class='align-middle' colspan='6'>No results found</td>
				</tr>
			";
	  	return $toecho;
		}
		for ($i = 0; $i < count($out); $i++) {
			$toecho .= "
				<tr>
					<td class='align-middle'>".$out[$i]['id']."</td>
					<td class='align-middle'>".$out[$i]['userref']."</td>
					<td class='align-middle'>".$this->_validateXSS($out[$i]['name'])."</td>
					<td class='align-middle'>".$this->_validateXSS($out[$i]['address'])."</td>
					<td class='align-middle'>".$out[$i]['paid_time']."</td>
					<td class='align-middle'>".$out[$i]['end_time']."</td>
				</tr>
			";
		}
		return $toecho;
	}

	public function get_report_dormlisting() {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dorms", []), true), true);
		$toecho = "";
		$del_icon="<i class='fa-light fa-trash fa-fw fa-lg'></i>";
		$notif_icon="<i class='fa-light fa-bell fa-fw fa-lg'></i>";
		$hide_icon="<i class='fa-light fa-eye-slash fa-fw fa-lg'></i>";
		$show_icon="<i class='fa-light fa-eye-slash fa-fw fa-lg'></i>";
		for ($i = 0; $i < count($out); $i++) {
			$outs = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE `id` = ?", [$out[$i]['userref']]), true), true);
			$toecho .= "
				<tr>
					<td class='align-middle'>".$out[$i]['id']."</td>
					<td class='align-middle'>".$outs[0]['username']."</td>
					<td class='align-middle'>".$this->_validateXSS($out[$i]['name'])."</td>
					<td class='align-middle'>".$this->_validateXSS($out[$i]['address'])."</td>
					<td class='align-middle'>".$out[$i]['createdAt']."</td>
					<td class='align-middle'>".($out[$i]['who_own_this_dorm'] != '' ? "<span class='badge bg-danger'>Unavailable</span>" : "<span class='badge bg-success'>Available</span>")."</td>
				</tr>
			";
		}
		return $toecho;
	}

	public function get_users() {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_users", []), true), true);
		$toecho = "";
		for ($i = 0; $i < count($out); $i++) {
		    switch ($out[$i]['is_verified']) {
				case '0':
					$status = "<span class='badge bg-danger'>Unverified</span>";
				break;
				case '1':
					$status = "<span class='badge bg-success'>Verified</span>";
				break;
			}
			$toecho .= "
				<tr>
					<td class='align-middle'>".$out[$i]['id']."</td>
					<td class='align-middle'>".$this->_validateXSS($out[$i]['username'])."</td>
					<td width='25%' class='align-middle'>{$status}</td>
				</tr>
			";
		}
		return $toecho;
	}

	public function get_adminusers() {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_adminusers", []), true), true);
		$toecho = "";
		for ($i = 0; $i < count($out); $i++) {
		    $del_icon="<i class='fa-light fa-trash fa-fw fa-lg'></i>";
			$toecho .= "<tr>
				<td class='align-middle'>".$out[$i]['id']."</td>
				<td class='align-middle'>".$this->_validateXSS($out[$i]['email'])."</td>
				<td width='25%' class='align-middle'>".$out[$i]['date_created']."</td>
				<td class='align-middle'>
				    <button class='btn btn-danger p-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete Admin User' onclick='delete_admin_user(this)' data-id='".$out[$i]['id']."'>".$del_icon."</button>
				</td>
	  	</tr>";
		}
		return $toecho;
	}

	public function send_custom_notif($userref, $message) {
		$vtitle = "StudyHive";
		$vdesc = $message;
		$vreferencestarter = uniqid();
		$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
		$current_time = date('Y-m-d H:i:s');

		$out = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE id=?", [$userref], true));
		$outx = json_decode($out, true);
	    if (count($outx) == 1) {
		    if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",[$userref, $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time])) {
    		    if($this->push_notification($vtitle, $vdesc, $userref)) {
    				return "1";
    			}
		    }
		} else {
			return "0";
		}
	}

	public function new_admin($email, $password) {
	    $out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_adminusers WHERE email = ?", [$email]), true), true);
	    if (count($out) == 0) {
    		if ($this->QuickFire("INSERT INTO tbl_adminusers SET email=?, `password`=?, date_created=now()", [$email, $password])){
    			return "1";
    		} else {
    		    return "0";
    		}
	    } else {
	        return "1";
	    }
	}

	public function delete_dorm_admin($items) {
	    foreach ($items as $item) {
        $userref = $item["userref"];
        $dormref = $item["dormref"];
				$successCount = 0;
            
        $out = json_decode(json_decode($this->QuickLook("SELECT `name` FROM tbl_dorms WHERE id=? AND userref=?", [$dormref, $userref]), true), true);

    		$vtitle = "StudyHive";
    		$vdesc = "We would like to notify you that your listing named '" . $out[0]['name'] . "', has been removed. If you believe this removal was a mistake, kindly reach out to our team for assistance.";
    		$vreferencestarter = uniqid();
    		$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
    		$current_time = date('Y-m-d H:i:s');
    
    		if ($this->QuickFire("DELETE FROM `tbl_dorms` WHERE id=?", [$dormref])) {
					if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",[$userref, $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time])) {
							if($this->push_notification($vtitle, $vdesc, $userref)) {
								$successCount++;
						}
					}
    		}
	    }
	    
	    if ($successCount > 0 && $successCount == count($items)) {
				return "1";
			}
	}

	public function send_dorm_notif($items) {
	    foreach ($items as $item) {
				$userref = $item["userref"];
				$dormref = $item["dormref"];
				$successCount = 0;
				
				$out = json_decode(json_decode($this->QuickLook("SELECT name FROM tbl_dorms WHERE id=? AND userref=?", [$dormref, $userref]), true), true);
		
				$vtitle = "StudyHive";
				$vdesc = "This is a reminder to update your listing named '" . $out[0]['name'] . "', for the upcoming semester. Please ensure your listing is up to date. If your listing is already current, you may disregard this message.";
				$vreferencestarter = uniqid();
				$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
				$current_time = date('Y-m-d H:i:s');
		
				$out = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE id=?", [$userref], true));
				$outx = json_decode($out, true);
				if (count($outx) == 1) {
					if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?", [$userref, $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time])) {
						if ($this->push_notification($vtitle, $vdesc, $userref)) {
							$successCount++;
						}
					}
				}
      }
        
			if ($successCount > 0 && $successCount == count($items)) {
				return "1";
			}
	}

	public function get_payment_transaction_history() {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_transactions", []), true), true);
		$toecho = "";
		
		for ($i = 0; $i < count($out); $i++) {
		    $customer = json_decode(json_decode($this->QuickLook("SELECT username FROM tbl_users WHERE id = ?", [$out[$i]['userref']]), true), true);
		    $tenant = json_decode(json_decode($this->QuickLook("SELECT username FROM tbl_users WHERE id = ?", [$out[$i]['ownerref']]), true), true);
		    $tenant_name = strlen($tenant[0]['username']) != NULL ? $tenant[0]['username'] : "Deleted User";
		    $customer_name = strlen($customer[0]['username']) != NULL ? $customer[0]['username'] : "Deleted User";
			$toecho .= "
				<tr>
					<td class='align-middle'>".$out[$i]['id']."</td>
					<td class='align-middle'>".$out[$i]['token']."</td>
					<td class='align-middle'>".$this->_validateXSS($customer_name)."</td>
					<td class='align-middle'>".$this->_validateXSS($tenant_name)."</td>
					<td class='align-middle'>P".$out[$i]['amount']."</td>
					<td class='align-middle'>".$out[$i]['timestamp']."</td>
				</tr>
			";
		}
		return $toecho;
	}

	public function get_reports() {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dormreports", []), true), true);
		$toecho = "";
		$del_icon="<i class='fa-light fa-trash fa-fw fa-lg'></i>";
		for ($i = 0; $i < count($out); $i++) {
			$toecho .= "
				<tr>
					<td class='align-middle'>".$out[$i]['id']."</td>
					<td class='align-middle'>".$out[$i]['userref']."</td>
					<td class='align-middle'>".$out[$i]['dormref']."</td>
					<td class='align-middle'>".$this->_validateXSS($out[$i]['comment'])."</td>
					<td class='align-middle'>".$out[$i]['createdAt']."</td>
					<td class='align-middle'>
						<button class='btn btn-danger p-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Resolve Report' onclick='resolve_report_admin(this)' data-reportid='".$out[$i]['id']."'>".$del_icon."</button>
					</td>
				</tr>
			";
		}
		return $toecho;
	}

	public function resolve_dorm($reportid) {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_dormreports WHERE id=?", [$reportid]), true), true);

		$vtitle = "StudyHive";
		$vdesc = "The dorm listing you reported has been resolved. Thank you for your contribution!";
		$vreferencestarter = uniqid();
		$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
		$current_time = date('Y-m-d H:i:s');

		if ($this->QuickFire("DELETE FROM `tbl_dormreports` WHERE id=?", [$reportid])) {
			if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?", [$out[0]['userref'], $vtitle, $vdesc, $vreferencestarter, $current_timex, $current_time])) {
				if($this->push_notification($vtitle, $vdesc, $out[0]['userref'])) {
					return "1";
				}
			}
		}
	}

	public function showhide_dorm_admin($items) {
		foreach ($items as $item) {
			$userref = $item["userref"];
			$dormref = $item["dormref"];
			$successCount = 0;
					
			$out = json_decode(json_decode($this->QuickLook("SELECT `name`,`hide` FROM tbl_dorms WHERE id=? AND userref=?", [$dormref, $userref]), true), true);

			$vtitle = "StudyHive";
			$vdeschide = "We would like to notify you that your listing named '" . $out[0]['name'] . "', has been set to be hidden due to being inactive. If you believe this action was a mistake, kindly reach out to our team for assistance.";
			$vdescshow = "We would like to notify you that your listing named '" . $out[0]['name'] . "' has been set to be unhidden. As a result, your listing is now visible and available for viewing. If you did not request this action or believe it was a mistake, please don't hesitate to contact our team immediately. ";
			$vreferencestarter = uniqid();
			$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
			$current_time = date('Y-m-d H:i:s');
					
			if ($this->QuickFire("UPDATE tbl_dorms SET `hide`=? WHERE id=?", [$out[0]['hide'] == 0 ? 1 : 0, $dormref])) {
				if ($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?", [$userref, $vtitle, $out[0]['hide'] == 0 ? $vdeschide : $vdescshow, $vreferencestarter, $current_timex, $current_time])) {
					if($this->push_notification($vtitle, $out[0]['hide'] == 0 ? $vdeschide : $vdescshow, $userref)) {
						$successCount++;
					}
				}
			}
		}
	
		if ($successCount > 0 && $successCount == count($items)) {
			return "1";
		}
	}

	public function statistics() {
	  $notverified = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE is_verified = 0", [], true));
		$notverifiedx = json_decode($notverified, true);
		
		$verified = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE is_verified = 1", [], true));
		$verifiedx = json_decode($verified, true);
		
		$dorms = json_decode($this->QuickLook("SELECT * FROM tbl_dorms", [], true));
		$dormsx = json_decode($dorms, true);
		
		$reports = json_decode($this->QuickLook("SELECT * FROM tbl_dormreports", [], true));
		$reportsx = json_decode($reports, true);
		
		$online = json_decode($this->QuickLook("SELECT * FROM tbl_users WHERE is_online = 1", [], true));
		$onlinex = json_decode($online, true);
		
		$admins = json_decode($this->QuickLook("SELECT * FROM tbl_adminusers", [], true));
		$adminsx = json_decode($admins, true);
		
		return array(
			'not_verified' => count($notverifiedx),
			'verified' => count($verifiedx),
			'dorms' => count($dormsx),
			'reports' => count($reportsx),
			'online' => count($onlinex),
			'admins' => count($adminsx)
		);
	}
	
	public function get_user_statistics() {
	  $user_statistics = json_decode($this->QuickLook("SELECT created_at FROM tbl_users GROUP BY DATE( created_at ) ORDER BY created_at  DESC LIMIT 7;", [], true));
		$user_statisticsx = json_decode($user_statistics, true);
		
	  $user_statistics2 = json_decode($this->QuickLook("SELECT created_at as t, COUNT(*) as y FROM tbl_users GROUP BY DATE( created_at ) ORDER BY created_at  DESC LIMIT 7 ;", [], true));
		$user_statistics2x = json_decode($user_statistics2, true);
		
		return array(
			'user_statistics' => array_reverse($user_statisticsx),
			'user_statistics2' => array_reverse($user_statistics2x),
		);
	}
	
	public function get_transaction_statistics() {
	  $user_statistics = json_decode($this->QuickLook("SELECT timestamp FROM tbl_transactions GROUP BY DATE( timestamp ) ORDER BY timestamp  DESC LIMIT 7;", [], true));
		$user_statisticsx = json_decode($user_statistics, true);
		
	  $user_statistics2 = json_decode($this->QuickLook("SELECT timestamp as t, COUNT(*) as y FROM tbl_transactions GROUP BY DATE( timestamp ) ORDER BY timestamp  DESC LIMIT 7 ;", [], true));
		$user_statistics2x = json_decode($user_statistics2, true);
		
		return array(
			'transactions_statistics' => array_reverse($user_statisticsx),
			'transactions_statistics2' => array_reverse($user_statistics2x),
		);
	}
	
	public function top_reviews_in_dorm() {
	  $dorms = json_decode($this->QuickLook("SELECT tbl_dorms.name, AVG(r.rating) AS average_rating FROM tbl_dormreviews r INNER JOIN tbl_dorms ON r.dormref = tbl_dorms.id GROUP BY r.dormref ORDER BY average_rating DESC LIMIT 5;", [], true));
		$dormsx = json_decode($dorms, true);
		
		return array(
		  'dorms' => array_reverse($dormsx),
		);
	}
	
	public function delete_admin_user($user_id) {
	  $dorms = json_decode($this->QuickLook("DELETE FROM tbl_adminusers WHERE id = ?", [$user_id], true));
		$dormsx = json_decode($dorms, true);
		return "1";
	}
	
	public function changePass($currentpass, $newpass, $retypenewpass) {
	  $check = json_decode($this->QuickLook("SELECT * FROM `tbl_adminusers` WHERE `id` = ? AND `password` = ?", [$_SESSION['id'], $currentpass], true));
		$checkx = json_decode($check, true);
		if (count($checkx) == 1) {
		    if(empty($newpass) && empty($retypenewpass)) {
		      return json_encode(['message'=>"Error! Please input the fields.", 'status' => 400]);
		    }
		    
		    if($newpass === $retypenewpass) {
					$this->QuickFire("UPDATE tbl_adminusers SET password=? WHERE id=?",[$newpass,$_SESSION['id']]);
					return json_encode(['message'=>"Successfully changed.", 'status' => 200]);
		    } else {
		      return json_encode(['message'=>"Your new password and re-type new password doesn't match.", 'status' => 400]);
		    }
		} else {
		    return json_encode(['message'=>"Invalid current password.", 'status' => 400]);
		}
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
