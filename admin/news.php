<?php

session_start();

require_once "../includes/functions.php";

requireAdmin();

$admin = getAdmin();

global $conn;


// ==========================================
// FETCH ALL NEWS
// ==========================================

$filter = $_GET['status'] ?? 'all';
$search = trim($_GET['q'] ?? '');

$where = "WHERE 1=1";

if ($filter === 'published') {
    $where .= " AND news.status = 'published'";
} elseif ($filter === 'draft') {
    $where .= " AND news.status = 'draft'";
}

if (!empty($search)) {
    $s = $conn->real_escape_string($search);
    $where .= " AND news.title LIKE '%$s%'";
}

$newsList = [];
$result = $conn->query("
    SELECT news.*, categories.name AS category_name
    FROM news
    LEFT JOIN categories ON news.category_id = categories.id
    $where
    ORDER BY news.created_at DESC
    LIMIT 50
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $newsList[] = $row;
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM news WHERE id = $id");
    header("Location: news.php?deleted=1");
    exit;
}

$deleted = isset($_GET['deleted']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">

    <?php include "sidebar.php"; ?>

    <div class="admin-main" id="adminMain">

        <div class="admin-topbar">
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="topbar-title">All Articles</div>
            <div class="topbar-right">
                <a href="add-news.php" class="btn-add-news">
                    <i class="fa-solid fa-plus"></i>
                    Add Article
                </a>
                <span class="admin-name">
                    <i class="fa-regular fa-user-circle"></i>
                    <?= clean($admin['name'] ?? 'Admin'); ?>
                </span>
                <a href="logout.php" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>
            </div>
        </div>

        <div class="admin-content">

            <h1 class="admin-page-title">Manage Articles</h1>

            <?php if ($deleted): ?>
                <div class="alert-success">
                    <i class="fa-solid fa-circle-check"></i> Article deleted successfully.
                </div>
            <?php endif; ?>


            <!-- FILTERS -->

            <div class="news-filters">

                <div class="filter-tabs">

                    <a href="news.php" class="filter-tab <?= $filter === 'all' ? 'active' : ''; ?>">
                        All
                    </a>

                    <a href="news.php?status=published" class="filter-tab <?= $filter === 'published' ? 'active' : ''; ?>">
                        Published
                    </a>

                    <a href="news.php?status=draft" class="filter-tab <?= $filter === 'draft' ? 'active' : ''; ?>">
                        Drafts
                    </a>

                </div>

                <form method="GET" action="news.php" class="filter-search">
                    <input
                        type="text"
                        name="q"
                        placeholder="Search articles..."
                        value="<?= clean($search); ?>"
                    >
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

            </div>


            <!-- NEWS TABLE -->

            <div class="admin-card">

                <div class="admin-table-wrapper">

                    <table class="admin-table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Featured</th>
                                <th>Trending</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (empty($newsList)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        No articles found.
                                        <a href="add-news.php">Add one now</a>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($newsList as $i => $article): ?>

                                <tr>

                                    <td><?= $i + 1; ?></td>

                                    <td class="article-title-cell">
                                        <?= clean(mb_substr($article['title'], 0, 55)); ?>
                                        <?= strlen($article['title']) > 55 ? '...' : ''; ?>
                                    </td>

                                    <td>
                                        <span class="cat-badge">
                                            <?= clean($article['category_name']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if ($article['is_featured']): ?>
                                            <i class="fa-solid fa-star" style="color:#f59e0b"></i>
                                        <?php else: ?>
                                            <i class="fa-regular fa-star" style="color:#aaa"></i>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if ($article['is_trending']): ?>
                                            <i class="fa-solid fa-fire" style="color:#ef4444"></i>
                                        <?php else: ?>
                                            <i class="fa-regular fa-fire" style="color:#aaa"></i>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <span class="status-badge status-<?= $article['status']; ?>">
                                            <?= ucfirst($article['status']); ?>
                                        </span>
                                    </td>

                                    <td><?= number_format((int)$article['views']); ?></td>

                                    <td><?= date('d M Y', strtotime($article['created_at'])); ?></td>

                                    <td>
                                        <div class="action-btns">

                                            <a
                                                href="../news.php?slug=<?= urlencode($article['slug']); ?>"
                                                target="_blank"
                                                class="action-btn btn-view"
                                                title="View"
                                            >
                                                <i class="fa-regular fa-eye"></i>
                                            </a>

                                            <a
                                                href="edit-news.php?id=<?= $article['id']; ?>"
                                                class="action-btn btn-edit"
                                                title="Edit"
                                            >
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>

                                            <a
                                                href="news.php?delete=<?= $article['id']; ?>"
                                                class="action-btn btn-delete"
                                                title="Delete"
                                                onclick="return confirm('Delete this article?')"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                            </a>

                                        </div>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('sidebar-collapsed');
            document.getElementById('adminMain').classList.toggle('main-expanded');
        }
    </script>

</body>
</html>
