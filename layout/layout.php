<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'My App' ?></title>
    <link href="/media/css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/css/coreui.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/js/coreui.bundle.min.js"></script>

</head>

<body class="d-flex flex-column min-vh-100">

    <?php include 'navbar.php'; ?>
    <div class="container-fluid flex-grow-1">
        <div class="row">
            <?php if (isset($_SESSION['user_id'])): ?>
                <aside class="col-12 col-sm-12 col-md-9 col-lg-12 col-xl-12">
                    <?php include 'sidebar.php'; ?>
                </aside>
            <?php endif; ?>


            <main class="col-12 col-sm-10 col-md-10 col-lg-11 col-xl-12 px-0">
                <?php if (isset($content)) include $content; ?>
            </main>

        </div>
    </div>
    <footer class="py-3 text-center mt-auto">
        <p>&copy; <?= date('Y') ?> Incident Response Portal - All rights reserved.</p>
    </footer>
</body>

</html>

<style>
    body.light-mode .nav-item .active,
    body.dark-mode .nav-item .active {
        background-color: #007bff;
        color: #ffffff !important;
    }

    body.light-mode .sidebar {
        background-color: #ffffff;
        color: #333;
    }

    body.dark-mode .sidebar {
        background-color: #1e1e2f;
        color: #f0f0f0;
    }


    .sidebar .nav-link {
        color: inherit;
        transition: background-color 0.3s, color 0.3s;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
        background-color: #007bff;
        color: #ffffff;
    }

    body.light-mode .sidebar .nav-link.active,
    body.light-mode .sidebar .nav-link:hover {
        background-color: #007bff;
        color: #ffffff;
    }

    body.dark-mode .sidebar .nav-link.active,
    body.dark-mode .sidebar .nav-link:hover {
        background-color: #0056b3;
        color: #ffffff;
    }

    #sidebarToggle {
        background-color: #007bff;
        color: #ffffff;
        border: none;
        padding: 0.5rem 1rem;
        cursor: pointer;
    }

    body.dark-mode {
        background-color: #1e1e2f;
        color: #f8f9fa;
    }

    body.dark-mode .title {
        color: #ffffff !important;
    }


    body.dark-mode .dark-mode-bg {
        background-color: #1f1f1f !important;
        color: #f8f9fa !important;
        border-color: #444 !important;
    }

    body.dark-mode ul,
    body.dark-mode .navbar,
    body.dark-mode .dropdown-menu,
    body.dark-mode .card,
    body.dark-mode .modal-content {
        background-color: #1e1e2f !important;
        color: #f8f9fa !important;
    }

    body.dark-mode .dropdown-item {
        color: #ffffff;
    }

    body.dark-mode .dropdown-item:hover {
        background-color: #333333;
    }

    body.dark-mode .dashboard-card {
        background-color: #1e1e2f !important;
        color: #f8f9fa !important;
    }

    body.dark-mode a {
        color: #f8f9fa !important;

    }

    body.light-mode a {
        color: #1e1e2f !important;

    }

    body.dark-mode table.table,
    body.dark-mode table.table thead,
    body.dark-mode table.table tbody,
    body.dark-mode table.table tr,
    body.dark-mode table.table td,
    body.dark-mode table.table th {
        background-color: #1e1e2f !important;
        color: #f8f9fa !important;
        border-color: #444 !important;
    }

    body.dark-mode table.table thead {
        background-color: #1e1e2f !important;
    }

    body.dark-mode table.table tbody tr:hover {
        background-color: #333 !important;
    }

    body.dark-mode .card,
    body.dark-mode .card-header,
    body.dark-mode .card-footer,
    body.dark-mode .card-body {
        background-color: #1e1e2f;
        color: #f8f9fa;
        border-color: #444 !important;

    }

    body.dark-mode .form-control,
    body.dark-mode .form-select {
        background-color: #1e1e2f;
        color: #f8f9fa;
        border-color: #444;
    }

    body.dark-mode .form-control::placeholder {
        color: #aaa;
    }

    body.dark-mode .btn-close {
        filter: invert(1);
    }

    body.dark-mode .text-muted {
        color: #aaa !important;
    }

    body.dark-mode hr {
        border-color: #444;
    }

    body.dark-mode .pagination .page-link {
        background-color: #1e1e2f;
        color: #f8f9fa;
        border-color: #444;
    }

    body.dark-mode .pagination .page-item.active .page-link {
        background-color: #007bff;
        color: #ffffff;
        border-color: #007bff;
    }

    body.dark-mode .pagination .page-link:hover {
        background-color: #444;
        color: #ffffff;
    }
</style>