<?php

session_start();

require_once "../includes/functions.php";

requireAdmin();

$admin = getAdmin();

global $conn;

$success = '';
$error   = '';

// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'add') {

        $title  = trim($_POST['title'] ?? '');
        $link   = trim($_POST['link'] ?? '#');
        $status = isset($_POST['status']) ? 1 : 0;

        if (empty($title)) {
            $error = "Title is required.";
        } else {
            $stmt = $conn->prepare("INSERT INTO breaking_news (title, link, status) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $title, $link, $status);
            $stmt->execute()
                ? $success = "Breaking news added."
                : $error = "Failed to add.";
        }
    }

    if ($_POST['action'] === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $conn->query("DELETE FROM breaking_news WHERE id = $id");
        $success = "Breaking news deleted.";
    }

    if ($_POST['action'] === 'toggle') {
        $id     = (int) ($_POST['id'] ?? 0);
        $status = (int) ($_POST['status'] ?? 0);
        $new    = $status ? 0 : 1;
        $conn->query("UPDATE breaking_news SET status = $new WHERE id = $id");
        $success = "Status updated.";
    }
}

// Fetch
$breakingList = [];
$result = $conn->query("SELECT * FROM breaking_news ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $breakingList[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breaking News - Admin Panel</title>
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
            <div class="topbar-title">Breaking News</div>
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

        <div class="admin-content">

            <h1 class="admin-page-title">
                <i class="fa-solid fa-bolt"></i>
                Breaking News Ticker
            </h1>

            <?php if ($success): ?>
                <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?= clean($success); ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert-error"><i class="fa-solid fa-circle-xmark"></i> <?= clean($error); ?></div>
            <?php endif; ?>


            <!-- ADD FORM -->

            <div class="admin-card mb-4">

                <div class="admin-card-header">
                    <h2>Add Breaking News</h2>
                </div>

                <form method="POST" action="breaking-news.php">

                    <input type="hidden" name="action" value="add">

                    <div class="form-group">
                        <label for="bTitle">Breaking News Text</label>
                        <input
                            type="text"
                            name="title"
                            id="bTitle"
                            placeholder="Enter breaking news headline..."
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="bLink">Link URL (optional)</label>
                        <input
                            type="text"
                            name="link"
                            id="bLink"
                            placeholder="https://... or leave # for no link"
                            value="#"
                        >
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="status" value="1" checked>
                            <span>Active (show on website)</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-admin-primary">
                        <i class="fa-solid fa-bolt"></i>
                        Add Breaking News
                    </button>

                </form>

            </div>


            <!-- LIST -->

            <div class="admin-card">

                <div class="admin-card-header">
                    <h2>All Breaking News (<?= count($breakingList); ?>)</h2>
                </div>

                <div class="admin-table-wrapper">

                    <table class="admin-table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Headline</th>
                                <th>Link</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (empty($breakingList)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No breaking news added yet.
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($breakingList as $i => $item): ?>

                                <tr>

                                    <td><?= $i + 1; ?></td>

                                    <td><?= clean($item['title']); ?></td>

                                    <td>
                                        <a href="<?= clean($item['link']); ?>" target="_blank">
                                            <?= clean(mb_substr($item['link'], 0, 40)); ?>
                                        </a>
                                    </td>

                                    <td>
                                        <span class="status-badge status-<?= $item['status'] ? 'published' : 'draft'; ?>">
                                            <?= $item['status'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="action-btns">

                                            <form method="POST" action="breaking-news.php" style="display:inline">
                                                <input type="hidden" name="action" value="toggle">
                                                <input type="hidden" name="id" value="<?= $item['id']; ?>">
                                                <input type="hidden" name="status" value="<?= $item['status']; ?>">
                                                <button type="submit" class="action-btn btn-edit" title="Toggle">
                                                    <i class="fa-solid fa-toggle-<?= $item['status'] ? 'on' : 'off'; ?>"></i>
                                                </button>
                                            </form>

                                            <form method="POST" action="breaking-news.php" style="display:inline"
                                                  onsubmit="return confirm('Delete?')">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $item['id']; ?>">
                                                <button type="submit" class="action-btn btn-delete" title="Delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>

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
