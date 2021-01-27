<?php

if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {

    // echo "<script>console.log(6)</script>"; debug
    
    include("includes/config.php");
    include("includes/classes/User.php");
    include("includes/classes/Artist.php");
    include("includes/classes/Album.php");
    include("includes/classes/Song.php");
    include("includes/classes/Playlist.php");

    if (isset($_GET['userLoggedIn'])) {
        // echo "<script>console.log('see')</script>";
        // echo "<script>console.log('here  is why: ', userLoggedIn)</script>";

        $userLoggedIn = new User($conn, $_GET['userLoggedIn'] );
    }

    else {
        // echo "<script>console.log('see2')</script>";
        // echo "<script>console.log('here  is why: ', userLoggedIn)</script>";
        exit();
    }
    
}
else {
    // echo "<script>console.log(1)</script>"; debug
    include("includes/header.php");

    // echo "<script>console.log(2)</script>"; debug

    include("includes/footer.php");
    
    // echo "<script>console.log(3)</script>"; debug

    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";

    // echo "<script>console.log(5)</script>"; debug

    exit();
}
?>