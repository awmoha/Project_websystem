<?php
session_start();
require_once 'db.php';
require_once('track_visit.php');

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

$sql = "
  SELECT i.*, iu.user_name, r.role_name, 
  (SELECT st.status_type 
   FROM incident_status ists 
   JOIN status_type st ON ists.status_type_id = st.status_type_id
   WHERE ists.inc_id = i.inc_id 
   ORDER BY ists.reported_at DESC LIMIT 1) AS current_status
  FROM incident i
  JOIN incident_user iu ON i.inc_user_id = iu.inc_user_id
  JOIN role r ON iu.role_id = r.role_id
";

if ($role == 'Reporter') {
    $sql .= " WHERE i.inc_user_id = ? ";
}
$sql .= " ORDER BY i.inc_id DESC";
$stmt = $conn->prepare($sql);
if ($role == 'Reporter') {
    $stmt->bind_param("i", $user_id);
}


$stmt->execute();
$result = $stmt->get_result();
$title = "Incidents";
$content = "pages/incidents_content.php";

include('layout/layout.php');
