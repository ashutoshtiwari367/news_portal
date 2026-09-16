<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/functions.php';

$siteName = getSetting('site_name', 'News Portal');
$siteTagline = getSetting(
    'site_tagline',
    'Latest News & Breaking Updates'
);

$categories = getCategories();

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= clean($siteName); ?>
    </title>

    <meta
        name="description"
        content="<?= clean($siteTagline); ?>"
    >

    <meta
        name="robots"
        content="index, follow"
    >

    <!-- Bootstrap 5 -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- Google Font -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- Main CSS -->

    <?php

    $basePath = '';

    if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
        $basePath = '../';
    }

    ?>

    <link
        rel="stylesheet"
        href="<?= $basePath; ?>assets/css/style.css"
    >

</head>

<body>

<!-- =========================================
     TOP BAR
========================================= -->

<div class="top-bar">

    <div class="container">

        <div class="top-bar-content">

            <div>
                <i class="fa-regular fa-calendar"></i>

                <?= date('l, d F Y'); ?>
            </div>

            <div class="top-links">

                <a href="#">
                    About Us
                </a>

                <a href="#">
                    Contact
                </a>

                <a href="#">
                    Advertise
                </a>

            </div>

        </div>

    </div>

</div>


<!-- =========================================
     MAIN HEADER
========================================= -->

<header class="main-header">

    <div class="container">

        <div class="header-content">

            <!-- LOGO -->

            <div class="site-brand">

                <a
                    href="<?= $basePath; ?>index.php"
                    class="logo-link"
                >

                    <div class="logo-icon">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>

                    <div class="logo-text">

                        <div class="site-name">
                            <?= clean($siteName); ?>
                        </div>

                        <div class="site-tagline">
                            <?= clean($siteTagline); ?>
                        </div>

                    </div>

                </a>

            </div>


            <!-- HEADER AD -->

            <div class="header-ad">

                <span>
                    Advertisement
                </span>

            </div>


            <!-- MOBILE MENU BUTTON -->

            <button
                class="mobile-menu-btn"
                type="button"
                onclick="toggleMobileMenu()"
            >

                <i class="fa-solid fa-bars"></i>

            </button>

        </div>

    </div>

</header>


<!-- =========================================
     NAVIGATION
========================================= -->

<nav class="main-navigation">

    <div class="container">

        <div
            class="navigation-wrapper"
            id="mobileNavigation"
        >

            <ul class="nav-menu">

                <li>

                    <a
                        href="<?= $basePath; ?>index.php"
                        class="<?= $currentPage === 'index.php' ? 'active' : ''; ?>"
                    >

                        <i class="fa-solid fa-house"></i>

                        Home

                    </a>

                </li>


                <?php foreach ($categories as $category): ?>

                    <li>

                        <a
                            href="<?= $basePath; ?>category.php?slug=<?= urlencode($category['slug']); ?>"
                        >

                            <?= clean($category['name']); ?>

                        </a>

                    </li>

                <?php endforeach; ?>

            </ul>


            <!-- SEARCH -->

            <form
                class="header-search"
                action="<?= $basePath; ?>search.php"
                method="GET"
            >

                <input
                    type="search"
                    name="q"
                    placeholder="Search news..."
                    aria-label="Search news"
                    required
                >

                <button type="submit">

                    <i class="fa-solid fa-magnifying-glass"></i>

                </button>

            </form>

        </div>

    </div>

</nav>


<!-- =========================================
     BREAKING NEWS
========================================= -->

<?php

$breakingNews = getBreakingNews(10);

if (!empty($breakingNews)):

?>

<div class="breaking-wrapper">

    <div class="container">

        <div class="breaking-news">

            <div class="breaking-label">

                <span class="breaking-dot"></span>

                BREAKING

            </div>


            <div class="breaking-content">

                <div class="breaking-track">

                    <?php foreach ($breakingNews as $breaking): ?>

                        <a
                            href="<?= clean($breaking['link']); ?>"
                            class="breaking-item"
                        >

                            <?= clean($breaking['title']); ?>

                        </a>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>

    </div>

</div>

<?php endif; ?>


<!-- =========================================
     MAIN CONTENT START
========================================= -->

<main class="main-content">