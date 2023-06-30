<?php
class api_queries
{
	private $conn;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}
    
    public function getLastChatByID($chatroom_code) {
        $statement = sprintf("SELECT * FROM `tbl_chats` WHERE `chatroom_code` = '%s' ORDER BY id DESC", $chatroom_code);
        $result = $this->conn->query($statement);
        $row = $result->fetch_assoc();
        return array(
            'data' => (int) $row['itr'],
            'code' => 200
        );
    }

    public function getChatrooms($to_user) {
        $statement = sprintf("SELECT tbl_chatrooms.from_user as id, tbl_users.username, tbl_chatrooms.unique_code, tbl_chatrooms.chatroom_code, tbl_users.imageUrl FROM tbl_chatrooms INNER JOIN tbl_users ON tbl_chatrooms.from_user = tbl_users.id  WHERE to_user = '%s' ORDER BY tbl_chatrooms.id DESC", $to_user);

        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            $array = array();
            $count = 1;
            while($row = $result->fetch_assoc()) {
                $statement2 = sprintf("SELECT * FROM `tbl_chats` WHERE `chatroom_code` = '%s' ORDER BY id DESC", $row['chatroom_code']);
                $result2 = $this->conn->query($statement2);
                $row2 = $result2->fetch_assoc();
                if($row2) {
                    $statement3 = sprintf("SELECT * FROM `tbl_dorms` WHERE `id` = '%s'", $row['unique_code']);
                    $result3 = $this->conn->query($statement3);
                    $row3 = $result3->fetch_assoc();

                    $whoFirst = ($to_user == $row2['user_id']) ?  'You' : $row['username'];
                    if(strlen($row2['image'])!=0) {
                        $whoFirst .= " sent a photo.";
                    } else {
                        $whoFirst .= ": ";
                        $whoFirst .= $row2['message'];
                    }

                    $username = ($row3['name'] ?? "");
                    $username .= (($row3['name'] ?? NULL) ? " - " : "");
                    $username .= $row['username'] ?? "Deleted User";
                    $array[] = array(
                        'id' => $count,
                        'user_id' => $row['id'],
                        'username' => $username,
                        'unique_code' => $row['unique_code'],
                        'chatroom_code' => $row['chatroom_code'],
                        'imageUrl' => $row['imageUrl'] ?? "https://studyhive.social/images/logo.png",
                        'message' => $whoFirst,
                        'time' => $row2['time'] ?? 0
                    );
                } else {
                    $statement3 = sprintf("SELECT * FROM `tbl_dorms` WHERE `id` = '%s'", $row['unique_code']);
                    $result3 = $this->conn->query($statement3);
                    $row3 = $result3->fetch_assoc();

                    $username = ($row3['name'] ?? "");
                    $username .= (($row3['name'] ?? NULL) ? " - " : "");
                    $username .= $row['username'] ?? "Deleted User";
                    $array[] = array(
                        'id' => $count,
                        'user_id' => $row['id'],
                        'username' => $username,
                        'unique_code' => $row['unique_code'],
                        'chatroom_code' => $row['chatroom_code'],
                        'imageUrl' => $row['imageUrl'] ?? "https://studyhive.social/images/logo.png",
                        'message' => "",
                        'time' => $row2['time'] ?? 0
                    );
                }
                $count++;
            }

            return array(
                'data' => $array,
                'code' => 200
            );
        } else {
            return array(
                'data' => 'No results found.',
                'code' => 403
            );
        }
    }

    public function checkLogin($email, $fcm) {
        $statement = sprintf("SELECT * FROM tbl_users WHERE `identifier` = '%s'", $email);

        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            // success, email exist
            $row = $result->fetch_assoc();
            $dtnow = date("Y-m-d H:i:s");
            $statement2 = sprintf("INSERT INTO tbl_notif_fcmkeys SET `user_ref` = '%s', `fcm_key` = '%s', `created_at` = '%s'", $row['id'], $fcm, $dtnow);
            $this->conn->query($statement2);
            return array (
                'data' => ["username" => $row['username'], "id" => $row['id'], "status" => true, "mode" => "user"],
                'code' => 200
            );
        } else {
            // error, email doesn't exist
            return array(
                'data' => 'No results found.',
                'code' => 403
            );
        }
    }

    public function checkRegister($email, $username, $imageUrl) {
        $statement = sprintf("SELECT * FROM tbl_users WHERE `identifier` = '%s'", $email);
        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            // error, email exist
            return array(
                'data' => 'Your account already exist! Go to login page!',
                'code' => 403
            );
        } else {
            // success, email doesn't exist
            $statement2 = sprintf("INSERT INTO tbl_users SET id = '%s', identifier='%s', username='%s', `password`='%s', `imageUrl` = '%s', updated_at=now(), created_at=now()", uniqid(), $email, $username, uniqid(), $imageUrl);
            $this->conn->query($statement2);

            $statement3 = sprintf("SELECT * FROM tbl_users WHERE `identifier` = '%s'", $email);
            $result3 = $this->conn->query($statement3);
            $row = $result3->fetch_assoc();

            return array(
                'data' => ["username" => $row['username'], "id" => $row['id'], "status" => true, "mode" => "user"],
                'code' => 200
            );
        }
    }

    public function getChats($chatroom_code, $myId, $itr) {
        $statement = sprintf("SELECT tbl_chats.id as id, tbl_chats.itr, tbl_users.id as user_id, tbl_users.username, tbl_users.imageUrl, tbl_chats.message, tbl_chats.image, tbl_chats.time FROM tbl_chats INNER JOIN tbl_users ON tbl_chats.user_id = tbl_users.id WHERE chatroom_code = '%s' AND `itr` > '".$itr."' ORDER BY tbl_chats.id DESC LIMIT 10", $chatroom_code);

        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            $array = array();
            
            while($row = $result->fetch_assoc()) {
                $array[] = array(
                    'id' => (int) $row['id'],
                    'itr' => (int) $row['itr'],
                    'user_id' => $row['user_id'],
                    'username' => $row['username'],
                    'message' => $row['message'],
                    'image' => $row['image'],
                    'time' => (int) $row['time'],
                    'imageUrl' => $row['imageUrl'],
                    'balloon' => $myId === $row['user_id'] ? true : false,
                );
            }

            return array(
                'data' => $array,
                'code' => 200
            );
        } else {
            return array(
                'data' => 'No results found.',
                'code' => 403
            );
        }
    }

    public function getPreviouslyChats($chatroom_code, $myId, $itr) {
        $statement = sprintf("SELECT tbl_chats.id as id, tbl_chats.itr, tbl_users.id as user_id, tbl_users.username, tbl_users.imageUrl, tbl_chats.message, tbl_chats.image, tbl_chats.time FROM tbl_chats INNER JOIN tbl_users ON tbl_chats.user_id = tbl_users.id WHERE chatroom_code = '%s' AND `itr` < '".$itr."' ORDER BY tbl_chats.id DESC LIMIT 10", $chatroom_code);

        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            $array = array();
            
            while($row = $result->fetch_assoc()) {
                $array[] = array(
                    'id' => (int) $row['id'],
                    'itr' => (int) $row['itr'],
                    'user_id' => $row['user_id'],
                    'username' => $row['username'],
                    'message' => $row['message'],
                    'image' => $row['image'],
                    'time' => (int) $row['time'],
                    'imageUrl' => $row['imageUrl'],
                    'balloon' => $myId === $row['user_id'] ? true : false,
                );
            }

            return array(
                'data' => $array,
                'code' => 200
            );
        } else {
            return array(
                'data' => 'No results found.',
                'code' => 403
            );
        }
    }

    public function sendChat($chatroom_code, $myId, $message, $image) {
        $getStatement = sprintf("SELECT * FROM `tbl_chats` WHERE `chatroom_code` = '%s' ORDER BY id DESC", $chatroom_code);
        $getResult = $this->conn->query($getStatement);
        $getRow = $getResult->fetch_assoc();
        echo $getRow['itr'];
        if(strlen($image) != 0) {
            $statement = sprintf("INSERT INTO tbl_chats (chatroom_code,user_id,`image`,`time`,`itr`) VALUES ('%s','%s','%s', %d, %d)", $chatroom_code, $myId, $this->base64ToImage($image, $chatroom_code), time(), ($getRow['itr'] ?? 0) + 1);
            $this->conn->query($statement);
        } else {
            $statement = sprintf("INSERT INTO tbl_chats (chatroom_code,user_id,`message`,`time`,`itr`) VALUES ('%s','%s','%s', %d, %d)", $chatroom_code, $myId, $message, time(), ($getRow['itr'] ?? 0) + 1);
            $this->conn->query($statement);
        }
        
        return array(
            'data' => 'Successfully sent!',
            'code' => 200
        );
    }

    public function base64ToImage($img, $unique_code) {
        if(!is_dir("uploads/chatrooms/". $unique_code ."/")) {
            mkdir("uploads/chatrooms/". $unique_code ."/");
        }
        
        $data = str_replace('data:image/png;base64,', '', $img);
        $data = str_replace('data:image/jpeg;base64,', '', $data);
        $data = str_replace('data:image/jpg;base64,', '', $data);
        $data = str_replace('data:image/jfif;base64,', '', $data);
        $data = str_replace('data:image/bmp;base64,', '', $data);
        $data = str_replace('data:image/gif;base64,', '', $data);
        $data = str_replace(' ', '+', $data);
        $data = base64_decode($data);


        $mimeType = mime_content_type($img);
    
        $mimeTypes = [
            'image/png' => '.png',
            'image/jpeg' => '.jpeg',
            'image/jpg' => '.jpg',
            'image/jfif' => '.jfif',
            'image/bmp' => '.bmp',
            'image/gif' => '.gif'
        ];
    
        $mimeType = isset($mimeTypes[$mimeType]) ? $mimeTypes[$mimeType] : '';
    
        $path = 'uploads/chatrooms/' . $unique_code . '/' . uniqid() . $mimeType;
        file_put_contents($path, $data);
    
        return $path;
    }

    public function addChat($unique_code, $myid, $other_id) {
        $statement = sprintf("SELECT * FROM `tbl_chatrooms` WHERE `unique_code` = '%s' AND `to_user` = '%s' AND `from_user` = '%s'", $unique_code, $myid, $other_id);
        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            // Error, exist data
            return array(
                'data' => 'Error, you already have conversation with this user!',
                'code' => 403
            );
        } else {
            $unique = uniqid();
            $unique2 = $unique;
            // Success, does not exist data
            $statement2 = sprintf("INSERT INTO tbl_chatrooms (`unique_code`,`chatroom_code`,`to_user`,`from_user`,`time`) VALUES ('%s','%s','%s','%s', %d)", $unique_code, $unique2, $myid, $other_id, time());
            $this->conn->query($statement2);

            $statement3 = sprintf("INSERT INTO tbl_chatrooms (`unique_code`,`chatroom_code`,`to_user`,`from_user`,`time`) VALUES ('%s','%s','%s','%s', %d)", $unique_code, $unique2, $other_id, $myid, time());
            $this->conn->query($statement3);

            return array(
                'data' => 'Successfully added!',
                'code' => 200
            );
        }
    }

    public function getDorm($unique_code) {
        $statement = sprintf("SELECT * FROM `tbl_dorms` WHERE `id` = '%s'", $unique_code);
        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            // Success, exist data
            $data = $result->fetch_assoc();
            $first_image = explode(',', $data['images']);
            $data['first_image'] = $first_image[0];
            return array(
                'data' => $data,
                'code' => 200
            );
        } else {
            // Error, does not exist data
            return array(
                'data' => 'Error, that dorm doesn\'t exist!',
                'code' => 403
            );
        }
    }

    public function getAmenities($unique_code) {
        $statement = sprintf("SELECT * FROM `tbl_amenities` WHERE `dormref` = '%s'", $unique_code);
        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            // Success, exist data
            $data = $result->fetch_assoc();
            return array(
                'data' => $data,
                'code' => 200
            );
        } else {
            // Error, does not exist data
            return array(
                'data' => 'Error, that amenities doesn\'t exist!',
                'code' => 403
            );
        }
    }
    public function popular_dorm($aircon,$elevator,$beddings,$kitchen,$laundry,$lounge,$parking,$security,$study_room,$wifi,$pet,$visitor,$curfew,$rating, $min_price, $max_price, $hei) {
        
        if(
            $aircon == 0 &&
            $elevator == 0 &&
            $beddings == 0 &&
            $kitchen == 0 &&
            $laundry == 0 &&
            $lounge == 0 &&
            $parking == 0 &&
            $security == 0 &&
            $study_room == 0 &&
            $pet == 0 &&
            $visitor == 0 &&
            $curfew == 0 &&
            $wifi == 0 &&
            $rating == 0 &&
            $min_price == 0 &&
            $max_price == 0 &&
            $hei == ""
        ) {
            $sql = "SELECT tbl_dorms.* FROM tbl_dorms";
            $sql .= " INNER JOIN tbl_amenities ON tbl_dorms.id = tbl_amenities.dormref";
            $sql .= ' WHERE';
            $sql .= ' tbl_dorms.hide = 0 AND';
            $sql .= ' tbl_dorms.id IN (SELECT tbl_dormreviews.dormref FROM tbl_dormreviews WHERE tbl_dormreviews.rating >= 3)';

            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                // Success, exist data
                $array = array();
                while($row = $result->fetch_assoc()) {
                    $array[] = $row;
                }
                
                return array(
                    'data' => $array,
                    'code' => 200
                );
            } else {
                // Error, does not exist data
                return array(
                    'data' => 'Error, no results found.',
                    'code' => 403
                );
            }
        } else {
            $establishment_rules_array = array(
                array('name' => 'pets','value' => $pet),
                array('name' => 'visitors','value' => $visitor),
                array('name' => 'curfew','value' => $curfew)
            );

            $amenities_array = array(
                array('name' => 'aircon','value' => $aircon),
                array('name' => 'elevator','value' => $elevator),
                array('name' => 'beddings','value' => $beddings),
                array('name' => 'kitchen','value' => $kitchen),
                array('name' => 'laundry','value' => $laundry),
                array('name' => 'lounge','value' => $lounge),
                array('name' => 'parking','value' => $parking),
                array('name' => 'security','value' => $security),
                array('name' => 'study_room','value' => $study_room),
                array('name' => 'wifi','value' => $wifi)
            );

            $establishment_rules_sql = '';
            $amenities_sql = '';
            $countEstablishment = count($establishment_rules_array);
            $countAmenities = count($amenities_array);
            
            $conditions = [];

            for ($sqs = 0; $sqs < $countEstablishment; $sqs++) {
                if ($establishment_rules_array[$sqs]['value'] == 1 || $establishment_rules_array[$sqs]['value'] == "1") {
                    $condition = sprintf("tbl_dorms.%s = %d", $establishment_rules_array[$sqs]['name'], $establishment_rules_array[$sqs]['value']);
                    $conditions[] = $condition;
                }
            }

            $establishment_rules_sql .= implode(' AND ', $conditions);

            $conditions2 = [];

            for ($sq = 0; $sq < $countAmenities; $sq++) {
                if ($amenities_array[$sq]['value'] == 1 || $amenities_array[$sq]['value'] == "1") {
                    $condition = sprintf("tbl_amenities.%s = %d", $amenities_array[$sq]['name'], $amenities_array[$sq]['value']);
                    $conditions2[] = $condition;
                }
            }

            $amenities_sql .= implode(' AND ', $conditions2);

            $sql = "SELECT tbl_dorms.* FROM tbl_dorms";
            $sql .= " INNER JOIN tbl_amenities ON tbl_dorms.id = tbl_amenities.dormref";
            if($establishment_rules_sql != '' || $amenities_sql != '' ) {
                $sql .= ' WHERE tbl_dorms.hide = 0 AND';
            } else {
                $sql .= ' WHERE tbl_dorms.hide = 0';
            }
            
            if($establishment_rules_sql != '') {
                $sql .= '(';
                    $sql .= $establishment_rules_sql;
                $sql .= ')';
            }

            if($amenities_sql != '') {
                if($establishment_rules_sql != '') {
                    $sql .= ' AND ';
                }
                $sql .= '(';
                    $sql .= $amenities_sql;
                $sql .= ')';
            }
            
            if($establishment_rules_sql != '' || $amenities_sql != '') {
                $sql .= sprintf(" AND tbl_dorms.id IN (SELECT tbl_dormreviews.dormref FROM tbl_dormreviews WHERE tbl_dormreviews.rating >= %d)", $rating);
            } else {
                $sql .= sprintf(" WHERE tbl_dorms.id IN (SELECT tbl_dormreviews.dormref FROM tbl_dormreviews WHERE tbl_dormreviews.rating >= %d)", $rating);
            }

            if($min_price != 0 && $max_price != 0) {
                $sql .= sprintf(" AND tbl_dorms.price >= %s && tbl_dorms.price <= %s", $min_price, $max_price);
            }
            
            if($hei != "") {
                $sql .= sprintf(" AND FIND_IN_SET('%s', tbl_dorms.hei) > 0", $hei);
            }

            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                // Success, exist data
                $array = array();
                while($row = $result->fetch_assoc()) {
                    $array[] = $row;
                }
                
                return array(
                    'data' => $array,
                    'code' => 200
                );
            } else {
                // Error, does not exist data
                return array(
                    'data' => 'Error, no results found.',
                    'code' => 403
                );
            }
        }
    }
    public function latest_dorm($aircon,$elevator,$beddings,$kitchen,$laundry,$lounge,$parking,$security,$study_room,$wifi,$pet,$visitor,$curfew, $rating, $min_price, $max_price, $hei) {
        
        if(
            $aircon == 0 &&
            $elevator == 0 &&
            $beddings == 0 &&
            $kitchen == 0 &&
            $laundry == 0 &&
            $lounge == 0 &&
            $parking == 0 &&
            $security == 0 &&
            $study_room == 0 &&
            $pet == 0 &&
            $visitor == 0 &&
            $curfew == 0 &&
            $wifi == 0 &&
            $rating == 0 &&
            $min_price == 0 &&
            $max_price == 0 &&
            $hei == ""
        ) {
            $sql = "SELECT tbl_dorms.* FROM tbl_dorms";
            $sql .= " INNER JOIN tbl_amenities ON tbl_dorms.id = tbl_amenities.dormref";
            $sql .= " WHERE tbl_dorms.hide = 0";
            $sql .= ' ORDER BY tbl_dorms.createdAt DESC';
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                // Success, exist data
                $array = array();
                while($row = $result->fetch_assoc()) {
                    $array[] = $row;
                }
                
                return array(
                    'data' => $array,
                    'code' => 200
                );
            } else {
                // Error, does not exist data
                return array(
                    'data' => 'Error, no results found.',
                    'code' => 403
                );
            }
        } else {
            $establishment_rules_array = array(
                array('name' => 'pets','value' => $pet),
                array('name' => 'visitors','value' => $visitor),
                array('name' => 'curfew','value' => $curfew)
            );

            $amenities_array = array(
                array('name' => 'aircon','value' => $aircon),
                array('name' => 'elevator','value' => $elevator),
                array('name' => 'beddings','value' => $beddings),
                array('name' => 'kitchen','value' => $kitchen),
                array('name' => 'laundry','value' => $laundry),
                array('name' => 'lounge','value' => $lounge),
                array('name' => 'parking','value' => $parking),
                array('name' => 'security','value' => $security),
                array('name' => 'study_room','value' => $study_room),
                array('name' => 'wifi','value' => $wifi)
            );

            $establishment_rules_sql = '';
            $amenities_sql = '';
            $countEstablishment = count($establishment_rules_array);
            $countAmenities = count($amenities_array);
            
            $conditions = [];

            for ($sqs = 0; $sqs < $countEstablishment; $sqs++) {
                if ($establishment_rules_array[$sqs]['value'] == 1 || $establishment_rules_array[$sqs]['value'] == "1") {
                    $condition = sprintf("tbl_dorms.%s = %d", $establishment_rules_array[$sqs]['name'], $establishment_rules_array[$sqs]['value']);
                    $conditions[] = $condition;
                }
            }

            $establishment_rules_sql .= implode(' AND ', $conditions);

            $conditions2 = [];

            for ($sq = 0; $sq < $countAmenities; $sq++) {
                if ($amenities_array[$sq]['value'] == 1 || $amenities_array[$sq]['value'] == "1") {
                    $condition = sprintf("tbl_amenities.%s = %d", $amenities_array[$sq]['name'], $amenities_array[$sq]['value']);
                    $conditions2[] = $condition;
                }
            }

            $amenities_sql .= implode(' AND ', $conditions2);

            $sql = "SELECT tbl_dorms.* FROM tbl_dorms";
            $sql .= " INNER JOIN tbl_amenities ON tbl_dorms.id = tbl_amenities.dormref";
            if($establishment_rules_sql != '' || $amenities_sql != '') {
                $sql .= ' WHERE tbl_dorms.hide = 0 AND';
            } else {
                $sql .= ' WHERE  tbl_dorms.hide = 0';
            }
            
            
            if($establishment_rules_sql != '') {
                $sql .= '(';
                    $sql .= $establishment_rules_sql;
                $sql .= ')';
            }

            if($amenities_sql != '') {
                if($establishment_rules_sql != '') {
                    $sql .= ' AND ';
                }
                $sql .= '(';
                    $sql .= $amenities_sql;
                $sql .= ')';
            }
            
            
            if($establishment_rules_sql != '' || $amenities_sql != '') {
                $sql .= sprintf(" AND tbl_dorms.id IN (SELECT tbl_dormreviews.dormref FROM tbl_dormreviews WHERE tbl_dormreviews.rating >= %d)", $rating);
            } else {
                $sql .= sprintf(" WHERE tbl_dorms.id IN (SELECT tbl_dormreviews.dormref FROM tbl_dormreviews WHERE tbl_dormreviews.rating >= %d)", $rating);
            }

            if($min_price != 0 && $max_price != 0) {
                $sql .= sprintf(" AND tbl_dorms.price >= %s && tbl_dorms.price <= %s", $min_price, $max_price);
            }
            
            if($hei != "") {
                $sql .= sprintf(" AND FIND_IN_SET('%s', tbl_dorms.hei) > 0", $hei);
            }
            
            $sql .= ' ORDER BY tbl_dorms.createdAt DESC LIMIT 50';
            
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                // Success, exist data
                $array = array();
                while($row = $result->fetch_assoc()) {
                    $array[] = $row;
                }
                
                return array(
                    'data' => $array,
                    'code' => 200
                );
            } else {
                // Error, does not exist data
                return array(
                    'data' => 'Error, no results found.',
                    'code' => 403
                );
            }
        }
    }
    public function nearest_dorm($aircon,$elevator,$beddings,$kitchen,$laundry,$lounge,$parking,$security,$study_room,$wifi,$pet,$visitor,$curfew, $rating, $min_price, $max_price, $hei, $latitude, $longitude) {
        
        if(
            $aircon == 0 &&
            $elevator == 0 &&
            $beddings == 0 &&
            $kitchen == 0 &&
            $laundry == 0 &&
            $lounge == 0 &&
            $parking == 0 &&
            $security == 0 &&
            $study_room == 0 &&
            $pet == 0 &&
            $visitor == 0 &&
            $curfew == 0 &&
            $wifi == 0 &&
            $rating == 0 &&
            $min_price == 0 &&
            $max_price == 0 &&
            $hei == "" &&
            $latitude === "" &&
            $longitude === ""
        ) {
            $sql = "SELECT tbl_dorms.* FROM tbl_dorms";
            $sql .= " INNER JOIN tbl_amenities ON tbl_dorms.id = tbl_amenities.dormref";
            $sql .= ' WHERE';
            $sql .= ' tbl_dorms.hide = 0 AND';
            // $sql .= ' (6371 * acos(cos(radians(123.456)) * cos(radians(latitude)) * cos(radians(longitude) - radians(789.012)) + sin(radians(123.456)) * sin(radians(latitude))))';
            $sql .= ' (6371 * acos(cos(radians('.$latitude.')) * cos(radians(latitude)) * cos(radians('.$longitude.') - radians(longitude)) + sin(radians('.$latitude.')) * sin(radians(latitude))))';
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                // Success, exist data
                $array = array();
                while($row = $result->fetch_assoc()) {
                    $array[] = $row;
                }
                
                return array(
                    'data' => $array,
                    'code' => 200
                );
            } else {
                // Error, does not exist data
                return array(
                    'data' => 'Error, no results found.',
                    'code' => 403
                );
            }
        } else {
            $establishment_rules_array = array(
                array('name' => 'pets','value' => $pet),
                array('name' => 'visitors','value' => $visitor),
                array('name' => 'curfew','value' => $curfew)
            );

            $amenities_array = array(
                array('name' => 'aircon','value' => $aircon),
                array('name' => 'elevator','value' => $elevator),
                array('name' => 'beddings','value' => $beddings),
                array('name' => 'kitchen','value' => $kitchen),
                array('name' => 'laundry','value' => $laundry),
                array('name' => 'lounge','value' => $lounge),
                array('name' => 'parking','value' => $parking),
                array('name' => 'security','value' => $security),
                array('name' => 'study_room','value' => $study_room),
                array('name' => 'wifi','value' => $wifi)
            );

            $establishment_rules_sql = '';
            $amenities_sql = '';
            $countEstablishment = count($establishment_rules_array);
            $countAmenities = count($amenities_array);
            
            $conditions = [];

            for ($sqs = 0; $sqs < $countEstablishment; $sqs++) {
                if ($establishment_rules_array[$sqs]['value'] == 1 || $establishment_rules_array[$sqs]['value'] == "1") {
                    $condition = sprintf("tbl_dorms.%s = %d", $establishment_rules_array[$sqs]['name'], $establishment_rules_array[$sqs]['value']);
                    $conditions[] = $condition;
                }
            }

            $establishment_rules_sql .= implode(' AND ', $conditions);

            $conditions2 = [];

            for ($sq = 0; $sq < $countAmenities; $sq++) {
                if ($amenities_array[$sq]['value'] == 1 || $amenities_array[$sq]['value'] == "1") {
                    $condition = sprintf("tbl_amenities.%s = %d", $amenities_array[$sq]['name'], $amenities_array[$sq]['value']);
                    $conditions2[] = $condition;
                }
            }

            $amenities_sql .= implode(' AND ', $conditions2);

            $sql = "SELECT tbl_dorms.* FROM tbl_dorms";
            $sql .= " INNER JOIN tbl_amenities ON tbl_dorms.id = tbl_amenities.dormref";
            $sql .= ' WHERE';
            $sql .= ' tbl_dorms.hide = 0 AND';
            $sql .= ' (6371 * acos(cos(radians('.$latitude.')) * cos(radians(latitude)) * cos(radians('.$longitude.') - radians(longitude)) + sin(radians('.$latitude.')) * sin(radians(latitude))))';
            if($establishment_rules_sql != '') {
                $sql .= ' AND ';
                $sql .= '(';
                    $sql .= $establishment_rules_sql;
                $sql .= ')';
            }

            if($amenities_sql != '') {
                if($establishment_rules_sql != '') {
                    $sql .= ' AND ';
                }
                $sql .= '(';
                    $sql .= $amenities_sql;
                $sql .= ')';
            }

            if($establishment_rules_sql != '' || $amenities_sql != '') {
                $sql .= sprintf(" AND tbl_dorms.id IN (SELECT tbl_dormreviews.dormref FROM tbl_dormreviews WHERE tbl_dormreviews.rating >= %d)", $rating);
            } else {
                $sql .= sprintf(" AND tbl_dorms.id IN (SELECT tbl_dormreviews.dormref FROM tbl_dormreviews WHERE tbl_dormreviews.rating >= %d)", $rating);
            }

            if($min_price != 0 && $max_price != 0) {
                $sql .= sprintf(" AND tbl_dorms.price >= %s && tbl_dorms.price <= %s", $min_price, $max_price);
            }
            
            if($hei != "") {
                $sql .= sprintf(" AND FIND_IN_SET('%s', tbl_dorms.hei) > 0", $hei);
            }
            
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                // Success, exist data
                $array = array();
                while($row = $result->fetch_assoc()) {
                    $array[] = $row;
                }
                
                return array(
                    'data' => $array,
                    'code' => 200
                );
            } else {
                // Error, does not exist data
                return array(
                    'data' => 'Error, no results found.',
                    'code' => 403
                );
            }
        }
    }
    public function getMessageInfos($unique_code, $myid, $other_id) {
        $statement = sprintf("SELECT * FROM `tbl_chatrooms` WHERE `unique_code` = '%s' AND `to_user` = '%s' AND `from_user` = '%s'", $unique_code, $myid, $other_id);
        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            // Success, exist data
            $data = $result->fetch_assoc();

            $me_statement = sprintf("SELECT tbl_users.username, tbl_users.imageUrl FROM `tbl_users` WHERE `id` = '%s'", $myid);
            $me_result = $this->conn->query($me_statement);
            $data['me'] = $me_result->fetch_assoc();

            $other_statement = sprintf("SELECT tbl_users.username, tbl_users.imageUrl FROM `tbl_users` WHERE `id` = '%s'", $other_id);
            $other_result = $this->conn->query($other_statement);
            $data['other'] = $other_result->fetch_assoc();

            return array(
                'data' => $data,
                'code' => 200
            );
        } else {
            // Error, does not exist data
            return array(
                'data' => 'Error! There\'s something wrong!',
                'code' => 403
            );
        }
    }
    public function get_dorm_details($dormref, $user_id) {
        $statement = sprintf("SELECT d.*, a.* FROM tbl_dorms d JOIN tbl_amenities a ON d.id = a.dormref WHERE d.id='%s'", $dormref);
        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $me_statement = sprintf("SELECT * FROM `tbl_bookmarks` WHERE `dormref` = '%s' AND `userref` = '%s'", $dormref, $user_id);
            $me_result = $this->conn->query($me_statement);
            $data['myfavorite'] = ($me_result->num_rows > 0) ? 1 : 0;
            $data['new_address'] = str_replace(" ","+",$data['address']);
            
            return array(
                'data' => $data,
                'code' => 200
            );

        } else {
            // Error, does not exist data
            return array(
                'data' => 'Error! There\'s something wrong!',
                'code' => 403
            );
        }
    }
    public function addRemoveFavorite($dormref, $user_id) {
        $statement = sprintf("SELECT * FROM `tbl_bookmarks` WHERE `dormref` = '%s' AND `userref` = '%s'", $dormref, $user_id);
        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            // Success, exist
            $me_statement = sprintf("DELETE FROM `tbl_bookmarks` WHERE `dormref` = '%s' AND `userref` = '%s'", $dormref, $user_id);
            $this->conn->query($me_statement);

            return array(
                'data' => 'Successfully removed!',
                'code' => 200
            );

        } else {
            // Success, not exist
            $me_statement = sprintf("INSERT INTO `tbl_bookmarks` (dormref, userref) VALUES('%s','%s')", $dormref, $user_id);
            $this->conn->query($me_statement);

            return array(
                'data' => 'Successfully added!',
                'code' => 200
            );
        }
    }
    public function addRemoveHide($dormref, $user_id) {
        $statement = sprintf("SELECT * FROM `tbl_dorms` WHERE `id` = '%s' AND `userref` = '%s'", $dormref, $user_id);
        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            // Success, exist
            $data = $result->fetch_assoc();
            
            if($data['hide'] == 0) {
                $me_statement = sprintf("UPDATE `tbl_dorms` SET `hide` = 1 WHERE `id` = '%s' AND `userref` = '%s'", $dormref, $user_id);
                $this->conn->query($me_statement);
            } else {
                $me_statement = sprintf("UPDATE `tbl_dorms` SET `hide` = 0 WHERE `id` = '%s' AND `userref` = '%s'", $dormref, $user_id);
                $this->conn->query($me_statement);
            }
            
            return array(
                'data' => 'Successfully!',
                'code' => 200
            );

        } else {
            return array(
                'data' => 'Error! Not exist data!',
                'code' => 403
            );
        }
    }
    public function get_dorm($userref) {
        $statement = sprintf("SELECT * FROM `tbl_dorms` WHERE `userref` = '%s'", $userref);
        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            // Success, exist
            $array = array();
            while($row = $result->fetch_assoc()) {
                $row['modal'] = false;
                $array[] = $row;
            }
            
            return array(
                'data' => $array,
                'code' => 200
            );

        } else {
            return array(
                'data' => 'Error! Not exist data!',
                'code' => 403
            );
        }
    }
}
?>