<?php

$currentAdminPage = basename($_SERVER['PHP_SELF']);

?>

<aside class="admin-sidebar" id="adminSidebar">

    <div class="sidebar-logo">
        <i class="fa-solid fa-newspaper"></i>
        <span>NewsPortal</span>
    </div>

    <nav class="sidebar-nav">

        <div class="sidebar-section-label">Main</div>

        <a
            href="dashboard.php"
            class="sidebar-link <?= $currentAdminPage === 'dashboard.php' ? 'active' : ''; ?>"
        >
            <i class="fa-solid fa-gauge"></i>
            <span>Dashboard</span>
        </a>


        <div class="sidebar-section-label">Content</div>

        <a
            href="news.php"
            class="sidebar-link <?= $currentAdminPage === 'news.php' ? 'active' : ''; ?>"
        >
            <i class="fa-solid fa-newspaper"></i>
            <span>All Articles</span>
        </a>

        <a
            href="add-news.php"
            class="sidebar-link <?= $currentAdminPage === 'add-news.php' ? 'active' : ''; ?>"
        >
            <i class="fa-solid fa-plus-circle"></i>
            <span>Add Article</span>
        </a>

        <a
            href="categories.php"
            class="sidebar-link <?= $currentAdminPage === 'categories.php' ? 'active' : ''; ?>"
        >
            <i class="fa-solid fa-folder"></i>
            <span>Categories</span>
        </a>

        <a
            href="breaking-news.php"
            class="sidebar-link <?= $currentAdminPage === 'breaking-news.php' ? 'active' : ''; ?>"
        >
            <i class="fa-solid fa-bolt"></i>
            <span>Breaking News</span>
        </a>


        <div class="sidebar-section-label">System</div>

        <a
            href="../index.php"
            target="_blank"
            class="sidebar-link"
        >
            <i class="fa-solid fa-globe"></i>
            <span>View Site</span>
        </a>

        <a
            href="logout.php"
            class="sidebar-link sidebar-link-logout"
        >
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>

    </nav>

</aside>
