<?php

    class User {
        private  $conn;
        private $username;
    
        public  function  __construct($conn, $username) {
            $this->conn = $conn;
            $this->username = $username;
        }

        public function getUsername() {
                return $this->username;
        }
        public function getFullName() {
                $query = mysqli_query($this->conn, "SELECT concat(firstName, ' ', lastName) AS 'name' FROM users WHERE username='$this->username'");
                $row = mysqli_fetch_array($query);
                return $row['name'];
        }
        public function getProfilePicPath() {
                $query = mysqli_query($this->conn, "SELECT profilePic FROM users WHERE username='$this->username'");
                $row = mysqli_fetch_array($query);
                return $row['profilePic'];
        }
        public function getEmail() {
                $query = mysqli_query($this->conn, "SELECT email FROM users WHERE username='$this->username'");
                $row = mysqli_fetch_array($query);
                return $row['email'];
        }
    }
?>