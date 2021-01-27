<?php
    include("includes/includedFiles.php");
    if (isset($_GET['id'])) {
        $albumId = $_GET['id'];
    }
    else {
        header("Location: index.php");
    }

    $album = new Album($conn, $albumId);
    $artist = $album->getArtist();
?>

<!--  <div id="mainContent"> -->
    <div class="entityInfo">
        <div class="leftSection">
            <img src="<?php echo $album->getArtworkPath()?>">
        </div>
        <div class="rightSection">
            <h2><?php echo $album->getTitle(); ?></h2>
            <p><span role='link' tabindex='0' onclick='openPage("artist.php?id=<?php echo $artist->getId(); ?>")'><?php echo $artist->getName(); ?></span></p>
            <p><?php echo $album->getNumberOfSongs(); ?> songs</p>
        </div>
    </div>
    <div class="tracklistContainer">
        <ul class="tracklist">
            <?php
            $songIdArray = $album->getSongIds();

            $i = 1;
            foreach ($songIdArray as $songId) {
                $albumSong = new Song($conn, $songId);

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
                        <input type='hidden' class='albumId' value='" . $album->getId() . "'>
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
<!--  </div> -->

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