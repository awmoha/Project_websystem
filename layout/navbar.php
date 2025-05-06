<nav class="navbar navbar-expand-lg fixed-top bg-body-tertiary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand position-absolute top-50 start-50 translate-middle fw-bold" href="#">
            Incident Response Portal
        </a>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php $firstLetter = strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>

                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle rounded-circle text-white d-flex align-items-center justify-content-center"
                            type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="width: 40px; height: 40px; font-weight: bold;">
                            <?= $firstLetter ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu dropdown-menu-lg-end" aria-labelledby="userDropdown">
                        <li>
                                <h6 class="dropdown-header">Hello, <?= $_SESSION['user_name'] ?></h6>
                            </li>
                            <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person-circle me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button class="dropdown-item d-flex align-items-center" id="toggleTheme">
                                    <i class="bi bi-moon me-2" id="themeIcon"></i><span id="themeText">Dark Mode</span>
                                </button>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleTheme');
    const themeText = document.getElementById('themeText');
    const themeIcon = document.getElementById('themeIcon');
    const body = document.body;

    // Ladda sparat läge
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-mode');
        themeText.innerText = 'Light Mode';
        themeIcon.classList.replace('bi-moon', 'bi-sun');
    }

    toggleButton.addEventListener('click', function () {
        body.classList.toggle('dark-mode');
        const isDark = body.classList.contains('dark-mode');
        themeText.innerText = isDark ? 'Light Mode' : 'Dark Mode';
        themeIcon.classList.toggle('bi-moon', !isDark);
        themeIcon.classList.toggle('bi-sun', isDark);
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });
});
</script>
