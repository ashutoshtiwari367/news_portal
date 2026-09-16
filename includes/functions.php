<?php

// ==========================================
// COMMON FUNCTIONS
// ==========================================

require_once __DIR__ . '/../config/database.php';


// ==========================================
// SECURITY
// ==========================================

function clean($value)
{
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}


// ==========================================
// REDIRECT
// ==========================================

function redirect($url)
{
    header("Location: " . $url);
    exit;
}


// ==========================================
// CREATE SLUG
// ==========================================

function createSlug($text)
{
    $text = trim($text);

    // English characters
    $text = strtolower($text);

    $text = preg_replace(
        '/[^a-z0-9]+/',
        '-',
        $text
    );

    $text = trim($text, '-');

    return $text;
}


// ==========================================
// GET ALL CATEGORIES
// ==========================================

function getCategories()
{
    global $conn;

    $categories = [];

    $sql = "
        SELECT *
        FROM categories
        WHERE status = 1
        ORDER BY name ASC
    ";

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }

    return $categories;
}


// ==========================================
// GET CATEGORY BY SLUG
// ==========================================

function getCategoryBySlug($slug)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT *
        FROM categories
        WHERE slug = ?
        AND status = 1
        LIMIT 1
    ");

    $stmt->bind_param("s", $slug);

    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_assoc();
}


// ==========================================
// GET NEWS BY ID
// ==========================================

function getNewsById($id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            news.*,
            categories.name AS category_name,
            categories.slug AS category_slug
        FROM news
        LEFT JOIN categories
            ON news.category_id = categories.id
        WHERE news.id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_assoc();
}


// ==========================================
// GET NEWS BY SLUG
// ==========================================

function getNewsBySlug($slug)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            news.*,
            categories.name AS category_name,
            categories.slug AS category_slug
        FROM news
        LEFT JOIN categories
            ON news.category_id = categories.id
        WHERE news.slug = ?
        AND news.status = 'published'
        LIMIT 1
    ");

    $stmt->bind_param("s", $slug);

    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_assoc();
}


// ==========================================
// GET FEATURED NEWS
// ==========================================

function getFeaturedNews($limit = 5)
{
    global $conn;

    $limit = (int) $limit;

    $sql = "
        SELECT 
            news.*,
            categories.name AS category_name,
            categories.slug AS category_slug
        FROM news
        LEFT JOIN categories
            ON news.category_id = categories.id
        WHERE news.status = 'published'
        AND news.is_featured = 1
        ORDER BY news.published_at DESC
        LIMIT $limit
    ";

    $result = $conn->query($sql);

    $news = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $news[] = $row;
        }
    }

    return $news;
}


// ==========================================
// GET LATEST NEWS
// ==========================================

function getLatestNews($limit = 10)
{
    global $conn;

    $limit = (int) $limit;

    $sql = "
        SELECT 
            news.*,
            categories.name AS category_name,
            categories.slug AS category_slug
        FROM news
        LEFT JOIN categories
            ON news.category_id = categories.id
        WHERE news.status = 'published'
        ORDER BY news.published_at DESC,
                 news.id DESC
        LIMIT $limit
    ";

    $result = $conn->query($sql);

    $news = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $news[] = $row;
        }
    }

    return $news;
}


// ==========================================
// GET TRENDING NEWS
// ==========================================

function getTrendingNews($limit = 5)
{
    global $conn;

    $limit = (int) $limit;

    $sql = "
        SELECT 
            news.*,
            categories.name AS category_name,
            categories.slug AS category_slug
        FROM news
        LEFT JOIN categories
            ON news.category_id = categories.id
        WHERE news.status = 'published'
        AND news.is_trending = 1
        ORDER BY news.views DESC,
                 news.published_at DESC
        LIMIT $limit
    ";

    $result = $conn->query($sql);

    $news = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $news[] = $row;
        }
    }

    return $news;
}


// ==========================================
// GET BREAKING NEWS
// ==========================================

function getBreakingNews($limit = 10)
{
    global $conn;

    $limit = (int) $limit;

    $sql = "
        SELECT *
        FROM breaking_news
        WHERE status = 1
        ORDER BY id DESC
        LIMIT $limit
    ";

    $result = $conn->query($sql);

    $breaking = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $breaking[] = $row;
        }
    }

    return $breaking;
}


// ==========================================
// GET NEWS BY CATEGORY
// ==========================================

function getNewsByCategory($categoryId, $limit = 10)
{
    global $conn;

    $categoryId = (int) $categoryId;
    $limit = (int) $limit;

    $sql = "
        SELECT 
            news.*,
            categories.name AS category_name,
            categories.slug AS category_slug
        FROM news
        LEFT JOIN categories
            ON news.category_id = categories.id
        WHERE news.category_id = $categoryId
        AND news.status = 'published'
        ORDER BY news.published_at DESC
        LIMIT $limit
    ";

    $result = $conn->query($sql);

    $news = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $news[] = $row;
        }
    }

    return $news;
}


// ==========================================
// SEARCH NEWS
// ==========================================

function searchNews($keyword, $limit = 20)
{
    global $conn;

    $keyword = trim($keyword);
    $limit = (int) $limit;

    $search = "%" . $keyword . "%";

    $stmt = $conn->prepare("
        SELECT 
            news.*,
            categories.name AS category_name,
            categories.slug AS category_slug
        FROM news
        LEFT JOIN categories
            ON news.category_id = categories.id
        WHERE news.status = 'published'
        AND (
            news.title LIKE ?
            OR news.short_description LIKE ?
            OR news.content LIKE ?
        )
        ORDER BY news.published_at DESC
        LIMIT $limit
    ");

    $stmt->bind_param(
        "sss",
        $search,
        $search,
        $search
    );

    $stmt->execute();

    $result = $stmt->get_result();

    $news = [];

    while ($row = $result->fetch_assoc()) {
        $news[] = $row;
    }

    return $news;
}


// ==========================================
// GET SITE SETTING
// ==========================================

function getSetting($key, $default = '')
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT setting_value
        FROM settings
        WHERE setting_key = ?
        LIMIT 1
    ");

    $stmt->bind_param("s", $key);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['setting_value'];
    }

    return $default;
}


// ==========================================
// FORMAT DATE
// ==========================================

function formatDate($date)
{
    if (!$date) {
        return '';
    }

    return date(
        'd M Y, h:i A',
        strtotime($date)
    );
}


// ==========================================
// INCREASE NEWS VIEWS
// ==========================================

function increaseNewsViews($id)
{
    global $conn;

    $id = (int) $id;

    $stmt = $conn->prepare("
        UPDATE news
        SET views = views + 1
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);

    $stmt->execute();
}


// ==========================================
// CHECK ADMIN LOGIN
// ==========================================

function isAdminLoggedIn()
{
    return isset($_SESSION['admin_id']);
}


// ==========================================
// REQUIRE ADMIN LOGIN
// ==========================================

function requireAdmin()
{
    if (!isset($_SESSION['admin_id'])) {
        header("Location: index.php");
        exit;
    }
}


// ==========================================
// GET ADMIN DETAILS
// ==========================================

function getAdmin()
{
    global $conn;

    if (!isset($_SESSION['admin_id'])) {
        return null;
    }

    $id = (int) $_SESSION['admin_id'];

    $stmt = $conn->prepare("
        SELECT id, name, email, role
        FROM admins
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

?>