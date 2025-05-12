<?php 
require_once('db.php');
require_once('track_visit.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
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
$title = "SLA - Incident Status";

$content = "pages/sla_content.php";

$user_role = $_SESSION['role_name']; 

include('layout/layout.php');
?>
