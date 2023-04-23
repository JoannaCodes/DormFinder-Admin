<?php
Class sdm_query{
	private $c;
	public function __construct($db){
		$this->c=$db;
	}
	public function send_document($fileName) {
		if($this->QuickFire("INSERT INTO tbl_documents SET doc_1=?, doc1_status=?, user_id=?",
				[$fileName,"0","1122"])) {
			return "1";
		}
	}
	public function login_dormfinder($email,$password) {
		$out = json_decode($this->QuickLook("SELECT * FROM tbl_adminusers WHERE email=? AND password=?",[$email,$password],true));
		$outx=json_decode($out,true);
		if(count($outx) == 1) {
			if($outx[0]['password'] == $password) {
				echo json_encode(["email"=>$outx[0]['email'], "id"=>$outx[0]['id'], "status"=>"true"]);
			} else {
				echo json_encode(["email"=>"none", "id"=>"none", "status"=>"false"]);
			}
		}
	}
	public function verify_document($id,$docvalue) {
		$vtitle="DormFinder";
		$vdesc="Your documents have been verified! You can now publish your Dorm.";
		$vreferencestarter="1";
		$current_timex = date('Y-m-d H:i:s', strtotime('+1 minute'));
		$current_time = date('Y-m-d H:i:s');
		if($this->QuickFire("INSERT INTO tbl_notifications SET user_ref=?,title=?,ndesc=?,notif_uniqid=?,scheduled=?,created=?",
				["1122",$vtitle,$vdesc,$vreferencestarter,$current_timex,$current_time])) {
			if($this->QuickFire("UPDATE tbl_documents SET doc1_status=? WHERE id=?",
				[$docvalue,$id])){
				return "1";
			}
		}
	}
	public function get_submitdocuments() {
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_documents",[]),true),true);
		$toecho="";
		$doc_status="";
		for ($i=0;$i<count($out);$i++) {
			switch($out[$i]['doc1_status']) {
				case '0':
					$doc_status="Unverified";
					$doc_tooltip="Verify";
					$doc_value="1";
					$doc_icon="<i class='fa-light fa-file-check fa-fw fa-lg'></i>";
				break;
				case '1':
					$doc_status="Verified";
					$doc_tooltip="Unverify";
					$doc_value="0";
					$doc_icon="<i class='fa-light fa-file-xmark fa-fw fa-lg'></i>";
				break;
			}
			$toecho.="<tr>
	          <td class='align-middle'>".$out[$i]['doc_1']."</td>
	          <td class='align-middle'>".$doc_status."</td>
	          <td class='align-middle'>
	          	<a data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Download' href='http://localhost/dormfinder_php/all_photos/".$out[$i]['doc_1']."' download='".$out[$i]['doc_1']."' class='btn btn-link text-primary p-0 btn-lg type='button'><i class='fa-light fa-file-arrow-down fa-fw fa-lg'></i></a>
	          	<button class='btn btn-link text-primary p-0 btn-lg' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='".$doc_tooltip."' data-docvalue='".$doc_value."' onclick='verify_document(this)' data-id='".$out[$i]['id']."'>".$doc_icon."</button>
	          </td>
	        </tr>";
		}

		return $toecho;
	}
	public function look_morepastnotif($userref,$idstoftech){
		$thiday = date("Y-m-d H:i:s");
		$tofetch = "";
		$allid = explode(",", $idstoftech);
		
		$cleanedallid = array();
		
		
		
			for ($i = 0; $i < count($allid);$i++){
				if($allid[$i] != "" && $allid[$i] != null){
					array_push($cleanedallid,  $allid[$i]);
				}
			
		}
		$allid = 	$cleanedallid;
		
		if (count($allid) != 0 ){
			$tofetch .= " AND (";
		}

		for ($i = 0; $i < count($allid);$i++){
			if($allid[$i] != "" && $allid[$i] != null){
				$tofetch .= " id != '" . $allid[$i] . "'";


				if (($i + 1) < count($allid)){
					$tofetch .= " AND ";	
				}
			}
			
		}
		$lastvalue = "";
		if (count($allid) != 0 ){

			$tofetch .= ")";

		}

		$q = "SELECT * FROM tbl_notifications WHERE
		user_ref=? AND
		scheduled <= ? " . $tofetch . " ORDER BY scheduled DESC LIMIT 25";
		$out = json_decode(json_decode($this->QuickLook($q,[$userref,$thiday]),true),true);

		for($i = 0 ; $i < count($out);$i++){

		$out[$i]["scheduled"] = $this->DateExplainer($out[$i]["scheduled"] )   . ";" . date("F d, Y g:i a",strtotime($out[$i]["scheduled"]));

		}

	return json_encode(json_encode($out));

	}
	public function look_passednotifications($userref){
		$thiday = date("Y-m-d H:i:s");
		$out = json_decode(json_decode($this->QuickLook("SELECT * FROM tbl_notifications WHERE
		user_ref=? AND
		scheduled <= ? ORDER BY scheduled DESC LIMIT 8",[$userref,$thiday]),true),true);



		for($i = 0 ; $i < count($out);$i++){

		$out[$i]["scheduled"] = $this->DateExplainer($out[$i]["scheduled"] )   . ";" . date("F d, Y g:i a",strtotime($out[$i]["scheduled"]));

		}

		return json_encode(json_encode($out));


	}

		public function DateExplainer($thedate){
		$future = strtotime($thedate); //Future date.
		$timefromdb = strtotime(date("Y-m-d"));
		$timeleft = ($future - $timefromdb);
		$daysleft = round((($timeleft/24)/60)/60); 

		if($daysleft < 1){
			$formatted_day = str_replace("-","",$daysleft );

			if($formatted_day == 1){
				$dleft =  "yesterday";
			}else{


				$months = floor($formatted_day / 30);

				if ($months > 0){
					if($months == 1){
						$dleft =  "a month ago";
					}else{
						$dleft =  $months . " months ago";
					}
					
				}else{
					if ($formatted_day == 0){
						$dleft =  "today";
					}else{
						$dleft =  $formatted_day . " days ago";
					}
				}


				
				
			}
		}else{
			$formatted_day = $daysleft;

			if($formatted_day == 1){
				$dleft =  "today";
			}else{

				$months = floor($formatted_day / 30);



				if ($months > 0){
					$dleft =  $months . " months ago";
				}else{
					$dleft =  $formatted_day . " days ago";
				}


				
			}
			
			if($daysleft == 1){
				$dleft = "tomorrow";
			}else{

				$months = floor($daysleft / 30);

				if ($months > 0){
					$dleft =  $months . " months to go";
				}else{
					$dleft =  $daysleft . " days to go";
				}

			
			}
		}
		return $dleft;
	}
	public function look_usersavednotifs(){
		$currdate = date("Y-m-d H:i:s");
		return $this->QuickLook("SELECT *, UNIX_TIMESTAMP(scheduled) as unix_time FROM tbl_notifications WHERE scheduled>?",[$currdate]);
	}
	public function QuickLook($q,$par =array()){
		$q = $this->c->prepare($q);
		if($q->execute($par)){
			return json_encode(json_encode($q->fetchall(PDO::FETCH_ASSOC)));
		} else {
			return $q->errorInfo();
		}
	}

	public function QuickFire($q,$par =array()){
		$q = $this->c->prepare($q);
		if($q->execute($par)){
			return json_encode(json_encode(array("true")));
		}else{
			return json_encode(json_encode(array("false")));
		}
	}
}
?>