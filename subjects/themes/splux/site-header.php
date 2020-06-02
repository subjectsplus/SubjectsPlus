<?php
/**
 * Created by PhpStorm.
 * User: pvillanueva
 * Date: 06/02/20
 */
?>

<!--CONTENT for header-->
<nav class="navbar-expand-lg site-navbar-slim-nosliver">
    <div class="container position-relative">
        <a class="navbar-brand" href="<?php print $PublicPath; ?>">
            <img src="<?php print $AssetPath; ?>images/public/sp_logo.png" alt="logo" class="d-inline-block d-lg-block">
        </a>
        <button class="navbar-toggler js-sidebar-toggler d-block d-lg-none" data-button-options='{"modifiers":"left","wrapText":false}' aria-label="Toggle sidebar">
            <div class="nav-icon">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>
    </div>

    <div class="container">
        <div class="collapse navbar-collapse" id="siteNavigation" data-set="bs">
            <ul class="navbar-nav site-nav js-append-around">
                <li class="nav-item active">
                    <a class="nav-link no-decoration" href="<?php print $PublicPath; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-decoration" href="<?php print $PublicPath; ?>">Guides</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-decoration" href="<?php print $PublicPath; ?>databases.php">Databases</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-decoration" href="<?php print $PublicPath; ?>staff.php">Staff List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-decoration" href="<?php print $PublicPath; ?>faq.php">FAQs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-decoration" href="<?php print $PublicPath; ?>talkback.php">Talkback</a>
                </li>
            </ul>
        </div>
    </div>
</nav>