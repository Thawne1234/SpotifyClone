<div id="navBarContainer">
    <nav class="navBar">
        <span role="link" tabindex="0" onclick="openPage('index.php')" class="logo" >
            <img src="assets/images/icons/logo.png" alt="logo">
        </span>

        <div class="group">
            <div class="navItem highlighter searchNav">
                <span role="link" tabindex="0" onclick="openPage('search.php')"  class="navItemLink">
                    Search
                    <img src="assets/images/icons/search.png" alt="search" class="searchIcon">
                </span>
            </div>
        </div>
        <div class="group">
            <div class="navItem highlighter browse active">
                <span role="link" tabindex="0" onclick="openPage('browse.php')" class="navItemLink">Browse</span>
            </div>
            <div class="navItem highlighter yourNav">
                <span role="link" tabindex="0" onclick="openPage('yourMusic.php')" class="navItemLink">Your Music</span>
            </div>
            <div class="navItem highlighter settings">
                <span role="link" tabindex="0" onclick="openPage('settings.php')" class="navItemLink"><?php echo $userLoggedIn->getFullName()?></span>
            </div>

        </div>
    </nav>
</div>


<script>
    const navItems = document.querySelectorAll('.highlighter')
    const logo = document.querySelector('.logo');

    logo.addEventListener('click', () => {
            removeActiveClasses();
            $(".browse").addClass('active');
    })    



    function removeActiveClasses() {
        navItems.forEach((navItem) => {
            navItem.classList.remove('active')
        })
    }
</script>