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
$title = "Edit User";

// Hämta roller
$roles = [];
$result_roles = $conn->query("SELECT role_name FROM role");
if ($result_roles && $result_roles->num_rows > 0) {
    while ($row = $result_roles->fetch_assoc()) {
        $roles[] = $row['role_name'];
    }
}

// Hämta user_id från POST eller GET
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_user'])) {
    $userId = intval($_POST['user_id']);
} elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = intval($_GET['id']);
} else {
    $message = "❌ Invalid user ID!";
    include("pages/edit_user_content.php");
    exit();
}

// Funktion för att hämta användardata
function fetchUserData($conn, $userId) {
    $stmt = $conn->prepare("SELECT iu.inc_user_id, iu.user_name, iu.email, r.role_name
                            FROM incident_user iu
                            JOIN role r ON iu.role_id = r.role_id
                            WHERE iu.inc_user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows === 1) ? $result->fetch_assoc() : null;
}

// Hantera formuläruppdatering
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_user'])) {
    $userName = $conn->real_escape_string($_POST['user_name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $roleName = $conn->real_escape_string($_POST['role_name']);
    $newPassword = $_POST['password'] ?? '';

    if (!$email) {
        $message = "❌ Invalid email format!";
    } else {
        // Hämta role_id
        $stmt_role = $conn->prepare("SELECT role_id FROM role WHERE role_name = ?");
        $stmt_role->bind_param("s", $roleName);
        $stmt_role->execute();
        $result_role = $stmt_role->get_result();

        if ($result_role->num_rows === 1) {
            $roleId = $result_role->fetch_assoc()['role_id'];

            // Kolla om användarnamn eller epost redan finns
            $stmt_check = $conn->prepare("SELECT inc_user_id FROM incident_user WHERE (user_name = ? OR email = ?) AND inc_user_id != ?");
            $stmt_check->bind_param("ssi", $userName, $email, $userId);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $message = "❌ Username or email already exists!";
            } else {
                // Uppdatera användaren
                $stmt_update = $conn->prepare("UPDATE incident_user SET user_name = ?, email = ?, role_id = ? WHERE inc_user_id = ?");
                $stmt_update->bind_param("ssii", $userName, $email, $roleId, $userId);

                if ($stmt_update->execute()) {
                    // Om lösenord angetts – uppdatera det också
                    if (!empty($newPassword)) {
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        $stmt_pw = $conn->prepare("UPDATE incident_user SET password = ? WHERE inc_user_id = ?");
                        $stmt_pw->bind_param("si", $hashedPassword, $userId);
                        $stmt_pw->execute();
                        $stmt_pw->close();
                    }
                    $message = "✅ User updated successfully!";
                } else {
                    $message = "❌ Error updating user: " . $stmt_update->error;
                }
                $stmt_update->close();
            }
            $stmt_check->close();
        } else {
            $message = "❌ Invalid role!";
        }
        $stmt_role->close();
    }
}

// Hämta användaren igen
$user = fetchUserData($conn, $userId);
if (!$user) {
    $message = "❌ User not found!";
    include("pages/edit_user_content.php");
    exit();
}
$_SESSION['edit_user_data'] = $user;

$content = "pages/edit_user_content.php";
include("layout/layout.php");
?>
