<?php
class db_conn {
    private $conn;
 
    public function connect() {
      //  require_once 'include/Config.php';
         
        $this->conn = new mysqli("localhost", "root", "", "newdb");
         
        return $this->conn;
    }
}
 
?>