<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= htmlspecialchars($title) ?></h1>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </div>
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Incident #<?= htmlspecialchars($row['inc_id']) ?></h5>
                            <p><strong>Reported By:</strong> <?= htmlspecialchars($row['reporter_name']) ?></p>
                            <p><strong>Reported At:</strong> <?= htmlspecialchars($row['reported_at']) ?></p>

                            <p><strong>Time Left:</strong> <?= htmlspecialchars($row['time_left']) ?> hours</p>
                            <div class="progress">
                                <?php 
                                    $progress = ($row['hours_elapsed'] / $row['resolution_time']) * 100;
                                    if ($progress > 100) $progress = 100; 
                                ?>
                                <div class="progress-bar <?= $progress > 75 ? 'bg-danger' : ($progress > 50 ? 'bg-warning' : 'bg-success') ?>" role="progressbar" style="width: <?= $progress ?>%" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">No incidents found.</div>
        <?php endif; ?>
    </div>
</div>
