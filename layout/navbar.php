<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">

        <!-- Center Text -->
        <a class="navbar-brand position-absolute top-50 start-50 translate-middle fw-bold" href="#">
            Incident Response Portal
        </a>

        <!-- Toggler Button -->
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Right Content -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
        <div class="d-flex flex-reverse bd-highlight">
        <?php if (isset($_SESSION['user_id'])): ?>

                    <!-- <form class="d-flex me-3" role="search">
                    <input class="form-control form-control-md me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success btn-md d-flex align-items-center" type="submit">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </form> -->

                    <?php
                    $firstLetter = strtoupper(substr($_SESSION['user_name'], 0, 1));
                    ?>
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item text-white me-2 d-flex align-items-center">
                            <i class="bi bi-person me-2"></i>
                            <span>Hello <strong><?= $_SESSION['user_name'] ?></strong></span>
                        </li>
                    </ul>

                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-1"
                        style="width: 40px; height: 40px; font-weight: bold;">
                        <?= $firstLetter ?>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>