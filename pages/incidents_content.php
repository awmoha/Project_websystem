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
        $sql = "SELECT i.*, iu.user_name, r.role_name 
                FROM incident i
                JOIN incident_user iu ON i.inc_user_id = iu.inc_user_id
                JOIN role r ON iu.role_id = r.role_id
                WHERE i.inc_user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
    } else {
        $sql = "SELECT i.*, iu.user_name, r.role_name 
                FROM incident i
                JOIN incident_user iu ON i.inc_user_id = iu.inc_user_id
                JOIN role r ON iu.role_id = r.role_id";
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Reported At</th>
                    <th>Reported By (Role)</th>
                    <th>Status</th>
                    <th>Actions</th>
                    <?php if ($role == 'Administrator'): ?>
                        <th>Delete</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['inc_id']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= htmlspecialchars($row['reported_at']) ?></td>
                        <td><?= htmlspecialchars($row['user_name']) ?> (<?= htmlspecialchars($row['role_name']) ?>)</td>
                        <td><?= htmlspecialchars($row['status'] ?? 'Pending') ?></td>
                        <td>
                            <a href="view_incident.php?id=<?= $row['inc_id'] ?>" class="btn btn-primary btn-sm me-1">View</a>
                            <?php if ($role == 'Responder' || $role == 'Administrator'): ?>
                                <a href="change_status.php?id=<?= $row['inc_id'] ?>" class="btn btn-warning btn-sm me-1">Change Status</a>
                            <?php endif; ?>
                        </td>
                        <?php if ($role == 'Administrator'): ?>
                            <td>
                                <a href="delete_incident.php?id=<?= $row['inc_id'] ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Are you sure you want to delete this incident?');">
                                   Delete
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No incidents found.</div>
    <?php endif; ?>
</div>

