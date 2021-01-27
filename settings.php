<?php 
include("includes/includedFiles.php");
?>

<div style="height: 35vh; display:flex; flex-direction:column; justify-content:end" class="buttonItems">
    <button class="button" onclick="openPage('profile.php')">USER DETAILS</button>
    <button class="button" onclick="logout()">LOGOUT</button>
</div>

<div style="text-align:center; margin-top: 60px;">
    <img src="assets/images/icons/display.png" alt="">
</div>


<script>
    removeActiveClasses();
    $(".settings").addClass('active');
</script>