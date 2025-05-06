<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= htmlspecialchars($title) ?></h1>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </div>

    <?php
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    if (isset($_SESSION['flash_message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['flash_message'] . '</div>';
        unset($_SESSION['flash_message']);
    }

    
    if ($role == 'Reporter') {
        $sql = "SELECT i.inc_id, i.description, i.reported_at, i.inc_sev_id, iu.user_name AS reporter_name, 
                       (CASE 
                            WHEN i.inc_sev_id = 1 THEN 168        -- LOW: 168 hours (7 days)
                            WHEN i.inc_sev_id = 2 THEN 48         -- Medium: 48 hours (2 days)
                            WHEN i.inc_sev_id = 3 THEN 24         -- High: 24 hours (1 day)
                            WHEN i.inc_sev_id = 4 THEN 2          -- Critical: 2 hours
                            ELSE 0                                 -- If no priority, set it to 0
                        END) AS resolution_time,
                       TIMESTAMPDIFF(HOUR, i.reported_at, NOW()) AS hours_elapsed,
                       (CASE 
                            WHEN i.inc_sev_id = 1 THEN 168
                            WHEN i.inc_sev_id = 2 THEN 48
                            WHEN i.inc_sev_id = 3 THEN 24
                            WHEN i.inc_sev_id = 4 THEN 2
                            ELSE 0 
                        END) - TIMESTAMPDIFF(HOUR, i.reported_at, NOW()) AS time_left
                FROM incident i
                JOIN incident_user iu ON i.inc_user_id = iu.inc_user_id
                WHERE i.inc_user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
    } else {
        $sql = "SELECT i.inc_id, i.description, i.reported_at, i.inc_sev_id, iu.user_name AS reporter_name, 
                       (CASE 
                            WHEN i.inc_sev_id = 1 THEN 168
                            WHEN i.inc_sev_id = 2 THEN 48
                            WHEN i.inc_sev_id = 3 THEN 24
                            WHEN i.inc_sev_id = 4 THEN 2
                            ELSE 0 
                        END) AS resolution_time,
                       TIMESTAMPDIFF(HOUR, i.reported_at, NOW()) AS hours_elapsed,
                       (CASE 
                            WHEN i.inc_sev_id = 1 THEN 168
                            WHEN i.inc_sev_id = 2 THEN 48
                            WHEN i.inc_sev_id = 3 THEN 24
                            WHEN i.inc_sev_id = 4 THEN 2
                            ELSE 0 
                        END) - TIMESTAMPDIFF(HOUR, i.reported_at, NOW()) AS time_left
                FROM incident i
                JOIN incident_user iu ON i.inc_user_id = iu.inc_user_id";
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Incident #<?= htmlspecialchars($row['inc_id']) ?></h5>
                            <p><strong>Reported By:</strong> <?= htmlspecialchars($row['reporter_name']) ?></p>
                            <p><strong>Reported At:</strong> <?= htmlspecialchars($row['reported_at']) ?></p>

                            <!-- SLA Progress -->
                            <p><strong>Time Left:</strong> <?= htmlspecialchars($row['time_left']) ?> hours</p>
                            <div class="progress">
                                <?php 
                                    $progress = ($row['hours_elapsed'] / $row['resolution_time']) * 100;
                                    if ($progress > 100) $progress = 100; // Cap to 100% if over SLA time
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
