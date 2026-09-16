<?php

session_start();

require_once "../includes/functions.php";

requireAdmin();

$admin = getAdmin();

global $conn;

// ==========================================
// STATS
// ==========================================

$totalNews = $conn->query("SELECT COUNT(*) AS c FROM news")->fetch_assoc()['c'] ?? 0;
$pubNews   = $conn->query("SELECT COUNT(*) AS c FROM news WHERE status='published'")->fetch_assoc()['c'] ?? 0;
$draftNews = $conn->query("SELECT COUNT(*) AS c FROM news WHERE status='draft'")->fetch_assoc()['c'] ?? 0;
$totalCats = $conn->query("SELECT COUNT(*) AS c FROM categories")->fetch_assoc()['c'] ?? 0;
$totalViews= $conn->query("SELECT SUM(views) AS c FROM news")->fetch_assoc()['c'] ?? 0;


// ==========================================
// RECENT NEWS
// ==========================================

$recentNews = [];
$result = $conn->query("
    SELECT news.*, categories.name AS category_name
    FROM news
    LEFT JOIN categories ON news.category_id = categories.id
    ORDER BY news.created_at DESC
    LIMIT 10
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recentNews[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - News Portal Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">

</head>

<body class="admin-body">

    <!-- SIDEBAR -->
    <?php include "sidebar.php"; ?>


    <!-- MAIN WRAPPER -->

    <div class="admin-main" id="adminMain">

        <!-- TOP BAR -->

        <div class="admin-topbar">

            <button class="sidebar-toggle" onclick="toggleSidebar()" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="topbar-title">Dashboard</div>

            <div class="topbar-right">

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


        <!-- CONTENT -->

        <div class="admin-content">

            <h1 class="admin-page-title">Dashboard Overview</h1>


            <!-- STATS CARDS -->

            <div class="stats-grid">

                <div class="stat-card stat-blue">
                    <div class="stat-icon">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number"><?= number_format($totalNews); ?></div>
                        <div class="stat-label">Total Articles</div>
                    </div>
                </div>

                <div class="stat-card stat-green">
                    <div class="stat-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number"><?= number_format($pubNews); ?></div>
                        <div class="stat-label">Published</div>
                    </div>
                </div>

                <div class="stat-card stat-orange">
                    <div class="stat-icon">
                        <i class="fa-solid fa-pencil"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number"><?= number_format($draftNews); ?></div>
                        <div class="stat-label">Drafts</div>
                    </div>
                </div>

                <div class="stat-card stat-purple">
                    <div class="stat-icon">
                        <i class="fa-solid fa-folder"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number"><?= number_format($totalCats); ?></div>
                        <div class="stat-label">Categories</div>
                    </div>
                </div>

                <div class="stat-card stat-red">
                    <div class="stat-icon">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number"><?= number_format($totalViews); ?></div>
                        <div class="stat-label">Total Views</div>
                    </div>
                </div>

            </div>


            <!-- QUICK ACTIONS -->

            <div class="quick-actions">

                <h2 class="admin-section-title">Quick Actions</h2>

                <div class="quick-action-grid">

                    <a href="add-news.php" class="quick-action-card qa-primary">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add News Article</span>
                    </a>

                    <a href="news.php" class="quick-action-card qa-secondary">
                        <i class="fa-solid fa-list"></i>
                        <span>Manage News</span>
                    </a>

                    <a href="categories.php" class="quick-action-card qa-tertiary">
                        <i class="fa-solid fa-folder-plus"></i>
                        <span>Manage Categories</span>
                    </a>

                    <a href="breaking-news.php" class="quick-action-card qa-danger">
                        <i class="fa-solid fa-bolt"></i>
                        <span>Breaking News</span>
                    </a>

                    <a href="../index.php" target="_blank" class="quick-action-card qa-view">
                        <i class="fa-solid fa-globe"></i>
                        <span>View Website</span>
                    </a>

                </div>

            </div>


            <!-- RECENT ARTICLES -->

            <div class="admin-card">

                <div class="admin-card-header">
                    <h2>Recent Articles</h2>
                    <a href="news.php" class="admin-card-link">View All</a>
                </div>

                <div class="admin-table-wrapper">

                    <table class="admin-table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (empty($recentNews)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No articles yet.
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($recentNews as $i => $article): ?>

                                <tr>

                                    <td><?= $i + 1; ?></td>

                                    <td class="article-title-cell">
                                        <?= clean(mb_substr($article['title'], 0, 60)); ?>
                                        <?= strlen($article['title']) > 60 ? '...' : ''; ?>
                                    </td>

                                    <td>
                                        <span class="cat-badge">
                                            <?= clean($article['category_name']); ?>
                                        </span>
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
                                                href="delete-news.php?id=<?= $article['id']; ?>"
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('sidebar-collapsed');
            document.getElementById('adminMain').classList.toggle('main-expanded');
        }
    </script>

</body>

</html>
