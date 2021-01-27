<?php
    class Artist {
        private $conn;
        private $id;

        public function __construct($conn, $id) {
            $this->conn = $conn;
            $this->id = $id;
        }

        public function getName()
        {
            $artistQuery = mysqli_query($this->conn, "SELECT name FROM artists WHERE id='$this->id'");
            $artist = mysqli_fetch_array($artistQuery);
            return $artist['name'];
        }
        public function getProfilePicPath()
        {
            $artistQuery = mysqli_query($this->conn, "SELECT * FROM artists WHERE id='$this->id'");
            $artist = mysqli_fetch_array($artistQuery);
            return $artist['profilePic'];
        }

        public function getSongIds() {
            $query = mysqli_query($this->conn, "SELECT id FROM songs WHERE artist='$this->id' ORDER BY plays DESC");
            $array = array();

            while($row = mysqli_fetch_array($query)) {
                array_push($array, $row['id']);
            }

            return $array;
        }



        public function getId()
        {
            return $this->id;
        }

    }
?>