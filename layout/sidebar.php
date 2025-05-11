<div class="mt-5">
    <button class="btn btn-primary d-xl-none mt-3" id="sidebarToggle">
        ☰
    </button>
    <div class="sidebar sidebar-narrow-unfoldable border-end pt-4 px-2" id="sidebar">
        <div class="sidebar-header border-bottom">
            <div class="title sidebar-brand ">Menu</div>
        </div>
        <ul class="sidebar-nav">
            <li class="title nav-title ">Navigation</li>

            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'incidents.php' ? 'active' : '' ?>" href="incidents.php">
                    <i class="bi bi-list-ul me-2"></i> Incidents
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'create_incident.php' ? 'active' : '' ?>" href="create_incident.php">
                    <i class="bi bi-plus-circle me-2"></i> Create Incident
                </a>
            </li>

            <?php if (isset($_SESSION['role']) && trim($_SESSION['role']) == 'Administrator'): ?>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'statistics.php' ? 'active' : '' ?>" href="statistics.php">
                        <i class="bi bi-bar-chart-line me-2"></i> Statistics
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : '' ?>" href="users.php">
                        <i class="bi bi-people me-2"></i> Users
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>" href="about.php">
                    <i class="bi bi-info-circle me-2"></i> About
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'sla.php' ? 'active' : '' ?>" href="sla.php">
                    <i class="bi bi-hourglass-split me-2"></i> SLA
                </a>
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
</script>