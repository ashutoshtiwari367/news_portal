<?php

session_start();

require_once "includes/functions.php";


// ==========================================
// GET SLUG
// ==========================================

$slug = isset($_GET['slug'])
    ? trim($_GET['slug'])
    : '';

if (empty($slug)) {
    redirect("index.php");
}


// ==========================================
// GET NEWS
// ==========================================

$news = getNewsBySlug($slug);

if (!$news) {
    redirect("index.php");
}


// ==========================================
// INCREASE VIEWS
// ==========================================

increaseNewsViews($news['id']);


// ==========================================
// RELATED NEWS
// ==========================================

$relatedNews = getNewsByCategory(
    $news['category_id'],
    4
);

// Remove current article from related
$relatedNews = array_filter(
    $relatedNews,
    fn($n) => $n['id'] !== $news['id']
);

$relatedNews = array_slice(
    array_values($relatedNews),
    0,
    3
);


// ==========================================
// SEO
// ==========================================

$pageTitle       = $news['title'];
$pageDescription = mb_substr(
    strip_tags($news['short_description'] ?? ''),
    0,
    160
);

?>

<?php include "includes/header.php"; ?>


<!-- =========================================
     BREADCRUMB
========================================= -->

<div class="breadcrumb-bar">

    <div class="container">

        <nav class="breadcrumb-nav">

            <a href="index.php">Home</a>

            <i class="fa-solid fa-chevron-right"></i>

            <a href="category.php?slug=<?= urlencode($news['category_slug']); ?>">
                <?= clean($news['category_name']); ?>
            </a>

            <i class="fa-solid fa-chevron-right"></i>

            <span>
                <?= clean(mb_substr($news['title'], 0, 60)); ?>...
            </span>

        </nav>

    </div>

</div>


<!-- =========================================
     ARTICLE CONTENT
========================================= -->

<section class="section">

    <div class="container">

        <div class="row g-4">


            <!-- =================================
                 MAIN ARTICLE
            ================================== -->

            <div class="col-lg-8">

                <article class="single-article">

                    <!-- Category -->

                    <div class="article-category">
                        <span class="category-label">
                            <?= clean($news['category_name']); ?>
                        </span>
                    </div>


                    <!-- Title -->

                    <h1 class="article-title">
                        <?= clean($news['title']); ?>
                    </h1>


                    <!-- Meta -->

                    <div class="article-meta">

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
                            <?= number_format((int) $news['views']); ?> views
                        </span>

                    </div>


                    <!-- Share -->

                    <div class="article-share">

                        <span>Share:</span>

                        <a
                            href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
                            target="_blank"
                            class="share-btn share-fb"
                            aria-label="Share on Facebook"
                        >
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>

                        <a
                            href="https://twitter.com/intent/tweet?url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?= urlencode($news['title']); ?>"
                            target="_blank"
                            class="share-btn share-tw"
                            aria-label="Share on Twitter"
                        >
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>

                        <a
                            href="https://wa.me/?text=<?= urlencode($news['title'] . ' ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
                            target="_blank"
                            class="share-btn share-wa"
                            aria-label="Share on WhatsApp"
                        >
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>

                    </div>


                    <!-- Featured Image -->

                    <?php if (!empty($news['featured_image'])): ?>

                        <div class="article-image">
                            <img
                                src="uploads/<?= clean($news['featured_image']); ?>"
                                alt="<?= clean($news['title']); ?>"
                            >
                        </div>

                    <?php else: ?>

                        <div class="article-image">
                            <img
                                src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=1200&q=80"
                                alt="<?= clean($news['title']); ?>"
                            >
                        </div>

                    <?php endif; ?>


                    <!-- Short Description -->

                    <?php if (!empty($news['short_description'])): ?>
                        <div class="article-lead">
                            <?= clean($news['short_description']); ?>
                        </div>
                    <?php endif; ?>


                    <!-- Full Content -->

                    <div class="article-body">
                        <?= $news['content']; ?>
                    </div>


                    <!-- Tags / Meta Bottom -->

                    <div class="article-footer-meta">

                        <span class="tag-label">
                            <i class="fa-solid fa-tag"></i>
                            <?= clean($news['category_name']); ?>
                        </span>

                        <?php if ($news['is_featured']): ?>
                            <span class="tag-label tag-featured">
                                <i class="fa-solid fa-star"></i>
                                Featured
                            </span>
                        <?php endif; ?>

                        <?php if ($news['is_trending']): ?>
                            <span class="tag-label tag-trending">
                                <i class="fa-solid fa-fire"></i>
                                Trending
                            </span>
                        <?php endif; ?>

                    </div>

                </article>

            </div>


            <!-- =================================
                 SIDEBAR
            ================================== -->

            <div class="col-lg-4">

                <aside class="sidebar">


                    <!-- AD -->

                    <div class="ad-box">Advertisement</div>


                    <!-- RELATED NEWS -->

                    <?php if (!empty($relatedNews)): ?>

                        <div class="sidebar-widget">

                            <div class="sidebar-widget-header">
                                <h3>Related News</h3>
                            </div>

                            <?php foreach ($relatedNews as $related): ?>

                                <div class="related-item">

                                    <?php if (!empty($related['featured_image'])): ?>
                                        <img
                                            src="uploads/<?= clean($related['featured_image']); ?>"
                                            alt="<?= clean($related['title']); ?>"
                                            class="related-thumb"
                                        >
                                    <?php else: ?>
                                        <img
                                            src="https://images.unsplash.com/photo-1495020689067-958852a7765e?auto=format&fit=crop&w=200&q=80"
                                            alt="News"
                                            class="related-thumb"
                                        >
                                    <?php endif; ?>

                                    <div class="related-content">

                                        <a href="news.php?slug=<?= urlencode($related['slug']); ?>" class="related-title">
                                            <?= clean($related['title']); ?>
                                        </a>

                                        <span class="related-date">
                                            <i class="fa-regular fa-clock"></i>
                                            <?= formatDate($related['published_at']); ?>
                                        </span>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>


                    <!-- AD -->

                    <div class="ad-box">Advertisement</div>


                    <!-- TRENDING -->

                    <?php $trendingNews = getTrendingNews(5); ?>

                    <?php if (!empty($trendingNews)): ?>

                        <div class="sidebar-widget">

                            <div class="sidebar-widget-header">
                                <h3>Trending Now</h3>
                            </div>

                            <?php $n = 1; ?>

                            <?php foreach ($trendingNews as $trending): ?>

                                <div class="trending-item">

                                    <div class="trending-number">
                                        <?= $n; ?>
                                    </div>

                                    <div class="trending-title">
                                        <a href="news.php?slug=<?= urlencode($trending['slug']); ?>">
                                            <?= clean($trending['title']); ?>
                                        </a>
                                    </div>

                                </div>

                                <?php $n++; ?>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </aside>

            </div>

        </div>

    </div>

</section>

<?php include "includes/footer.php"; ?>
