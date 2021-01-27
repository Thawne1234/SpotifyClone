<?php
    include("includes/includedFiles.php");
    if (isset($_GET['id'])) {
        $playlistId = $_GET['id'];
    }
    else {
        header("Location: index.php");
    }

    $playlist = new Playlist($conn, $playlistId);
    $owner = new User($conn, $playlist->getOwner())
?>

<!--  <div id="mainContent"> -->
    <div class="entityInfo">
        <div class="leftSection">
            <img style="border: 3px solid #282828;" src="assets/images/icons/playlist.png">
        </div>
        <div class="rightSection">
            <h2><?php echo $playlist->getName(); ?></h2>
            <p>By <?php echo $playlist->getOwner();?></p>
            <p><?php echo $playlist->getNumberOfSongs(); ?> songs</p>
            <button style="width: 200px; margin-top: 20px" class="button" onclick="deletePlaylist('<?php echo $playlistId?>')">DELETE PLAYLIST</button>
        </div>
    </div>
    <div class="tracklistContainer">
        <ul class="tracklist">
            <?php
            $songIdArray = $playlist->getSongIds();

            if (!$songIdArray) {
                echo "<span class='noResult'>You don't have any songs in this playlist yet.</span>";
            }

            $i = 1;
            foreach ($songIdArray as $songId) {
                $playlistSong = new Song($conn, $songId);
                $songArtist = $playlistSong->getArtist();
                $songAlbum = $playlistSong->getAlbum();

                echo 
                "<li class='tracklistRow'>
                    <div class='trackCount'>
                        <img src='assets/images/icons/play-white.png' class='play' onclick='setTrack(\"" . $playlistSong->getId() . "\", tempPlaylist, true)'>
                        <span class='trackNumber'>$i</span>
                    </div>
                    <div class='trackInfo'>
                        <span class='trackName'>" . $playlistSong->getTitle() . "</span>
                        <span class='artistName' role='link' tabindex='0' onclick='openPage(\"artist.php?id=" . $songArtist->getId() . "\")'>" . $songArtist->getName() . "</span>
                    </div>
                    <div class='trackOptions'>
                        <input type='hidden' class='songId' value='" . $playlistSong->getId() . "'>
                        <input type='hidden' class='artistId' value='" . $songArtist->getId() . "'>
                        <input type='hidden' class='albumId' value='" . $songAlbum->getId() . "'>
                        <img src='assets/images/icons/more.png' class='optionsButton' onclick='showOptionsMenu(this)'>
                    </div>
                    <div class='trackDuration'>
                        <span class='duration'>" . $playlistSong->getDuration() . "</span>
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
    <div class="item" onclick="removeFromPlaylist(this, '<?php echo $playlistId; ?>')">Remove from playlist</div>
    <div class="item goToArtist">Go to artist</div>
    <div class="item goToAlbum">Go to album</div>
</nav>



<script>
    removeActiveClasses();
    $(".yourNav").addClass('active');
</script>