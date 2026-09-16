<?php

session_start();

require_once "includes/functions.php";


// ==========================================
// SEARCH QUERY
// ==========================================

$keyword = isset($_GET['q'])
    ? trim($_GET['q'])
    : '';

$results  = [];
$searched = false;

if (!empty($keyword)) {
    $searched = true;
    $results  = searchNews($keyword, 20);
}


// ==========================================
// SEO
// ==========================================

$siteName = getSetting('site_name', 'News Portal');

$pageTitle = $searched
    ? "Search: " . $keyword . " - " . $siteName
    : "Search News - " . $siteName;

$pageDescription =
    "Search for the latest news, breaking stories, and updates on " . $siteName;

?>

<?php include "includes/header.php"; ?>


<!-- =========================================
     SEARCH HEADER
========================================= -->

<div class="search-page-header">

    <div class="container">

        <h1 class="search-page-title">
            <i class="fa-solid fa-magnifying-glass"></i>
            Search News
        </h1>

        <form
            class="search-page-form"
            action="search.php"
            method="GET"
            id="searchForm"
        >

            <input
                type="search"
                name="q"
                id="searchInput"
                placeholder="Search for news, topics, categories..."
                value="<?= clean($keyword); ?>"
                required
                autocomplete="off"
            >

            <button type="submit" id="searchSubmitBtn">
                <i class="fa-solid fa-magnifying-glass"></i>
                Search
            </button>

        </form>

    </div>

</div>


<!-- =========================================
     SEARCH RESULTS
========================================= -->

<section class="section">

    <div class="container">

        <?php if ($searched): ?>

            <div class="search-results-header">

                <?php if (!empty($results)): ?>

                    <p class="search-results-count">
                        Found
                        <strong><?= count($results); ?></strong>
                        result<?= count($results) !== 1 ? 's' : ''; ?>
                        for "<em><?= clean($keyword); ?></em>"
                    </p>

                <?php else: ?>

                    <div class="empty-state">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <h3>No Results Found</h3>

                        <p>
                            No articles found matching
                            "<strong><?= clean($keyword); ?></strong>".
                            Try different keywords.
                        </p>

                    </div>

                <?php endif; ?>

            </div>


            <!-- RESULTS LIST -->

            <?php if (!empty($results)): ?>

                <div class="latest-news-list">

                    <?php foreach ($results as $news): ?>

                        <article class="news-list-card">


                            <!-- IMAGE -->

                            <div class="news-list-image">

                                <?php if (!empty($news['featured_image'])): ?>
                                    <img
                                        src="uploads/<?= clean($news['featured_image']); ?>"
                                        alt="<?= clean($news['title']); ?>"
                                    >
                                <?php else: ?>
                                    <img
                                        src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=500&q=80"
                                        alt="News"
                                    >
                                <?php endif; ?>

                            </div>


                            <!-- CONTENT -->

                            <div class="news-list-content">

                                <div class="category-label">
                                    <?= clean($news['category_name']); ?>
                                </div>

                                <h3 class="news-list-title">
                                    <a href="news.php?slug=<?= urlencode($news['slug']); ?>">
                                        <?= clean($news['title']); ?>
                                    </a>
                                </h3>

                                <p class="news-list-description">
                                    <?= clean(mb_substr($news['short_description'], 0, 200)); ?>
                                </p>

                                <div class="news-meta">

                                    <span>
                                        <i class="fa-regular fa-user"></i>
                                        <?= clean($news['author_name']); ?>
                                    </span>

                                    <span>
                                        <i class="fa-regular fa-clock"></i>
                                        <?= formatDate($news['published_at']); ?>
                                    </span>

                                    <span>
                                        <i class="fa-regular fa-eye"></i>
                                        <?= (int) $news['views']; ?>
                                    </span>

                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


        <?php else: ?>


            <!-- NOT YET SEARCHED - show latest -->

            <div class="section-header">
                <h2 class="section-title">Latest News</h2>
            </div>

            <?php $latestNews = getLatestNews(8); ?>

            <?php if (!empty($latestNews)): ?>

                <div class="row g-3">

                    <?php foreach ($latestNews as $news): ?>

                        <div class="col-md-6 col-lg-3">

                            <article class="news-card">

                                <a
                                    href="news.php?slug=<?= urlencode($news['slug']); ?>"
                                    class="news-card-image-link"
                                >
                                    <?php if (!empty($news['featured_image'])): ?>
                                        <img
                                            src="uploads/<?= clean($news['featured_image']); ?>"
                                            alt="<?= clean($news['title']); ?>"
                                            class="news-card-image"
                                        >
                                    <?php else: ?>
                                        <img
                                            src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=500&q=80"
                                            alt="News"
                                            class="news-card-image"
                                        >
                                    <?php endif; ?>
                                </a>

                                <div class="news-card-body">

                                    <span class="category-label">
                                        <?= clean($news['category_name']); ?>
                                    </span>

                                    <h3 class="news-card-title">
                                        <a href="news.php?slug=<?= urlencode($news['slug']); ?>">
                                            <?= clean($news['title']); ?>
                                        </a>
                                    </h3>

                                    <div class="news-meta">
                                        <span>
                                            <i class="fa-regular fa-clock"></i>
                                            <?= formatDate($news['published_at']); ?>
                                        </span>
                                    </div>

                                </div>

                            </article>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        <?php endif; ?>

    </div>

</section>

<?php include "includes/footer.php"; ?>
