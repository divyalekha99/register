<?php
require_once('C:\wamp64\www\and_con\db_func.php');
$db = new db_func();
 
// json response array
$response = array("error" => FALSE);
 
 $_POST["mobileNo"] = "";
 $_POST["password"] = "";
 
if (isset($_POST['mobileNo']) && isset($_POST['password'])) {
 
    $mobileNo = $_POST['mobileNo'];
    $password = $_POST['password'];
 
    // get the user by email and password
    $user = $db->getUserByEmailAndPassword($mobileNo, $password);
    if ($user != false) {
        // user is found
        $response["error"] = FALSE;
		
		$events = $db->getUserEvents($mobileNo);
		if($events != false){
		        $response["mobileNo"] = $events["mobileNo"];
				$response["events"]["e1"] = $events["e1"];
				$response["events"]["e2!"] = $events["e2"];
				$response["events"]["e3"] = $events["e3"];
				$response["events"]["e4"] = $events["e4"];
				$response["events"]["e5"] = $events["e5"];
				$response["events"]["e6"] = $events["e6"];
				$response["events"]["e7"] = $events["e7"];
				$response["events"]["e8"] = $events["e8"];
				$response["events"]["e9"] = $events["e9"];
				$response["events"]["e10"] = $events["e10"];
				$response["events"]["e11"] = $event["e11"];
				
				$response["mobileNo"] = $user["mobileNo"];
				$response["user"]["name"] = $user["name"];
				$response["user"]["email"] = $user["email"];
				$response["user"]["password"] = $user["password"];
				//$response["user"]["phoneNumber"] = $user["phone"];
				$response["user"]["collegeName"] = $user["college"];
		
        
		
        echo json_encode($response);
		}
    } else {
        // user is not found with the credentials
        $response["error"] = FALSE;
		
		$events = $db->getUserEvents($mobileNo);
		if($events != false){
		        $response["mobileNo"] = $events["mobileNo"];
				$response["events"]["e1"] = $events["e1"];
				$response["events"]["e2!"] = $events["e2"];
				$response["events"]["e3"] = $events["e3"];
				$response["events"]["e4"] = $events["e4"];
				$response["events"]["e5"] = $events["e5"];
				$response["events"]["e6"] = $events["e6"];
				$response["events"]["e7"] = $events["e7"];
				$response["events"]["e8"] = $events["e8"];
				$response["events"]["e9"] = $events["e9"];
				$response["events"]["e10"] = $events["e10"];
				$response["events"]["e11"] = $event["e11"];
				
				$response["mobileNo"] = $user["mobileNo"];
				$response["user"]["name"] = $user["name"];
				$response["user"]["email"] = $user["email"];
				$response["user"]["password"] = $user["password"];
				//$response["user"]["phoneNumber"] = $user["phone"];
				$response["user"]["collegeName"] = $user["college"];
		
        
		
        echo json_encode($response);
		}
        $response["error"] = TRUE;
        $response["error_msg"] = "Please enter valid login credentials.";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}
?>