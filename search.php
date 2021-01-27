<?php
include("includes/includedFiles.php");

if (isset($_GET['term'])) {
    $term = urldecode($_GET['term']);
} else {
    $term = "";
}
?>


<div class="searchContainer">
    <img src="assets/images/icons/search.png" class="searchFieldImg">
    <input style="font-weight:700; color: #000; width: 400px; height: 40px; border-radius: 30px; padding: 20px 20px 20px 40px" placeholder="Search for Artists, Songs or Albums" type="text" class="searchInput" onfocus="var val=this.value; this.value=''; this.value= val;" value="<?php echo $term ?>">
</div>

<script>
    $(".searchInput").focus();

    $(function() {
        
        $(".searchInput").keyup(function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                var val = $(".searchInput").val();
                openPage("search.php?term=" + val);
            }, 1000)
        })
    })
</script>

<script>
    removeActiveClasses();
    $(".searchNav").addClass('active');
</script>

<?php if ($term == "") exit(); ?>

<div class="tracklistContainer borderBottom">
    <h2 style="text-align:center">SONGS</h2>
    <ul class="tracklist">
        <?php

        $songsQuery = mysqli_query($conn, "SELECT id FROM songs WHERE title LIKE '$term%' LIMIT 10");

        if (mysqli_num_rows($songsQuery) == 0) {
            echo "<span class='noResults'>No songs found matching " . $term . "</span>";
        }

        $songIdArray = array();

        $i = 1;
        while ($row = mysqli_fetch_array($songsQuery)) {

            if ($i > 15) {
                break;
            }

            array_push($songIdArray, $row['id']);

            $albumSong = new Song($conn, $row['id']);
            $songArtist = $albumSong->getArtist();
            $songAlbum = $albumSong->getAlbum();

            echo
            "<li class='tracklistRow'>
                    <div class='trackCount'>
                        <img src='assets/images/icons/play-white.png' class='play' onclick='setTrack(\"" . $albumSong->getId() . "\", tempPlaylist, true)'>
                        <span class='trackNumber'>$i</span>
                    </div>
                    <div class='trackInfo'>
                        <span class='trackName'>" . $albumSong->getTitle() . "</span>
                        <span class='artistName' role='link' tabindex='0' onclick='openPage(\"artist.php?id=" . $songArtist->getId() . "\")'>" . $songArtist->getName() . "</span>
                    </div>
                    <div class='trackOptions'>
                        <input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
                        <input type='hidden' class='artistId' value='" . $songArtist->getId() . "'>
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


<div class="gridViewContainer borderBottom">
    <h2 style="text-align:center">ARTISTS</h2>
    <?php
    $artistsQuery = mysqli_query($conn, "SELECT * FROM artists WHERE name LIKE '$term%' LIMIT 10");

    if (mysqli_num_rows($artistsQuery) == 0) {
        echo "<div style='margin-bottom: 14px'><span class='noResults'>No artists found matching " . $term . "</span></div>";
    }

    while ($artistInfo = mysqli_fetch_array($artistsQuery)) {
        echo "<div onclick='openPage(\"artist.php?id=" . $artistInfo['id'] . "\")' class='gridViewItem card gridViewItem2'>
                        <span role='link' tabindex='0'>
                            <img src='" . $artistInfo['profilePic'] . "'>
                            <div class='gridViewItemTitle'>"
            . $artistInfo['name'] .
            "</div>
                            <div class='gridViewItemArtist'>
                                Artist
                            </div>
                        </span>
                    </div>";
    }
    ?>
</div>


<div class="gridViewContainer">
    <h2 style="text-align:center">ALBUMS</h2>
    <?php
    $albumQuery = mysqli_query($conn, "SELECT * FROM albums WHERE title LIKE '$term%' LIMIT 10");

    if (mysqli_num_rows($albumQuery) == 0) {
        echo "<div style='margin-bottom: 14px'><span class='noResults'>No albums found matching " . $term . "</span></div>";
    }

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



