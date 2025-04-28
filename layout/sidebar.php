<link href="media/css/styles.css" rel="stylesheet" />

<div class="mt-5">
    <button class="btn btn-primary d-xl-none mt-3" id="sidebarToggle">
        ☰
    </button>
    <div class="sidebar sidebar-narrow-unfoldable bg-dark text-primary border-end pt-4 px-2" id="sidebar">
        <div class="sidebar-header border-bottom text-white">
            <div class="sidebar-brand text-primary">Menu</div>
        </div>
        <ul class="sidebar-nav">
            <li class="nav-title text-primary">Navigation</li>

            <li class="nav-item">
                <a class="nav-link text-primary <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-primary <?= basename($_SERVER['PHP_SELF']) == 'incidents.php' ? 'active' : '' ?>" href="incidents.php">
                    <i class="bi bi-list-ul me-2"></i> Incidents
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-primary <?= basename($_SERVER['PHP_SELF']) == 'create_incident.php' ? 'active' : '' ?>" href="create_incident.php">
                    <i class="bi bi-plus-circle me-2"></i> Create Incident
                </a>
            </li>
            <?php if (isset($_SESSION['role']) && trim($_SESSION['role']) == 'Administrator'): ?>

                <li class="nav-item">
                    <a class="nav-link text-primary <?= basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : '' ?>" href="reports.php">
                        <i class="bi bi-bar-chart-line me-2"></i> Reports
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-primary <?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : '' ?>" href="users.php">
                        <i class="bi bi-people me-2"></i> Users
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link text-primary <?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : '' ?>" href="settings.php">
                    <i class="bi bi-gear me-2"></i> Settings
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-primary <?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>" href="about.php">
                    <i class="bi bi-info-circle me-2"></i> About
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-primary <?= basename($_SERVER['PHP_SELF']) == 'sla.php' ? 'active' : '' ?>" href="sla.php">
                    <i class="bi bi-info-circle me-2"></i> SLA
                </a>
            </li>

            <li class="nav-item">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a class="nav-link text-primary <?= basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : '' ?>" href="logout.php">
                        <i class="bi bi-box-arrow-left me-2"></i> Logout
                    </a>
                <?php else: ?>
                    <a class="nav-link text-primary <?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>" href="login.php">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login
                    </a>
                <?php endif; ?>
            </li>

        </ul>
    </div>
</div>
<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        document.body.classList.toggle('sidebar-open');
    });

    document.addEventListener('click', function(event) {
        if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
            sidebar.classList.remove('show');
            document.body.classList.remove('sidebar-open');
        }
    });
</script>