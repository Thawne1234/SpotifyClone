var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;

$(document).click(function(click) {
    var target = $(click.target);
    if (!target.hasClass("item") && !target.hasClass("optionsButton")) {
        hideOptionsMenu();    
    }
})

$(window).scroll(function() {
    hideOptionsMenu();
})

$(document).on("change", "select.playlistSelector", function() {
    var select = $(this);
    var playlistId= select.val();
    var songId = select.prevAll(".songId").val();

    $.post("includes/handlers/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId })
    .done(function(error) {
        if (error != "") {
            alert(error);
            return;
        }

        hideOptionsMenu();
        select.val("");

    });
})

$(document).on("click", ".goToArtist", function() {
    var artistId = $(this).prevAll(".artistId").val();
    openPage("artist.php?id=" + artistId);
})

$(document).on("click", ".goToAlbum", function() {
    var albumId = $(this).prevAll(".albumId").val();
    openPage("album.php?id=" + albumId);
})

function updatePassword(oldPasswordClass, newPasswordClass1,newPasswordClass2) {
    var oldPassword = $("." + oldPasswordClass).val();
    var newPassword1 = $("." + newPasswordClass1).val();
    var newPassword2 = $("." + newPasswordClass2).val();
    $.post("includes/handlers/ajax/updatePassword.php",{
        oldPassword: oldPassword,
        newPassword1: newPassword1,
        newPassword2: newPassword2,
        username: userLoggedIn
    })
    .done(function(response) {
        $("." + oldPasswordClass).nextAll(".message").text(response);
        $("." + oldPasswordClass).val("");
        $("." + newPasswordClass1).val("");
        $("." + newPasswordClass2).val("");
    })
}

function logout() {
    $.post("includes/handlers/ajax/logout.php", function() {
        location.reload();
    })
}


window.addEventListener("popstate", function() {
    var url = location.href;
    openPagePushState(url);
})

function openPagePushState(url) {
    if(timer !== null) {
      clearTimeout(timer);
    }
    if(url.indexOf("?") === -1) {
      url = url + "?";
    }
    var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    $("#mainContent").load(encodedUrl);
    $("body").scrollTop(0);
}

function openPage(url) {
    openPagePushState(url);
    history.pushState(null, null, url);
}

function removeFromPlaylist(button, playlistId) {
    var songId = $(button).prevAll(".songId").val();
    $.post("includes/handlers/ajax/removeFromPlaylist.php", { playlistId: playlistId, songId: songId })
    .done(function(error) {
        if (error != "") {
            alert(error);
            return;
        }
        openPage("playlist.php?id=" + playlistId);
    });
}

function createPlaylist() {
    var popup = prompt("Please enter the name of your playlist");
    if (popup != null) {
        $.post("includes/handlers/ajax/createPlaylist.php", {name: popup, username: userLoggedIn})
        .done(function(error) {
            if (error != "") {
                alert(error);
                return;
            }
            openPage("yourMusic.php");
        });
    }
}
function deletePlaylist(playlistId) {
    var popup = confirm("Are you sure you want to delete this playlist?");
    if (popup == true) {
        $.post("includes/handlers/ajax/deletePlaylist.php", { playlistId: playlistId })
        .done(function(error) {
            if (error != "") {
                alert(error);
                return;
            }
            openPage("yourMusic.php");
        });
    }
}
function hideOptionsMenu(button) {
    var menu = $(".optionsMenu");
    if (menu.css("display") != "none") {
        menu.css("display", "none")
    }
}

function showOptionsMenu(button) {
    var songId = $(button).prevAll(".songId").val();
    var artistId = $(button).prevAll(".artistId").val();
    var albumId = $(button).prevAll(".albumId").val();
    var menu = $(".optionsMenu");
    var menuWidth = menu.width();
    menu.find(".songId").val(songId);
    menu.find(".artistId").val(artistId);
    menu.find(".albumId").val(albumId);
    var scrollTop = $(window).scrollTop();
    var elementOffset = $(button).offset().top;
    var top = elementOffset - scrollTop;
    var left = $(button).position().left;
    menu.css({ "top": top + "px", "right": left + menuWidth / 2 + $(button).width()*2 + "px", "display": "inline" });

}


function formatTime(seconds) {
    var time = Math.round(seconds);
    var minutes = Math.floor(time / 60);
    var seconds = time - minutes*60;

    var extraZero;

    if (seconds < 10) {
        extraZero = "0";
    }
    else {
        extraZero="";
    }

    return minutes + ":" + extraZero + seconds;
}

function updateTimeProgressBar(audio) {
    $(".progressTime.current").text(formatTime(audio.currentTime));

    var progress = audio.currentTime / audio.duration * 100;
    $(".playbackBar .progress").css("width", progress + "%");
}

function updateVolumeProgressBar(audio) {
    var volume = audio.volume * 100;
    $(".volumeBar .progress").css("width", volume + "%");
}

function playFirstSong() {
    setTrack(tempPlaylist[0], tempPlaylist, true);
}

function Audio() {
    this.currentlyPlaying;
    this.audio =  document.createElement('audio');
    this.setTrack = function(track) {
        this.currentlyPlaying = track;
        this.audio.src = track.path;
    }

    this.audio.addEventListener("ended", function() {
        nextSong();
    });

    this.audio.addEventListener("canplay", function() {
        $(".progressTime.remaining").text(formatTime(this.duration));
    });
    
    this.audio.addEventListener("timeupdate", function() {
        if (this.duration) {
            updateTimeProgressBar(this);
        }
    });
    
    this.audio.addEventListener("volumechange", function() {
        if (this.duration) {
            updateVolumeProgressBar(this);
        }
    });

    this.play = function() {
        this.audio.play();
    }

    this.pause = function() {
        this.audio.pause();
    }

    this.setTime = function (seconds) {
        this.audio.currentTime = seconds;
    }
}