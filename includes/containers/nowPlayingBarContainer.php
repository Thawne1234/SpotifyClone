<?php
    $songQuery = mysqli_query($conn, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

    $resultArray = array();

    while ($row = mysqli_fetch_array($songQuery)) {
        array_push($resultArray, $row['id']);
    }

    $jsonArray = json_encode($resultArray);
    
?>

<script>
    $(document).ready(function() {
        var newPlaylist = <?php echo $jsonArray?>;
        audioElement = new Audio();
        setTrack(newPlaylist[0], newPlaylist, false);
        updateVolumeProgressBar(audioElement.audio);


        $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e) {
            e.preventDefault();
        });

        $(".playbackBar .progressBar").mousedown(function() {
            mouseDown = true;
        });
        $(".playbackBar .progressBar").mousemove(function(e) {
            if (mouseDown == true) {
                timeFromOffset(e, this);
            }
        });
        $(".playbackBar .progressBar").mouseup(function(e) {
                timeFromOffset(e, this);
        });

        $(".volumeBar .progressBar").mousedown(function() {
            mouseDown = true;
        });
        $(".volumeBar .progressBar").mousemove(function(e) {
            if (mouseDown == true) {
                var fraction = e.offsetX / $(this).width();
                if (fraction >= 0 && fraction <= 1) {
                    audioElement.audio.volume = fraction;
                }
            }
        });

        $(".volumeBar .progressBar").mouseup(function(e) {
            var fraction = e.offsetX / $(this).width();
            if (fraction >= 0 && fraction <= 1) {
                audioElement.audio.volume = fraction;
            }
        });

        $(document).mouseup(function() {
            mouseDown = false;
        })
    })

    function timeFromOffset(mouse, progressBar) {
        var percentage = mouse.offsetX / $(progressBar).width() * 100;
        var seconds = audioElement.audio.duration * (percentage / 100);
        audioElement.setTime(seconds);
    }

    function prevSong() {

        if (repeat == true) {
            audioElement.setTime(0);
            playSong();
            return;
        }
        
        if (audioElement.audio.currentTime >= 3) {
            audioElement.setTime(0);
            playSong();
        }

        else if( currentIndex == 0) {
            currentIndex = currentPlaylist.length - 1;
            var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
            setTrack(trackToPlay, currentPlaylist, true);
        }

        else {
            currentIndex--;
            var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
            setTrack(trackToPlay, currentPlaylist, true);
        }

    }

    function nextSong() {

        if (repeat == true) {
            audioElement.setTime(0);
            playSong();
            return;
        }

        if (currentIndex == currentPlaylist.length - 1) {
            currentIndex = 0;
        }

        else {
            currentIndex++;
        }

        var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
        setTrack(trackToPlay, currentPlaylist, true);
    }

    function toggleRepeat() {
        repeat = !repeat;
        var imageName = repeat ? "repeat-active.png" : "repeat.png";
        $(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
    }
    function toggleShuffle() {
        shuffle = !shuffle;
        var imageName = shuffle ? "shuffle-active.png" : "shuffle.png";
        $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);

        if (shuffle == true) {
            shuffleArray(shufflePlaylist);
            currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
        }

        else {
            //regularize
            currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);

        }
    }

    function shuffleArray(a) {
        var j, x, i;
        for (i = a.length; i; i--) {
            j = Math.floor(Math.random() * i);
            x = a[i -1];
            a[i - 1] = a[j];
            a[j] = x;
        }
    }

    function toggleMute() {
        audioElement.audio.muted = !audioElement.audio.muted;
        var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
        $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
    }

    function setTrack(trackId, newPlaylist, play) {

        if (newPlaylist != currentPlaylist) {
            currentPlaylist = newPlaylist;
            shufflePlaylist = currentPlaylist.slice();
            shuffleArray(shufflePlaylist);
        }

        if (shuffle == true) {
            currentIndex = shufflePlaylist.indexOf(trackId);
        }
        else {
            currentIndex = currentPlaylist.indexOf(trackId);
        }

        pauseSong();

        $.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {

            var track = JSON.parse(data);
            $(".trackName span").text(track.title);

            $.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
                var artist = JSON.parse(data);
                $(".trackInfo .artistName span").text(artist.name);
                $(".trackInfo .artistName span").attr("onclick", "openPage('artist.php?id=" + artist.id +  "')");
            });

            $.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {
                var album = JSON.parse(data);
                $(".content .albumLink img").attr("src", album.artworkPath);
                $(".content .albumLink img").attr("onclick", "openPage('album.php?id=" + album.id +  "')");
                // $(".trackName span").attr("onclick", "openPage('album.php?id=" + album.id +  "')");
            });

            audioElement.setTrack(track);
            // updateTimeProgressBar(audioElement.audio);
            if (play == true) {
                playSong();
            }
        });
    }

    function playSong() {
        audioElement.play();

        if (audioElement.audio.currentTime == 0) {
            $.post("includes/handlers/ajax/updatePlays.php", { songId: audioElement.currentlyPlaying.id });
        }

        $(".controlButton.play").hide();
        $(".controlButton.pause").show();
    }

    function pauseSong() {
        audioElement.pause();
        $(".controlButton.play").show();
        $(".controlButton.pause").hide();
    }

</script>

<div id="nowPlayingBarContainer">
    <div id="nowPlayingBar">
        <div id="nowPlayingLeft">
            <div class="content">
                <span class="albumLink">
                    <img role="link" tabIndex="0" src="" class="albumArtwork">
                </span>
                <div class="trackInfo">
                    <span class="trackName">
                        <span></span>
                    </span>
                    <span class="artistName">
                        <span role="link" tabIndex="0"></span>
                    </span>
                </div>
            </div>
        </div>
        <div id="nowPlayingCenter">
            <div class="content playerControls">
                <div class="buttons">
                    <button class="controlButton shuffle" title="Shuffle" onclick="toggleShuffle()"><img src="assets/images/icons/shuffle.png" alt="shuffle"></button>
                    <button class="controlButton previous" title="Previous" onclick="prevSong()"><img src="assets/images/icons/previous.png" alt="previous"></button>
                    <button class="controlButton play" title="Play" onclick="playSong()"><img src="assets/images/icons/play.png" alt="play"></button>
                    <button class="controlButton pause" title="Pause" onclick="pauseSong()" style="display: none;"><img src="assets/images/icons/pause.png" alt="pause"></button>
                    <button class="controlButton next" title="Next" onclick="nextSong()"><img src="assets/images/icons/next.png" alt="next"></button>
                    <button class="controlButton repeat" title="Repeat" onClick="toggleRepeat()"><img src="assets/images/icons/repeat.png" alt="repeat"></button>
                </div>
                <div class="playbackBar">
                    <span class="progressTime current">0.00</span>
                    <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress"></div>
                        </div>
                    </div>
                    <span class="progressTime remaining">0.00</span>
                </div>
            </div>
        </div>
        <div id="nowPlayingRight">
            <div class="volumeBar">
                <button class="controlButton volume" title="Volume" onclick="toggleMute()"><img src="assets/images/icons/volume.png" alt="volume"></button>
                <div class="progressBar">
                    <div class="progressBarBg">
                        <div class="progress"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>