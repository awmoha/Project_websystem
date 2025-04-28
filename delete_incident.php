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

    // Ta bort alla kommentarer kopplade till incidenten
    $delete_comments_stmt = $conn->prepare("DELETE FROM comment WHERE inc_id = ?");
    $delete_comments_stmt->bind_param("i", $id);
    $delete_comments_stmt->execute();
    $delete_comments_stmt->close();

    // Ta bort alla tillgångar kopplade till incidenten
    $delete_assets_stmt = $conn->prepare("DELETE FROM incident_asset WHERE incident_id = ?");
    $delete_assets_stmt->bind_param("i", $id);
    $delete_assets_stmt->execute();
    $delete_assets_stmt->close();

    // Ta bort alla bevis kopplade till incidenten
    $delete_evidence_stmt = $conn->prepare("DELETE FROM incident_evidence WHERE inc_id = ?");
    $delete_evidence_stmt->bind_param("i", $id);
    $delete_evidence_stmt->execute();
    $delete_evidence_stmt->close();

    // Ta bort alla statusar kopplade till incidenten
    $delete_status_stmt = $conn->prepare("DELETE FROM incident_status WHERE inc_id = ?");
    $delete_status_stmt->bind_param("i", $id);
    $delete_status_stmt->execute();
    $delete_status_stmt->close();

    // Ta bort incidenten
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
