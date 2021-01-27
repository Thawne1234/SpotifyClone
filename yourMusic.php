<?php
    include("includes/includedFiles.php");
?>

<div class="playlistsContainer">
    <div class="gridViewContainer">
        <h2 style="font-size: 30; text-align: center">PLAYLISTS</h2>
        <div class="buttonItems">
            <button class="button green" onclick="createPlaylist()">NEW PLAYLIST</button>
        </div>

        <?php
            $username = $userLoggedIn->getUsername();
            $playlistsQuery = mysqli_query($conn, "SELECT * FROM playlists WHERE owner='$username'");

            if (mysqli_num_rows($playlistsQuery) == 0) {
                echo "<span class='noResult'>You don't have any playlists yet.</span>";
            }
            while($row = mysqli_fetch_array($playlistsQuery)) {
                $playlist = new Playlist($conn, $row);
                echo "<div style='padding: 0px' class='gridViewItem playlistCard' role='link' tabindex='0' onclick='openPage(\"playlist.php?id=" . $playlist->getId() . " \")'>
                        <div class='playlistImage'>
                            <img src='assets/images/icons/playlist.png'>
                        </div>
                        <div  style='margin: 10px 0; text-align: center' class='gridViewInfo'>"
                            . $playlist->getName() .
                        "</div>
                        </span>
                    </div>";
            }
        ?>

    </div>
</div>


<script>   
    removeActiveClasses();
    $(".yourNav").addClass('active');
</script>