<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Administrator') {
    $_SESSION['flash_message'] = "Unauthorized access.";
    header("Location: incidents.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // I och med att vi har FK som är kopplat till assets då måste vi först, ta bort alla relaterade tillgångar från incident_asset
    $delete_assets_stmt = $conn->prepare("DELETE FROM incident_asset WHERE incident_id = ?");
    $delete_assets_stmt->bind_param("i", $id);
    $delete_assets_stmt->execute();
    $delete_assets_stmt->close();

    // och sen tya bort allt 
    $delete_incident_stmt = $conn->prepare("DELETE FROM incident WHERE inc_id = ?");
    $delete_incident_stmt->bind_param("i", $id);
    if ($delete_incident_stmt->execute()) {
        $_SESSION['flash_message'] = "Incident deleted successfully.";
    } else {
        $_SESSION['flash_message'] = "Failed to delete incident. Error: " . mysqli_error($conn);
    }
    $delete_incident_stmt->close();
} else {
    $_SESSION['flash_message'] = "Invalid incident ID.";
}

header("Location: incidents.php");
exit();
?>
