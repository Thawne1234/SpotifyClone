<?php
    include("includes/includedFiles.php");
?>

<!--  <div id="mainContent"> -->
    <div style="background-color: #333; padding-bottom:20px; margin-left: -20px; width: calc(100vw - 220px)" class="entityInfo borderBottom">
        <div  style="margin-left: 40px; width: 40%" class="leftSection">
            <img style="border-radius: 50%;" src="<?php echo $userLoggedIn->getProfilePicPath()?>">
        </div>
        <div style="width: 50%;" class="rightSection">
            <p>PROFILE</p>
            <h2 style="letter-spacing: 0px; margin-bottom: 0px; font-weight: 900;"><?php echo $userLoggedIn->getFullName(); ?></h2>
        </div>
    </div>

    
    <div class="container borderBottom">
        <div style="text-align:center; width: 100%">
            <h2 style="display: block; font-size: 15px; margin-top: 15px; color: #a0a0a0">EMAIL</h2>
            <div style="display: inline; margin-top:0px"><?php echo $userLoggedIn->getEmail()?></div>
            <img style="height: 16px; width: 16px; margin-left: 5px" src="assets/images/icons/tick.png" alt="">
            <span style="display:block; padding-bottom: 30px;" class="message"></span>
        </div>
    </div>

    <div style="padding: 20px;" class="container">
        <div style="text-align: center;">
            <h2 style="display: block; font-size: 15px; margin-top: 15px; color: #a0a0a0">PASSWORD</h2>
            <input class="oldPassword" name="oldPassword" type="password" placeholder="Current password">
            <input class="newPassword1" name="newPassword1" type="password" placeholder="New password">
            <input class="newPassword2" name="newPassword2" type="password" placeholder="Confirm password">
            <span style="display: block; margin-bottom: 15px; color: #2ebd59; font-weight: 300" class="message"></span>
            <button class="button" onclick="updatePassword('oldPassword','newPassword1','newPassword2')">UPDATE</button>
        </div>
    </div>

    <script>
    $(".oldPassword").focus(function() {
        $(".message").text("");
    })
    $(".newPassword1").focus(function() {
        $(".message").text("");
    })
    $(".newPassword2").focus(function() {
        $(".message").text("");
    })
    </script>


<script>
    removeActiveClasses();
    $(".settings").addClass('active');
</script>
