<?php
session_start();
require_once 'db.php';  
require_once('track_visit.php');


$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT iu.*, r.role_name 
                            FROM incident_user iu 
                            JOIN role r ON iu.role_id = r.role_id 
                            WHERE iu.user_name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['inc_user_id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['role'] = $user['role_name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "User not found.";
    }
}

$title = "Login";
$content = "pages/login_content.php";

include("layout/layout.php");
