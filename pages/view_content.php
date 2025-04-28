<?php
if (!isset($_GET['id'])) {
    header("Location: incidents.php");
    exit();
}


require_once('db.php');
session_start();

$inc_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$sql = "SELECT i.*, iu.user_name 
        FROM incident i 
        JOIN incident_user iu ON i.inc_user_id = iu.inc_user_id 
        WHERE i.inc_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $inc_id);
$stmt->execute();
$incident = $stmt->get_result()->fetch_assoc();

$sql_comments = "SELECT c.*, iu.user_name 
                 FROM comment c 
                 JOIN incident_user iu ON c.inc_user_id = iu.inc_user_id 
                 WHERE c.inc_id = ? ORDER BY c.created_at ASC";
$stmt_comments = $conn->prepare($sql_comments);
$stmt_comments->bind_param("i", $inc_id);
$stmt_comments->execute();
$comments = $stmt_comments->get_result();

$sql_status_history = "SELECT s.*, st.status_type, iu.user_name
                       FROM incident_status s
                       JOIN status_type st ON s.status_type_id = st.status_type_id
                       JOIN incident_user iu ON s.inc_user_id = iu.inc_user_id
                       WHERE s.inc_id = ? ORDER BY s.reported_at ASC";
$stmt_status = $conn->prepare($sql_status_history);
$stmt_status->bind_param("i", $inc_id);
$stmt_status->execute();
$status_history = $stmt_status->get_result();

$sql_images = "SELECT * FROM incident_evidence WHERE inc_id = ?";
$stmt_images = $conn->prepare($sql_images);
$stmt_images->bind_param("i", $inc_id);
$stmt_images->execute();
$images = $stmt_images->get_result();

$status_options = [];
$status_query = $conn->query("SELECT * FROM status_type");
while ($row = $status_query->fetch_assoc()) {
    $status_options[] = $row;
}

?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-4">Incident #<?= htmlspecialchars($incident['inc_id']) ?></h1>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </div>
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <?= htmlspecialchars($_SESSION['flash_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>



    <div class="card mb-4">
        <div class="card-body">
            <form action="update_incident.php" method="POST">
                <input type="hidden" name="inc_id" value="<?= $inc_id ?>">
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4" <?= ($role == 'Administrator' || $role == 'Reporter') ? '' : 'readonly' ?>><?= htmlspecialchars($incident['description']) ?></textarea>
                </div>

                <?php if ($role == 'Administrator' || $role == 'Reporter' || $role == 'Responder'): ?>
                    <button type="submit" class="btn btn-success">Update Description</button>
                <?php endif; ?>
            </form>

            <hr>

            <p><strong>Reported by:</strong> <?= htmlspecialchars($incident['user_name']) ?></p>
            <p><strong>Reported at:</strong> <?= htmlspecialchars($incident['reported_at']) ?></p>

            <?php if ($role == 'Administrator' || $role == 'Responder'): ?>
                <form action="update_status.php" method="POST" class="mt-3">
                    <input type="hidden" name="inc_id" value="<?= $inc_id ?>">
                    <div class="mb-3">
                        <label for="status_type" class="form-label">Change Status</label>
                        <select name="status_type_id" id="status_type" class="form-select" required>
                            <?php foreach ($status_options as $status): ?>
                                <option value="<?= $status['status_type_id'] ?>"><?= htmlspecialchars($status['status_type']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Status</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Status History</h5>
        </div>
        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
            <?php if ($status_history->num_rows > 0): ?>
                <?php while ($status = $status_history->fetch_assoc()): ?>
                    <div class="mb-2">
                        <strong><?= htmlspecialchars($status['user_name']) ?></strong>
                        changed status to
                        <span class="badge bg-info text-dark"><?= htmlspecialchars($status['status_type']) ?></span>
                        <small class="text-muted">(<?= htmlspecialchars($status['reported_at']) ?>)</small>
                    </div>
                    <hr>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">No status changes yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Uploaded Images</h5>
        </div>
        <div class="card-body">
            <?php if ($images->num_rows > 0): ?>
                <div class="row">
                    <?php while ($img = $images->fetch_assoc()): ?>
                        <div class="col-md-3 mb-3">
                            <img src="<?= htmlspecialchars($img['file_path']) ?>" class="img-fluid rounded" alt="Evidence">
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No images uploaded yet.</p>
            <?php endif; ?>

            <?php if ($role == 'Administrator' || $role == 'Reporter' || $role == 'Responder'): ?>
                <form action="upload_image.php" method="POST" enctype="multipart/form-data" class="mt-3">
                    <input type="hidden" name="inc_id" value="<?= $inc_id ?>">
                    <div class="mb-3">
                        <input type="file" name="image[]" multiple class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Images</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Comments</h5>
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            <?php if ($comments->num_rows > 0): ?>
                <?php while ($comment = $comments->fetch_assoc()): ?>
                    <div class="mb-3">
                        <strong><?= htmlspecialchars($comment['user_name']) ?></strong>
                        <small class="text-muted"><?= htmlspecialchars($comment['created_at']) ?></small>
                        <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                        <hr>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">No comments yet.</p>
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <form action="add_comment.php" method="POST">
                <div class="input-group">
                    <input type="hidden" name="inc_id" value="<?= $inc_id ?>">
                    <input type="text" name="content" class="form-control" placeholder="Write a comment..." maxlength="20" required>
                    <button class="btn btn-primary" type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>

</div>