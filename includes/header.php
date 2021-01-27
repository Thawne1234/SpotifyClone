<?php
include("includes/config.php");
include("includes/classes/User.php");
include("includes/classes/Artist.php");
include("includes/classes/Album.php");
include("includes/classes/Song.php");
include("includes/classes/Playlist.php");

// echo "<script>console.log('entered')</script>";

if (isset($_SESSION['userLoggedIn'])) {
    // echo "<script>console.log('exited');</script>";
    $userLoggedIn = new User($conn, $_SESSION['userLoggedIn']);
    $username = $userLoggedIn->getUsername();
    echo "<script>userLoggedIn = '$username'</script>";
} else {
    header("Location: auth.php");
}

?>

<html lang="en">

<head>
    <title>Welcome To Spotify</title>
    <link rel="stylesheet" type="text/css" href="assets/css/index.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>

<body>
    <div class="mainContainer">
        <div class="topContainer">
            <?php include("includes/containers/navbarContainer.php") ?>
            <div id="mainViewContainer">
                <div id="mainContent">