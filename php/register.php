<?php
 
require_once('C:\wamp64\www\and_con\db_func.php');
$db = new db_func();
 
// json response array
$response = array("error" => FALSE);
 
 $_POST["name"] = "";
 $_POST["email"] = "";    
 $_POST["mobileNo"] = "";
 $_POST["password"] = "";
 $_POST["college"] = "";

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['mobileNo']) && isset($_POST['college'])) {
	$name = $_POST['name'];
    $email = $_POST['email'];
	
	$password = $_POST['password']; 								//unhashed password
	//$password = password_hash($pass,PASSWORD_DEFAULT);		 	// encrypted password
	$mobileNo = $_POST['mobileNo'];
	$college = $_POST['college'];
 
    // check if user is already existed with the same email
    if ($db->isUserExisted($mobileNo)) {
        $response["error"] = TRUE;
        $response["error_msg"] = "Username already exists with " . $mobileNo;
        echo json_encode($response);
        }else{                 
        $user = $db->storeUser($name, $email, $mobileNo, $college, $password);// create a new user
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["mobileNo"] = $user["mobileNo"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
			$response["user"]["password"] = $user["password"];
		//	$response["user"]["phoneNumber"] = $user["phone"];
            $response["user"]["collegeName"] = $user["college"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
            }   
        }
}else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email, password, collegename or phonenumber) is missing!";
    echo json_encode($response);
    }
?>