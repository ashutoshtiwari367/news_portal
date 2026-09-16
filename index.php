<?php

session_start();

require_once "includes/functions.php";


// ==========================================
// WEBSITE DATA
// ==========================================

$siteName = getSetting(
    'site_name',
    'News Portal'
);

$siteTagline = getSetting(
    'site_tagline',
    'Latest News & Breaking Updates'
);


// Featured News
$featuredNews = getFeaturedNews(5);

// Latest News
$latestNews = getLatestNews(10);

// Trending News
$trendingNews = getTrendingNews(5);

// Categories
$categories = getCategories();


// ==========================================
// PAGE SEO
// ==========================================

$pageTitle =
    $siteName . " - " .
    $siteTagline;

$pageDescription =
    "Read the latest breaking news, national news, " .
    "politics, business, technology, sports, education " .
    "and local news updates.";

?>

<?php include "includes/header.php"; ?>


<!-- =========================================
     PAGE INTRO / AD
========================================= -->

<div class="container">

    <div class="ad-box">

        Advertisement

    </div>

</div>


<!-- =========================================
     FEATURED NEWS
========================================= -->

<section class="section">

    <div class="container">

        <div class="section-header">

            <h2 class="section-title">
                Top Stories
            </h2>

            <a
                href="search.php"
                class="view-all"
            >
                View All
                <i class="fa-solid fa-arrow-right"></i>
            </a>

        </div>


        <?php if (!empty($featuredNews)): ?>

            <?php

            $mainStory =
                $featuredNews[0];

            $sideStories =
                array_slice(
                    $featuredNews,
                    1,
                    4
                );

            ?>

            <div class="featured-grid">

                <!-- =================================
                     MAIN STORY
                ================================== -->

                <article class="featured-main">

                    <?php if (
                        !empty(
                            $mainStory['featured_image']
                        )
                    ): ?>

                        <img
                            src="uploads/<?= clean(
                                $mainStory['featured_image']
                            ); ?>"
                            alt="<?= clean(
                                $mainStory['title']
                            ); ?>"
                        >

                    <?php else: ?>

                        <img
                            src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=1200&q=80"
                            alt="News"
                        >

                    <?php endif; ?>


                    <div class="featured-overlay">

                        <span class="category-label">

                            <?= clean(
                                $mainStory['category_name']
                            ); ?>

                        </span>


                        <h2>

                            <a
                                href="news.php?slug=<?= urlencode(
                                    $mainStory['slug']
                                ); ?>"
                            >

                                <?= clean(
                                    $mainStory['title']
                                ); ?>

                            </a>

                        </h2>


                        <p>

                            <?= clean(
                                mb_substr(
                                    $mainStory[
                                        'short_description'
                                    ],
                                    0,
                                    150
                                )
                            ); ?>

                        </p>

                    </div>

                </article>


                <!-- =================================
                     SIDE STORIES
                ================================== -->

                <div class="featured-side">

                    <?php

                    $firstSide =
                        array_slice(
                            $sideStories,
                            0,
                            2
                        );

                    ?>

                    <?php foreach (
                        $firstSide
                        as $story
                    ): ?>

                        <article class="featured-small">

                            <?php if (
                                !empty(
                                    $story[
                                        'featured_image'
                                    ]
                                )
                            ): ?>

                                <img
                                    src="uploads/<?= clean(
                                        $story[
                                            'featured_image'
                                        ]
                                    ); ?>"
                                    alt="<?= clean(
                                        $story['title']
                                    ); ?>"
                                >

                            <?php else: ?>

                                <img
                                    src="https://images.unsplash.com/photo-1495020689067-958852a7765e?auto=format&fit=crop&w=700&q=80"
                                    alt="News"
                                >

                            <?php endif; ?>


                            <div class="featured-small-overlay">

                                <h3>

                                    <a
                                        href="news.php?slug=<?= urlencode(
                                            $story['slug']
                                        ); ?>"
                                    >

                                        <?= clean(
                                            $story['title']
                                        ); ?>

                                    </a>

                                </h3>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>


                <div class="featured-side">

                    <?php

                    $secondSide =
                        array_slice(
                            $sideStories,
                            2,
                            2
                        );

                    ?>

                    <?php foreach (
                        $secondSide
                        as $story
                    ): ?>

                        <article class="featured-small">

                            <?php if (
                                !empty(
                                    $story[
                                        'featured_image'
                                    ]
                                )
                            ): ?>

                                <img
                                    src="uploads/<?= clean(
                                        $story[
                                            'featured_image'
                                        ]
                                    ); ?>"
                                    alt="<?= clean(
                                        $story['title']
                                    ); ?>"
                                >

                            <?php else: ?>

                                <img
                                    src="https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=700&q=80"
                                    alt="News"
                                >

                            <?php endif; ?>


                            <div class="featured-small-overlay">

                                <h3>

                                    <a
                                        href="news.php?slug=<?= urlencode(
                                            $story['slug']
                                        ); ?>"
                                    >

                                        <?= clean(
                                            $story['title']
                                        ); ?>

                                    </a>

                                </h3>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            </div>

        <?php else: ?>

            <div class="empty-state">

                <i class="fa-regular fa-newspaper"></i>

                <h3>
                    No Featured News
                </h3>

                <p>
                    Featured stories will appear here.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================
     LATEST + TRENDING
========================================= -->

<section class="section">

    <div class="container">

        <div class="row g-4">

            <!-- =================================
                 LATEST NEWS
            ================================== -->

            <div class="col-lg-8">

                <div class="section-header">

                    <h2 class="section-title">
                        Latest News
                    </h2>

                    <a
                        href="search.php"
                        class="view-all"
                    >
                        More News
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>


                <?php if (
                    !empty($latestNews)
                ): ?>

                    <div class="latest-news-list">

                        <?php foreach (
                            $latestNews
                            as $news
                        ): ?>

                            <article
                                class="news-list-card"
                            >

                                <!-- IMAGE -->

                                <div
                                    class="news-list-image"
                                >

                                    <?php if (
                                        !empty(
                                            $news[
                                                'featured_image'
                                            ]
                                        )
                                    ): ?>

                                        <img
                                            src="uploads/<?= clean(
                                                $news[
                                                    'featured_image'
                                                ]
                                            ); ?>"
                                            alt="<?= clean(
                                                $news['title']
                                            ); ?>"
                                        >

                                    <?php else: ?>

                                        <img
                                            src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=500&q=80"
                                            alt="News"
                                        >

                                    <?php endif; ?>

                                </div>


                                <!-- CONTENT -->

                                <div
                                    class="news-list-content"
                                >

                                    <div
                                        class="category-label"
                                    >

                                        <?= clean(
                                            $news[
                                                'category_name'
                                            ]
                                        ); ?>

                                    </div>


                                    <h3
                                        class="news-list-title"
                                    >

                                        <a
                                            href="news.php?slug=<?= urlencode(
                                                $news['slug']
                                            ); ?>"
                                        >

                                            <?= clean(
                                                $news['title']
                                            ); ?>

                                        </a>

                                    </h3>


                                    <p
                                        class="news-list-description"
                                    >

                                        <?= clean(
                                            mb_substr(
                                                $news[
                                                    'short_description'
                                                ],
                                                0,
                                                180
                                            )
                                        ); ?>

                                    </p>


                                    <div
                                        class="news-meta"
                                    >

                                        <span>

                                            <i
                                                class="fa-regular fa-user"
                                            ></i>

                                            <?= clean(
                                                $news[
                                                    'author_name'
                                                ]
                                            ); ?>

                                        </span>


                                        <span>

                                            <i
                                                class="fa-regular fa-clock"
                                            ></i>

                                            <?= formatDate(
                                                $news[
                                                    'published_at'
                                                ]
                                            ); ?>

                                        </span>


                                        <span>

                                            <i
                                                class="fa-regular fa-eye"
                                            ></i>

                                            <?= (int)
                                                $news[
                                                    'views'
                                                ]; ?>

                                        </span>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="empty-state">

                        <i
                            class="fa-regular fa-newspaper"
                        ></i>

                        <h3>
                            No News Available
                        </h3>

                        <p>
                            Latest news will appear here.
                        </p>

                    </div>

                <?php endif; ?>

            </div>


            <!-- =================================
                 SIDEBAR
            ================================== -->

            <div class="col-lg-4">

                <aside class="sidebar">


                    <!-- ADVERTISEMENT -->

                    <div class="ad-box">

                        Advertisement

                    </div>


                    <!-- TRENDING -->

                    <div class="sidebar-widget">

                        <div
                            class="sidebar-widget-header"
                        >

                            <h3>
                                Trending News
                            </h3>

                        </div>


                        <?php if (
                            !empty($trendingNews)
                        ): ?>

                            <?php

                            $number = 1;

                            ?>

                            <?php foreach (
                                $trendingNews
                                as $trending
                            ): ?>

                                <div
                                    class="trending-item"
                                >

                                    <div
                                        class="trending-number"
                                    >

                                        <?= $number; ?>

                                    </div>


                                    <div
                                        class="trending-title"
                                    >

                                        <a
                                            href="news.php?slug=<?= urlencode(
                                                $trending['slug']
                                            ); ?>"
                                        >

                                            <?= clean(
                                                $trending[
                                                    'title'
                                                ]
                                            ); ?>

                                        </a>

                                    </div>

                                </div>

                                <?php

                                $number++;

                                ?>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <div
                                class="p-4 text-muted"
                            >

                                No trending news.

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- ADVERTISEMENT -->

                    <div class="ad-box">

                        Advertisement

                    </div>

                </aside>

            </div>

        </div>

    </div>

</section>


<!-- =========================================
     CATEGORY SECTIONS
========================================= -->

<section class="section">

    <div class="container">

        <?php

        /*
         * Show first 4 categories
         * on homepage.
         */

        $homeCategories =
            array_slice(
                $categories,
                0,
                4
            );

        ?>

        <?php foreach (
            $homeCategories
            as $category
        ): ?>

            <?php

            $categoryNews =
                getNewsByCategory(
                    $category['id'],
                    4
                );

            ?>

            <?php if (
                !empty($categoryNews)
            ): ?>

                <div class="section">

                    <div class="section-header">

                        <h2 class="section-title">

                            <?= clean(
                                $category['name']
                            ); ?>

                        </h2>


                        <a
                            href="category.php?slug=<?= urlencode(
                                $category['slug']
                            ); ?>"
                            class="view-all"
                        >

                            View All

                            <i
                                class="fa-solid fa-arrow-right"
                            ></i>

                        </a>

                    </div>


                    <div class="row g-3">

                        <?php foreach (
                            $categoryNews
                            as $news
                        ): ?>

                            <div
                                class="col-md-6 col-lg-3"
                            >

                                <article
                                    class="news-card"
                                >

                                    <div
                                        class="news-card-image"
                                    >

                                        <?php if (
                                            !empty(
                                                $news[
                                                    'featured_image'
                                                ]
                                            )
                                        ): ?>

                                            <img
                                                src="uploads/<?= clean(
                                                    $news[
                                                        'featured_image'
                                                    ]
                                                ); ?>"
                                                alt="<?= clean(
                                                    $news['title']
                                                ); ?>"
                                            >

                                        <?php else: ?>

                                            <img
                                                src="https://images.unsplash.com/photo-1495020689067-958852a7765e?auto=format&fit=crop&w=700&q=80"
                                                alt="News"
                                            >

                                        <?php endif; ?>

                                    </div>


                                    <div
                                        class="news-card-body"
                                    >

                                        <div
                                            class="category-label"
                                        >

                                            <?= clean(
                                                $category[
                                                    'name'
                                                ]
                                            ); ?>

                                        </div>


                                        <h3
                                            class="news-card-title"
                                        >

                                            <a
                                                href="news.php?slug=<?= urlencode(
                                                    $news[
                                                        'slug'
                                                    ]
                                                ); ?>"
                                            >

                                                <?= clean(
                                                    $news[
                                                        'title'
                                                    ]
                                                ); ?>

                                            </a>

                                        </h3>


                                        <p
                                            class="news-card-description"
                                        >

                                            <?= clean(
                                                mb_substr(
                                                    $news[
                                                        'short_description'
                                                    ],
                                                    0,
                                                    100
                                                )
                                            ); ?>

                                        </p>


                                        <div
                                            class="news-meta"
                                        >

                                            <span>

                                                <i
                                                    class="fa-regular fa-clock"
                                                ></i>

                                                <?= formatDate(
                                                    $news[
                                                        'published_at'
                                                    ]
                                                ); ?>

                                            </span>

                                        </div>

                                    </div>

                                </article>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            <?php endif; ?>

        <?php endforeach; ?>

    </div>

</section>


<!-- =========================================
     NEWSLETTER
========================================= -->

<section class="section">

    <div class="container">

        <div
            class="newsletter-box"
            style="
                background:#ffffff;
                border:1px solid #e7e7e7;
                border-radius:8px;
                padding:30px;
                text-align:center;
            "
        >

            <h2
                style="
                    font-size:24px;
                    font-weight:800;
                    margin-bottom:8px;
                "
            >

                Stay Updated

            </h2>


            <p
                style="
                    color:#777;
                    font-size:13px;
                    margin-bottom:20px;
                "
            >

                Get the latest news and important updates
                directly in your inbox.

            </p>


            <form
                style="
                    max-width:500px;
                    margin:auto;
                    display:flex;
                    gap:8px;
                "
                onsubmit="return false;"
            >

                <input
                    type="email"
                    placeholder="Enter your email address"
                    required
                    style="
                        flex:1;
                        height:44px;
                        border:1px solid #ddd;
                        padding:0 13px;
                        outline:none;
                        border-radius:4px;
                    "
                >

                <button
                    type="submit"
                    style="
                        border:0;
                        background:#d71920;
                        color:#fff;
                        padding:0 20px;
                        border-radius:4px;
                        font-weight:600;
                    "
                >

                    Subscribe

                </button>

            </form>

        </div>

    </div>

</section>


<?php include "includes/footer.php"; ?>