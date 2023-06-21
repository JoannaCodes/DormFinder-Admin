<?php
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Max-Age: 86400');
    header("Access-Control-Allow-Methods: POST");
    header("Content-Type: multipart/form-data");
    header("Content-Type: application/json");
    date_default_timezone_set('Asia/Manila');

    include_once "inc/conn.php";
    include_once "mod/api_queries.php";

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

    function conn()
    {
        $conn = new connection();
        $conn = $conn->mysqli_connect();
        $api = new api_queries($conn);
        return $api;
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

            if($action !== NULL)
            {
                switch($action) {
                    
                    case "getLastChatByID":
                        $result = conn()->getLastChatByID(_validate($_POST['chatroom_code']));
                        statusCode($result['code'], $result['data']);
                    break;
                    case "getChatrooms":
                        $result = conn()->getChatrooms(_validate($_POST['myid']));
                        statusCode($result['code'], $result['data']);
                    break;
                    case "getChats":
                        $result = conn()->getChats(_validate($_POST['chatroom_code']), _validate($_POST['myid']), _validate($_POST['itr']));
                        statusCode($result['code'], $result['data']);
                    break;
                    case "getPreviouslyChats":
                        $result = conn()->getPreviouslyChats(_validate($_POST['chatroom_code']), _validate($_POST['myid']), _validate($_POST['itr']));
                        statusCode($result['code'], $result['data']);
                    break;
                    case "sendChat":
                        $result = conn()->sendChat(_validate($_POST['chatroom_code']), _validate($_POST['myid']), _validate($_POST['message']), $_POST['image'] ?? "");
                        statusCode($result['code'], $result['data']);
                    break;
                    case "addChat":
                        $result = conn()->addChat(_validate($_POST['unique_code']), _validate($_POST['myid']), _validate($_POST['other_id']));
                        statusCode($result['code'], $result['data']);
                    break;
                    case "getDorm":
                        $result = conn()->getDorm(_validate($_POST['unique_code']));
                        statusCode($result['code'], $result['data']);
                    break;
                    case "getAmenities":
                        $result = conn()->getAmenities(_validate($_POST['unique_code']));
                        statusCode($result['code'], $result['data']);
                    break;
                    case "popular_dorm":
                        $result = conn()->popular_dorm(_validate($_POST['aircon'] ?? NULL), _validate($_POST['elevator'] ?? NULL), _validate($_POST['beddings'] ?? NULL), _validate($_POST['kitchen'] ?? NULL), _validate($_POST['laundry'] ?? NULL), _validate($_POST['lounge'] ?? NULL), _validate($_POST['parking'] ?? NULL), _validate($_POST['security'] ?? NULL), _validate($_POST['study_room'] ?? NULL), _validate($_POST['wifi'] ?? NULL), _validate($_POST['pet'] ?? NULL), _validate($_POST['visitor'] ?? NULL), _validate($_POST['curfew'] ?? NULL));
                        statusCode($result['code'], $result['data']);
                    break;
                    case "latest_dorm":
                        $result = conn()->latest_dorm(_validate($_POST['aircon'] ?? NULL), _validate($_POST['elevator'] ?? NULL), _validate($_POST['beddings'] ?? NULL), _validate($_POST['kitchen'] ?? NULL), _validate($_POST['laundry'] ?? NULL), _validate($_POST['lounge'] ?? NULL), _validate($_POST['parking'] ?? NULL), _validate($_POST['security'] ?? NULL), _validate($_POST['study_room'] ?? NULL), _validate($_POST['wifi'] ?? NULL), _validate($_POST['pet'] ?? NULL), _validate($_POST['visitor'] ?? NULL), _validate($_POST['curfew'] ?? NULL));
                        statusCode($result['code'], $result['data']);
                    break;
                    case "nearest_dorm":
                        $result = conn()->nearest_dorm(_validate($_POST['aircon'] ?? NULL), _validate($_POST['elevator'] ?? NULL), _validate($_POST['beddings'] ?? NULL), _validate($_POST['kitchen'] ?? NULL), _validate($_POST['laundry'] ?? NULL), _validate($_POST['lounge'] ?? NULL), _validate($_POST['parking'] ?? NULL), _validate($_POST['security'] ?? NULL), _validate($_POST['study_room'] ?? NULL), _validate($_POST['wifi'] ?? NULL), _validate($_POST['pet'] ?? NULL), _validate($_POST['visitor'] ?? NULL), _validate($_POST['curfew'] ?? NULL),_validate($_POST['latitude']), _validate($_POST['longitude']));
                        statusCode($result['code'], $result['data']);
                    break;
                    case "getMessageInfos":
                        $result = conn()->getMessageInfos(_validate($_POST['unique_code'] ?? NULL), _validate($_POST['myid'] ?? NULL), _validate($_POST['other_id'] ?? NULL));
                        statusCode($result['code'], $result['data']);
                    break;
                    case "checkLogin":
                        $result = conn()->checkLogin(_validate($_POST['email'] ?? NULL));
                        statusCode($result['code'], $result['data']);
                    break;
                    case "checkRegister":
                        $result = conn()->checkRegister(_validate($_POST['email'] ?? NULL), _validate($_POST['username'] ?? NULL), _validate($_POST['imageUrl'] ?? NULL));
                        statusCode($result['code'], $result['data']);
                    break;
                    default:
                        statusCode(200, "Welcome to DormFinder API");
                    break;
                }
            } else {
                statusCode(200, "Welcome to DormFinder API");
            }

        } else {
            // Unauthorized access
            statusCode(401, 'Invalid authentication key!');
        }
    }
    
?>