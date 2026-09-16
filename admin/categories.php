<?php

session_start();

require_once "../includes/functions.php";

requireAdmin();

$admin = getAdmin();

global $conn;

// Handle add category
$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'add') {

        $name = trim($_POST['name'] ?? '');
        $slug = createSlug($name);

        if (empty($name)) {
            $error = "Category name is required.";
        } else {

            $check = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
            $check->bind_param("s", $slug);
            $check->execute();
            $check->get_result()->num_rows > 0
                ? $error = "Category with this name already exists."
                : null;

            if (empty($error)) {
                $stmt = $conn->prepare("INSERT INTO categories (name, slug, status) VALUES (?, ?, 1)");
                $stmt->bind_param("ss", $name, $slug);
                $stmt->execute()
                    ? $success = "Category added successfully."
                    : $error = "Failed to add category.";
            }
        }
    }

    if ($_POST['action'] === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $conn->query("DELETE FROM categories WHERE id = $id");
        $success = "Category deleted.";
    }

    if ($_POST['action'] === 'toggle') {
        $id     = (int) ($_POST['id'] ?? 0);
        $status = (int) ($_POST['status'] ?? 0);
        $new    = $status ? 0 : 1;
        $conn->query("UPDATE categories SET status = $new WHERE id = $id");
        $success = "Category status updated.";
    }
}

// Fetch categories
$categories = [];
$result = $conn->query("SELECT * FROM categories ORDER BY name ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Admin Panel</title>
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
            <div class="topbar-title">Categories</div>
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

            <h1 class="admin-page-title">Manage Categories</h1>

            <?php if ($success): ?>
                <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?= clean($success); ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert-error"><i class="fa-solid fa-circle-xmark"></i> <?= clean($error); ?></div>
            <?php endif; ?>


            <!-- ADD CATEGORY FORM -->

            <div class="admin-card mb-4">

                <div class="admin-card-header">
                    <h2>Add New Category</h2>
                </div>

                <form method="POST" action="categories.php" class="add-category-form">
                    <input type="hidden" name="action" value="add">

                    <div class="form-row">

                        <div class="form-group">
                            <label for="catName">Category Name</label>
                            <input
                                type="text"
                                name="name"
                                id="catName"
                                placeholder="e.g. Technology"
                                required
                            >
                        </div>

                        <button type="submit" class="btn-admin-primary">
                            <i class="fa-solid fa-plus"></i>
                            Add Category
                        </button>

                    </div>

                </form>

            </div>


            <!-- CATEGORIES TABLE -->

            <div class="admin-card">

                <div class="admin-card-header">
                    <h2>All Categories (<?= count($categories); ?>)</h2>
                </div>

                <div class="admin-table-wrapper">

                    <table class="admin-table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (empty($categories)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No categories found.</td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($categories as $i => $cat): ?>

                                <tr>

                                    <td><?= $i + 1; ?></td>

                                    <td><strong><?= clean($cat['name']); ?></strong></td>

                                    <td><code><?= clean($cat['slug']); ?></code></td>

                                    <td>
                                        <span class="status-badge status-<?= $cat['status'] ? 'published' : 'draft'; ?>">
                                            <?= $cat['status'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="action-btns">

                                            <form method="POST" action="categories.php" style="display:inline">
                                                <input type="hidden" name="action" value="toggle">
                                                <input type="hidden" name="id" value="<?= $cat['id']; ?>">
                                                <input type="hidden" name="status" value="<?= $cat['status']; ?>">
                                                <button type="submit" class="action-btn btn-edit" title="Toggle Status">
                                                    <i class="fa-solid fa-toggle-<?= $cat['status'] ? 'on' : 'off'; ?>"></i>
                                                </button>
                                            </form>

                                            <form method="POST" action="categories.php" style="display:inline"
                                                  onsubmit="return confirm('Delete this category?')">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $cat['id']; ?>">
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
