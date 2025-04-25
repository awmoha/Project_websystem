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
  <link href="media/css/styles.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/css/coreui.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #1e1e2f;
    }
  </style>
</head>

<body>

  <?php include 'navbar.php'; ?>

  <div class="container-fluid">
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/js/coreui.bundle.min.js"></script>
</body>

</html>