<?php

session_start();

require_once "includes/functions.php";


// ==========================================
// GET CATEGORY
// ==========================================

$slug = isset($_GET['slug'])
    ? trim($_GET['slug'])
    : '';

if (empty($slug)) {
    redirect("index.php");
}

$category = getCategoryBySlug($slug);

if (!$category) {
    redirect("index.php");
}


// ==========================================
// PAGINATION
// ==========================================

$perPage     = 9;
$currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset      = ($currentPage - 1) * $perPage;


// ==========================================
// GET NEWS
// ==========================================

global $conn;

$catId = (int) $category['id'];

// Total count
$countResult = $conn->query("
    SELECT COUNT(*) AS total
    FROM news
    WHERE category_id = $catId
    AND status = 'published'
");
$totalCount = $countResult ? (int) $countResult->fetch_assoc()['total'] : 0;
$totalPages = ceil($totalCount / $perPage);

// News for this page
$sql = "
    SELECT
        news.*,
        categories.name AS category_name,
        categories.slug AS category_slug
    FROM news
    LEFT JOIN categories
        ON news.category_id = categories.id
    WHERE news.category_id = $catId
    AND news.status = 'published'
    ORDER BY news.published_at DESC, news.id DESC
    LIMIT $perPage OFFSET $offset
";

$result = $conn->query($sql);
$newsList = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $newsList[] = $row;
    }
}


// ==========================================
// SEO
// ==========================================

$siteName = getSetting('site_name', 'News Portal');

$pageTitle       = clean($category['name']) . " News - " . $siteName;
$pageDescription = "Latest " . $category['name'] . " news, updates, and breaking stories.";

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

            <span><?= clean($category['name']); ?></span>

        </nav>

    </div>

</div>


<!-- =========================================
     CATEGORY HEADER
========================================= -->

<div class="category-page-header">

    <div class="container">

        <h1 class="category-page-title">
            <i class="fa-solid fa-folder-open"></i>
            <?= clean($category['name']); ?>
        </h1>

        <p class="category-page-subtitle">
            <?= $totalCount; ?> articles found
        </p>

    </div>

</div>


<!-- =========================================
     NEWS GRID
========================================= -->

<section class="section">

    <div class="container">

        <?php if (!empty($newsList)): ?>

            <div class="row g-4">

                <?php foreach ($newsList as $news): ?>

                    <div class="col-md-6 col-lg-4">

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
                                        src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=700&q=80"
                                        alt="News"
                                        class="news-card-image"
                                    >
                                <?php endif; ?>

                                <?php if ($news['is_featured']): ?>
                                    <span class="news-card-badge badge-featured">Featured</span>
                                <?php elseif ($news['is_trending']): ?>
                                    <span class="news-card-badge badge-trending">Trending</span>
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

                                <p class="news-card-description">
                                    <?= clean(mb_substr($news['short_description'], 0, 120)); ?>...
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

                                </div>

                            </div>

                        </article>

                    </div>

                <?php endforeach; ?>

            </div>


            <!-- PAGINATION -->

            <?php if ($totalPages > 1): ?>

                <div class="pagination-wrapper">

                    <nav class="pagination-nav">

                        <?php if ($currentPage > 1): ?>
                            <a
                                href="category.php?slug=<?= urlencode($slug); ?>&page=<?= $currentPage - 1; ?>"
                                class="page-btn"
                            >
                                <i class="fa-solid fa-chevron-left"></i>
                                Previous
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                            <a
                                href="category.php?slug=<?= urlencode($slug); ?>&page=<?= $i; ?>"
                                class="page-btn <?= $i === $currentPage ? 'page-btn-active' : ''; ?>"
                            >
                                <?= $i; ?>
                            </a>

                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a
                                href="category.php?slug=<?= urlencode($slug); ?>&page=<?= $currentPage + 1; ?>"
                                class="page-btn"
                            >
                                Next
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>

                    </nav>

                </div>

            <?php endif; ?>


        <?php else: ?>

            <div class="empty-state">

                <i class="fa-regular fa-newspaper"></i>

                <h3>No News Available</h3>

                <p>No articles have been published in this category yet.</p>

                <a href="index.php" class="btn-primary">
                    Back to Home
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php include "includes/footer.php"; ?>
