<?php
require_once('db.php');
require_once('track_visit.php');
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: index.php"); 
    exit();
}

$message = '';
$title = "Create User";
$content = "pages/create_user_content.php"; 
$roles = [];
$res = $conn->query("SELECT role_name FROM role");
while ($row = $res->fetch_assoc()) {
    $roles[] = $row['role_name'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" &&
    !empty($_POST['user_name']) &&
    !empty($_POST['password']) &&
    !empty($_POST['email']) &&
    isset($_POST['role_name'])) {

    $userName = $conn->real_escape_string($_POST['user_name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password']; 
    $roleName = $conn->real_escape_string($_POST['role_name']);

    if ($email) {
        $stmt_role = $conn->prepare("SELECT role_id FROM role WHERE role_name = ?");
        $stmt_role->bind_param("s", $roleName);
        $stmt_role->execute();
        $result_role = $stmt_role->get_result();

        if ($result_role->num_rows === 1) {
            $row_role = $result_role->fetch_assoc();
            $roleId = $row_role['role_id'];

            $stmt_check = $conn->prepare("SELECT inc_user_id FROM incident_user WHERE user_name = ? OR email = ?");
            $stmt_check->bind_param("ss", $userName, $email);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $message = "Username or email already exists!";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt_insert = $conn->prepare("INSERT INTO incident_user (user_name, password, email, role_id) VALUES (?, ?, ?, ?)");
                $stmt_insert->bind_param("sssi", $userName, $hashedPassword, $email, $roleId);

                if ($stmt_insert->execute()) {
                    $message = "User created successfully!";
                } else {
                    $message = "Error creating user: " . $conn->error;
                }
                $stmt_insert->close();
            }
            $stmt_check->close();
        } else {
            $message = "❌ Invalid role selected!";
        }
        $stmt_role->close();
        $result_role->close();

    } else {
        $message = "❌ Invalid email format!";
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = "❌ Please fill in all fields!";
}

include("layout/layout.php");
?>