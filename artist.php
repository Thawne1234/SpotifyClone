<?php
include("includes/includedFiles.php");
if (isset($_GET['id'])) {
    $artistId = $_GET['id'];
} else {
    header("Location: index.php");
}

$artist = new Artist($conn, $artistId);
?>

<div class="entityInfo artistPageTopSection borderBottom">
    <!-- <img class="artistPageImage" src="<?php echo $artist->getProfilePicPath(); ?>"> -->
    <div class="centerSection">
        <div class="artistInfo">
            <h1 class="artistName" style="font-size: 60;"><?php echo $artist->getName(); ?></h1>
            <div class="headerButtons">
                <button class="button green" onclick="playFirstSong()">PLAY</button>
            </div>
        </div>

    </div>
</div>

<div class="tracklistContainer borderBottom">
    <h2 style="text-align:center">SONGS</h2>
    <ul class="tracklist">
        <?php
        $songIdArray = $artist->getSongIds();

        $i = 1;
        foreach ($songIdArray as $songId) {

            if ($i > 5) {
                break;
            }

            $albumSong = new Song($conn, $songId);
            $songAlbum = $albumSong->getAlbum();

            echo
            "<li class='tracklistRow'>
                    <div class='trackCount'>
                        <img src='assets/images/icons/play-white.png' class='play' onclick='setTrack(\"" . $albumSong->getId() . "\", tempPlaylist, true)'>
                        <span class='trackNumber'>$i</span>
                    </div>
                    <div class='trackInfo'>
                        <span class='trackName'>" . $albumSong->getTitle() . "</span>
                        <span class='artistName' role='link' tabindex='0' onclick='openPage(\"artist.php?id=" . $artist->getId() . "\")'>" . $artist->getName() . "</span>
                    </div>
                    <div class='trackOptions'>
                        <input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
                        <input type='hidden' class='artistId' value='" . $artist->getId() . "'>
                        <input type='hidden' class='albumId' value='" . $songAlbum->getId() . "'>
                        <img src='assets/images/icons/more.png' class='optionsButton' onclick='showOptionsMenu(this)'>
                    </div>
                    <div class='trackDuration'>
                        <span class='duration'>" . $albumSong->getDuration() . "</span>
                    </div>
                </li>";
            $i++;
        }
        ?>

        <script>
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlaylist = JSON.parse(tempSongIds);
        </script>
    </ul>
</div>


<div class="gridViewContainer">
    <h2 style="text-align:center">ALBUMS</h2>
    <?php
    $albumQuery = mysqli_query($conn, "SELECT * FROM albums WHERE artist='$artistId'");

    while ($row = mysqli_fetch_array($albumQuery)) {
        echo "<div class='gridViewItem card'>
                    <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
                        <img src='" . $row['artworkPath'] . "'>
                        <div class='gridViewItemTitle'  style='text-align: center;'>"
            . $row['title'] .
            "</div>
                    </span>
                </div>";
    }
    ?>
</div>


<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <input type="hidden" class="artistId">
    <input type="hidden" class="albumId">
    <?php echo Playlist::getPlaylistsDropdown($conn, $userLoggedIn->getUsername()); ?>
    <div class="item goToArtist">Go to artist</div>
    <div class="item goToAlbum">Go to album</div>
</nav>




<script>
    removeActiveClasses();
    $(".browse").addClass('active');
</script>