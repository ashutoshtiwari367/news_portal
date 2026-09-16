<?php

session_start();

require_once "../includes/functions.php";

requireAdmin();

$admin = getAdmin();

global $conn;

$success = '';
$error   = '';

// ==========================================
// GET NEWS BY ID
// ==========================================

$id   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$news = getNewsById($id);

if (!$news) {
    redirect("news.php");
}

$categories = getCategories();


// ==========================================
// HANDLE FORM SUBMIT
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = trim($_POST['title'] ?? '');
    $categoryId  = (int) ($_POST['category_id'] ?? 0);
    $shortDesc   = trim($_POST['short_description'] ?? '');
    $content     = $_POST['content'] ?? '';
    $authorName  = trim($_POST['author_name'] ?? 'Admin');
    $status      = in_array($_POST['status'] ?? '', ['published', 'draft'])
                   ? $_POST['status']
                   : 'draft';
    $isFeatured  = isset($_POST['is_featured']) ? 1 : 0;
    $isTrending  = isset($_POST['is_trending']) ? 1 : 0;

    // Re-generate slug only if title changed
    $slug = $news['slug'];
    if ($title !== $news['title']) {
        $slug = createSlug($title);
    }

    // Validation
    if (empty($title)) {
        $error = "Title is required.";
    } elseif ($categoryId === 0) {
        $error = "Please select a category.";
    } else {

        // Handle image upload
        $featuredImage = $news['featured_image'];
        if (!empty($_FILES['featured_image']['name'])) {

            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error = "Invalid image type.";
            } elseif ($_FILES['featured_image']['size'] > 5 * 1024 * 1024) {
                $error = "Image must be under 5MB.";
            } else {

                $uploadDir = '../uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $fileName = time() . '_' . preg_replace('/[^a-z0-9._-]/', '', strtolower($_FILES['featured_image']['name']));

                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $uploadDir . $fileName)) {
                    $featuredImage = $fileName;
                } else {
                    $error = "Failed to upload image.";
                }
            }
        }

        if (empty($error)) {

            $publishedAt = $news['published_at'];
            if ($status === 'published' && empty($publishedAt)) {
                $publishedAt = date('Y-m-d H:i:s');
            }

            $stmt = $conn->prepare("
                UPDATE news SET
                    category_id = ?,
                    title = ?,
                    slug = ?,
                    short_description = ?,
                    content = ?,
                    featured_image = ?,
                    author_name = ?,
                    status = ?,
                    is_featured = ?,
                    is_trending = ?,
                    published_at = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                "isssssssiisi",
                $categoryId,
                $title,
                $slug,
                $shortDesc,
                $content,
                $featuredImage,
                $authorName,
                $status,
                $isFeatured,
                $isTrending,
                $publishedAt,
                $id
            );

            if ($stmt->execute()) {
                $news    = getNewsById($id);
                $success = "Article updated successfully!";
            } else {
                $error = "Failed to update: " . $conn->error;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - Admin Panel</title>
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
            <div class="topbar-title">Edit Article</div>
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

            <h1 class="admin-page-title">Edit Article</h1>

            <?php if ($success): ?>
                <div class="alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <?= clean($success); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert-error">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <?= clean($error); ?>
                </div>
            <?php endif; ?>


            <form method="POST" action="edit-news.php?id=<?= $id; ?>" enctype="multipart/form-data">

                <div class="add-news-layout">

                    <div class="add-news-main">

                        <div class="admin-card">

                            <div class="form-group">
                                <label for="title">Article Title <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    value="<?= clean($news['title']); ?>"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="shortDesc">Short Description</label>
                                <textarea name="short_description" id="shortDesc" rows="3"><?= clean($news['short_description'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="content">Article Content</label>
                                <textarea name="content" id="content" rows="15"><?= $news['content']; ?></textarea>
                            </div>

                        </div>

                        <div class="admin-card">

                            <div class="form-group">
                                <label>Featured Image</label>

                                <?php if (!empty($news['featured_image'])): ?>
                                    <div class="current-image">
                                        <img src="../uploads/<?= clean($news['featured_image']); ?>" alt="Current Image">
                                        <span>Current image</span>
                                    </div>
                                <?php endif; ?>

                                <div
                                    class="image-upload-area"
                                    id="imageUploadArea"
                                    onclick="document.getElementById('featuredImage').click()"
                                >
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <p>Click to upload new image</p>
                                    <span>JPG, PNG, WEBP up to 5MB</span>
                                </div>

                                <input
                                    type="file"
                                    name="featured_image"
                                    id="featuredImage"
                                    accept="image/*"
                                    style="display:none"
                                    onchange="previewImage(this)"
                                >

                                <img id="imagePreview" class="image-preview" style="display:none" alt="Preview">

                            </div>

                        </div>

                    </div>

                    <div class="add-news-sidebar">

                        <div class="admin-card">

                            <h3 class="settings-title">Publish Settings</h3>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status">
                                    <option value="published" <?= $news['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                                    <option value="draft" <?= $news['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Author Name</label>
                                <input type="text" name="author_name" value="<?= clean($news['author_name']); ?>">
                            </div>

                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="is_featured" value="1" <?= $news['is_featured'] ? 'checked' : ''; ?>>
                                    <span><i class="fa-solid fa-star"></i> Featured</span>
                                </label>
                            </div>

                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="is_trending" value="1" <?= $news['is_trending'] ? 'checked' : ''; ?>>
                                    <span><i class="fa-solid fa-fire"></i> Trending</span>
                                </label>
                            </div>

                            <div class="publish-actions">
                                <button type="submit" class="btn-admin-primary w-100">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    Update Article
                                </button>
                                <a href="news.php" class="btn-admin-secondary w-100">Cancel</a>
                            </div>

                        </div>

                        <div class="admin-card">
                            <h3 class="settings-title">Category</h3>
                            <div class="form-group">
                                <select name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id']; ?>" <?= (int)$news['category_id'] === (int)$cat['id'] ? 'selected' : ''; ?>>
                                            <?= clean($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('sidebar-collapsed');
            document.getElementById('adminMain').classList.toggle('main-expanded');
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    const area    = document.getElementById('imageUploadArea');
                    preview.src   = e.target.result;
                    preview.style.display = 'block';
                    area.style.display    = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>
</html>
