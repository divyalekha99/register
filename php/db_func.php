<?php
 
class db_func {
 
    private $conn;
 
    function __construct() {
        require_once('C:\wamp64\www\and_con\db_conn.php');
        $db = new db_conn();
        $this->conn = $db->connect();
    }
 
    function __destruct() {
         
    }
 
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $mobileNo, $college, $password) {
        $uuid = uniqid('', true);
		$singleevent = 0;
      
 
        $stmt = $this->conn->prepare("INSERT INTO users(name, email, mobileNo, college, password, created_at, updated_at)VALUES(?,?,?,?,?,NOW(),NOW())");
        $stmt->bind_param("sssss", $name, $email, $mobileNo, $college, $password);
        $result = $stmt->execute();
        $stmt->close();
        
		$user = $this->getUserByEmailAndPassword($mobileNo, $password);
		$uno = $user['mobileNo'];
		
		$stmt1 = $this->conn->prepare("INSERT INTO events(mobileNo, e1, e2, e3, e4, e5, e6, e7, e8, e9, e10, e11, created_at, updated_at) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())");
        $stmt1->bind_param("iiiiiiiiiiii", $uno, $singleevent, $singleevent, $singleevent, $singleevent, $singleevent, $singleevent, $singleevent, $singleevent, $singleevent, $singleevent, $singleevent);
        $result1 = $stmt1->execute();
        $stmt1->close();
 
        // check for successful store
        if ($result && $result1) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE mobileNo = ?");
            $stmt->bind_param("s", $mobileNo);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            return $user;
        } else {
            return false;
        }
    }
	
 	public function getUserByEmailAndPassword($mobileNo, $password) {
		
		$stmt = $this->conn->prepare("SELECT * FROM users WHERE mobileNo = ?");
		$stmt->bind_param("s", $mobileNo);
		
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
			
            // verifying user password
			$dbPass = $user['password'];
		
            // check for password equality
	
            if(password_verify($password,$dbPass)) {
                // user authentication details are correct
               return $user;
            }
        } else {
            return NULL;
        }
    }
	
	public function getUserEvents($mobileNo) {
		
		$user = $this->getUserByEmail($mobileNo);
		$uno = $user['mobileNo'];
 
        $stmt = $this->conn->prepare("SELECT * FROM events WHERE mobileNo = ?");
 
        $stmt->bind_param("i", $uno);
 
        if ($stmt->execute()) {
            $events = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            echo json_encode($events);
			return $events;

            }
		else {
            return NULL;
        }
    }
 
    /**
     * Check user exists or not
     */
    public function isUserExisted($mobileNo) {
        $stmt = $this->conn->prepare("SELECT mobileNo from users WHERE mobileNo = ?");
 
        $stmt->bind_param("s", $mobileNo);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
		
	 public function getUserByEmail($mobileNo) {
 
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE mobileNo = ?");
 
        $stmt->bind_param("s", $mobileNo);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
			
			$mobile = $user['mobileNo'];
			
            // check for e-mail equality
            if ($mobile == $mobileNo) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }
 
}
 