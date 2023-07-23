<?php
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Max-Age: 86400');
    header("Access-Control-Allow-Methods: POST");
    header("Content-Type: multipart/form-data");
    header("Content-Type: application/json");
    date_default_timezone_set('Asia/Manila');
    
    function conn(){
        $host = "localhost";
    	$db = "u390510725_studyhive";
    	$username = "u390510725_studyhive";
    	$password = "pP1FRim0ZD9$";
	
		$conn = new mysqli($host, $username, $password, $db);
		return $conn;
	}
	
    function _validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    function authenticate($authKey)
    {
        $validKey = '%n91qDOmDz(8fAQP';
        
        if ($authKey === $validKey) {
            return true;
        } else {
            return false;
        }
    }

    function statusCode($code, $data)
    {
        if($code == 200) {
            header("HTTP/1.1 200 OK");
        } else if($code == 401) {
            header('HTTP/1.1 401 Unauthorized');
        } else if($code == 403) {
            header('HTTP/1.1 403 Forbidden');
        }

        $array = array(
            'data' => $data,
            'code' => $code
        );

        echo json_encode($array);
    }
    
    function push_notification($title, $message, $userref) {
		// destination is either FCM Device Key or Topic
		$statement = sprintf("SELECT * FROM tbl_notif_fcmkeys WHERE user_ref='%s' GROUP BY fcm_key", $userref);
        $result = conn()->query($statement);

		$fcm_array=array();

		while($row = $result->fetch_assoc()) {
		    array_push($fcm_array,$row['fcm_key']);
		}


		$fields = array
		(
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
	
    // Handle incoming requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        // Check if the auth key is provided in the request headers
        $authKey = $_SERVER['HTTP_AUTH_KEY'] ?? '';

        // Authenticate the client
        if (authenticate($authKey))
        {
            $action = _validate($_POST['action'] ?? NULL);

            if($action !== NULL) {
                switch($action) {
                    case "realtime":
                        
                        $statement = sprintf("SELECT * FROM `tbl_dorms` WHERE `end_time` != ''");
                        $result = conn()->query($statement);
                
                        if ($result->num_rows > 0) {
                            // Success, merong data
                            $data = 0;
                            while($row = $result->fetch_assoc()) {
                                $expire_date = $row['end_time'];
                                $expireNewDate = date("m/d/Y", strtotime($expire_date));
                                $expireNewDate2 = date("Y-m-d", strtotime($expire_date));
                                $expireTimestamp = strtotime($expireNewDate);
                                
                                $now_date = date("m/d/Y", time());
                                $now_date2 = date("Y-m-d", time());
                                if($now_date2 >= $expireNewDate2) {
                                    // expire
                                    if($expireNewDate == $now_date) {
                                        // pagbibigyan natin siya ng isang araw para magbayad.
                                        
                                        // for pay rent button
                                        $statement2 = sprintf("UPDATE `tbl_chatrooms` SET `pay_rent` = 1 WHERE `unique_code` = '%s' AND `to_user` = '%s' AND `pay_rent` = 2", $row['id'], $row['who_own_this_dorm']);
                                        $result2 = conn()->query($statement2);
                                        $statement3 = sprintf("UPDATE `tbl_chatrooms` SET `pay_rent` = 1 WHERE `unique_code` = '%s' AND `from_user` = '%s' AND `pay_rent` = 2", $row['id'], $row['who_own_this_dorm']);
                                        $result3 = conn()->query($statement3);
                                        
                                        // for notification
                                        $statement4 = sprintf("INSERT INTO `tbl_notifications` (user_ref,title,ndesc,created,scheduled,notif_uniqid,status) VALUES('%s','%s','%s', now(), now(),'%s','%s')", $row['who_own_this_dorm'], 'StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', uniqid(), 'unread');
                                        $result4 = conn()->query($statement4);
                                        push_notification('StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', $row['who_own_this_dorm']);
                                        
                                        $whoisthis = sprintf("SELECT * FROM `tbl_users` WHERE `id` = '%s'", $row['who_own_this_dorm']);
                                        $whoisthis_result = conn()->query($whoisthis);
                                        $whoisthis_row = $whoisthis_result->fetch_assoc();
                                        
                                        $statement6 = sprintf("INSERT INTO `tbl_notifications` (user_ref,title,ndesc,created,scheduled,notif_uniqid,status) VALUES('%s','%s','%s', now(), now(),'%s','%s')", $row['userref'], 'StudyHive', $whoisthis_row['username'] . '\'s rent is due today. They are given one (1) to complete their payment', uniqid(), 'unread');
                                        $result6 = conn()->query($statement6);
                                        push_notification('StudyHive', $whoisthis_row['username'] . '\'s rent is due today. They are given one (1) to complete their payment', $row['userref']);
                                        $data = 1;
                                    } else {
                                        // chupi na siya sa dorm pota.
                                        $statement2 = sprintf("UPDATE `tbl_chatrooms` SET `pay_rent` = 0 WHERE `unique_code` = '%s' AND `to_user` = '%s' AND `pay_rent` = 1", $row['id'], $row['who_own_this_dorm']);
                                        $result2 = conn()->query($statement2);
                                        $statement3 = sprintf("UPDATE `tbl_chatrooms` SET `pay_rent` = 0 WHERE `unique_code` = '%s' AND `from_user` = '%s' AND `pay_rent` = 1", $row['id'], $row['who_own_this_dorm']);
                                        $result3 = conn()->query($statement3);
                                        
                                        $statement4 = sprintf("UPDATE `tbl_dorms` SET `who_own_this_dorm` = NULL, `paid_time` = NULL, `end_time` = NULL  WHERE `id` = '%s'", $row['id']);
                                        $result4 = conn()->query($statement4);
                                        
                                        $statement5 = sprintf("INSERT INTO `tbl_notifications` (user_ref,title,ndesc,created,scheduled,notif_uniqid,status) VALUES('%s','%s','%s', now(), now(),'%s','%s')", $row['who_own_this_dorm'], 'StudyHive', 'You are expected to leave the establishment premises today.', uniqid(), 'unread');
                                        $result5 = conn()->query($statement5);
                                        push_notification('StudyHive', 'Please note that your rent is to be paid today. You are given until tomorrow for your convenience.', $row['who_own_this_dorm']);
                                        
                                        $whoisthis = sprintf("SELECT * FROM `tbl_users` WHERE `id` = '%s'", $row['who_own_this_dorm']);
                                        $whoisthis_result = conn()->query($whoisthis);
                                        $whoisthis_row = $whoisthis_result->fetch_assoc();
                                        
                                        $statement6 = sprintf("INSERT INTO `tbl_notifications` (user_ref,title,ndesc,created,scheduled,notif_uniqid,status) VALUES('%s','%s','%s', now(), now(),'%s','%s')", $row['userref'], 'StudyHive', $whoisthis_row['username'] . ' is expected to leave the establishment premises today.', uniqid(), 'unread');
                                        $result6 = conn()->query($statement6);
                                        push_notification('StudyHive', $whoisthis_row['username'] . ' is expected to leave the establishment premises today.', $row['userref']);
                                        $data = 2;
                                    }
                                } else {
                                    $data = 3;
                                }
                            }
                            /*
                                data
                                0 - di pa pumapasok sa database
                                1 - may nag expire
                                2 - pinaalis na ng tenant
                                3 - walang expire
                            */
                            statusCode(200, 'Successfully updated! | '.$data);
                            
                        } else {
                            // Success, walang data.
                            statusCode(200, 'No data!');
                        }
                    break;
                    default:
                        statusCode(200, 'Welcome to Study Hive Bot!');
                    break;
                }   
            } else {
                statusCode(401, 'Invalid authentication key!');
            }
        } else {
            statusCode(401, 'Invalid authentication key!');
        }
    } else {
        statusCode(401, 'Invalid authentication key!');
    }
?>