<?php
class api_queries
{
	private $conn;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}
    
    public function getLastChatByID($unique_code) {
        $statement = sprintf("SELECT * FROM `tbl_chats` WHERE `unique_code` = '%s' ORDER BY id DESC", $unique_code);
        $result = $this->conn->query($statement);
        $row = $result->fetch_assoc();
        return array(
            'data' => (int) $row['itr'],
            'code' => 200
        );
    }

    public function getChatrooms($to_user) {
        $statement = sprintf("SELECT tbl_users.id, tbl_users.username, tbl_chatrooms.unique_code, tbl_users.imageUrl FROM tbl_chatrooms INNER JOIN tbl_users ON tbl_chatrooms.from_user = tbl_users.id  WHERE to_user = '%s' ORDER BY tbl_chatrooms.id DESC", $to_user);

        $result = $this->conn->query($statement);

        if ($result->num_rows > 0) {
            $array = array();
            $count = 1;
            while($row = $result->fetch_assoc()) {
                $statement2 = sprintf("SELECT * FROM `tbl_chats` WHERE `unique_code` = '%s' ORDER BY id DESC", $row['unique_code']);
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
                    $username .= $row['username'];
                    $array[] = array(
                        'id' => $count,
                        'user_id' => $row['id'],
                        'username' => $username,
                        'unique_code' => $row['unique_code'],
                        'imageUrl' => $row['imageUrl'],
                        'message' => $whoFirst,
                        'time' => $row2['time'] ?? 0
                    );
                } else {
                    $statement3 = sprintf("SELECT * FROM `tbl_dorms` WHERE `id` = '%s'", $row['unique_code']);
                    $result3 = $this->conn->query($statement3);
                    $row3 = $result3->fetch_assoc();

                    $username = ($row3['name'] ?? "");
                    $username .= (($row3['name'] ?? NULL) ? " - " : "");
                    $username .= $row['username'];
                    $array[] = array(
                        'id' => $count,
                        'user_id' => $row['id'],
                        'username' => $username,
                        'unique_code' => $row['unique_code'],
                        'imageUrl' => $row['imageUrl'],
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

    public function getChats($unique_code, $myId, $itr) {
        $statement = sprintf("SELECT tbl_chats.id as id, tbl_chats.itr, tbl_users.id as user_id, tbl_users.username, tbl_users.imageUrl, tbl_chats.message, tbl_chats.image, tbl_chats.time FROM tbl_chats INNER JOIN tbl_users ON tbl_chats.user_id = tbl_users.id WHERE unique_code = '%s' AND `itr` > '".$itr."' ORDER BY tbl_chats.id DESC LIMIT 10", $unique_code);

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

    public function getPreviouslyChats($unique_code, $myId, $itr) {
        $statement = sprintf("SELECT tbl_chats.id as id, tbl_chats.itr, tbl_users.id as user_id, tbl_users.username, tbl_users.imageUrl, tbl_chats.message, tbl_chats.image, tbl_chats.time FROM tbl_chats INNER JOIN tbl_users ON tbl_chats.user_id = tbl_users.id WHERE unique_code = '%s' AND `itr` < '".$itr."' ORDER BY tbl_chats.id DESC LIMIT 10", $unique_code);

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

    public function sendChat($unique_code, $myId, $message, $image) {
        $getStatement = sprintf("SELECT * FROM `tbl_chats` WHERE `unique_code` = '%s' ORDER BY id DESC", $unique_code);
        $getResult = $this->conn->query($getStatement);
        $getRow = $getResult->fetch_assoc();

        if(strlen($image) != 0) {
            $statement = sprintf("INSERT INTO tbl_chats (unique_code,user_id,`image`,`time`,`itr`) VALUES ('%s','%s','%s', %d, %d)", $unique_code, $myId, $this->base64ToImage($image, $unique_code), time(), $getRow['itr'] + 1);
            $this->conn->query($statement);
        } else {
            $statement = sprintf("INSERT INTO tbl_chats (unique_code,user_id,`message`,`time`,`itr`) VALUES ('%s','%s','%s', %d, %d)", $unique_code, $myId, $message, time(), $getRow['itr'] + 1);
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
            // Success, does not exist data
            $statement2 = sprintf("INSERT INTO tbl_chatrooms (`unique_code`,`to_user`,`from_user`,`time`) VALUES ('%s','%s','%s', %d)", $unique_code, $myid, $other_id, time());
            $this->conn->query($statement2);

            $statement3 = sprintf("INSERT INTO tbl_chatrooms (`unique_code`,`to_user`,`from_user`,`time`) VALUES ('%s','%s','%s', %d)", $unique_code, $other_id, $myid, time());
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
}
?>