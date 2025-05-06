<?php
require_once('db.php');
require_once('track_visit.php');

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$message = '';
$title = "Users";
$content = "pages/users_content.php";


try {
    if ($conn->connect_error) {
        die("Database Connection Error: " . $conn->connect_error);
    }

    $query = "SELECT iu.inc_user_id, iu.user_name, iu.email, r.role_name
              FROM incident_user iu
              JOIN role r ON iu.role_id = r.role_id";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    $stmt->close();
    $result->close();

} catch (Exception $e) {
    die("An error occurred: " . $e->getMessage());
}

if (isset($_POST['remove_user'])) {
    $user_id = $_POST['user_id'];

    try {
        $delete_query = "DELETE FROM incident_user WHERE inc_user_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            header("Location: users.php");
            exit();
        } else {
            $message = "Error removing user: " . $stmt->error;
        }
        $stmt->close();
    } catch (Exception $e) {
        die("Error removing user: " . $e->getMessage());
    }
}
include("layout/layout.php");
