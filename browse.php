<!-- <script>console.log("browse.php render")</script> debug  -->


<?php 
include("includes/includedFiles.php");
// echo "<script>console.log(7)</script>"; debug
?>
<!--  <div id="mainContent"> -->

    <!-- <script>console.log(8)</script> -->

    <h1 class="pageHeadingBig">You Might Also Like</h1>
    <div class="gridViewContainer">
        <?php
            $albumQuery = mysqli_query($conn, "SELECT albums.id AS albumId, artworkPath, name, title, artists.id AS artistId FROM albums JOIN artists WHERE albums.artist = artists.id ORDER BY RAND() LIMIT 10");

            while($row = mysqli_fetch_array($albumQuery)) {
                echo "<div class='gridViewItem card'>
                        <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['albumId'] . "\")'>
                            <img src='" . $row['artworkPath'] . "'>
                            <div class='gridViewItemTitle'>"
                                . $row['title'] .
                            "</div>
                            <div class='gridViewItemArtist'>
                                "
                                . $row['name'] .
                                "
                            </div>
                        </span>
                    </div>";
            }
        ?>
    </div>
    <h1 class="pageHeadingBig">Popular Artists</h1>
    <div class="gridViewContainer">
        <?php
            $artistQuery = mysqli_query($conn, "SELECT * FROM artists ORDER BY RAND() LIMIT 5");

            while($artistInfo = mysqli_fetch_array($artistQuery)) {
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
<!--  </div> -->



<script>
    removeActiveClasses();
    $(".browse").addClass('active');
</script>