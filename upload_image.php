<?php
require_once('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inc_id = intval($_POST['inc_id']);
    $user_id = $_SESSION['user_id'];
    $uploaded_at = date('Y-m-d H:i:s');

    $files = $_FILES['evidence_files'];
    $errors = [];
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    $upload_dir = 'media/uploads/';

    if (!file_exists($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            $errors[] = "Kunde inte skapa upload-katalogen.";
        }
    }

    if ($files['name'][0] != '') {
        foreach ($files['name'] as $key => $name) {
            $file_name = basename($files['name'][$key]);
            $file_tmp = $files['tmp_name'][$key];
            $file_size = $files['size'][$key];
            $file_error = $files['error'][$key];

            if ($file_error === 0) {
                if ($file_size <= 2000000) { 
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    if (in_array($file_ext, $allowed_ext)) {
                        $new_file_name = uniqid() . '.' . $file_ext;
                        $file_destination = $upload_dir . $new_file_name;

                        if (move_uploaded_file($file_tmp, $file_destination)) {
                            $query = "INSERT INTO incident_evidence (inc_id, inc_user_id, file_path, uploaded_at) VALUES (?, ?, ?, ?)";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("iiss", $inc_id, $user_id, $file_destination, $uploaded_at);
                            
                            if (!$stmt->execute()) {
                                $errors[] = "Failed to move uploaded  $file_name: " . $stmt->error;
                            }
                        } else {
                            $errors[] = "Could not move the file $file_name.";
                        }
                    } else {
                        $errors[] = "Invalid file type: $file_name. Just " . implode(", ", $allowed_ext) . " är tillåtna.";
                    }
                } else {
                    $errors[] = "File $file_name is too large (max 2MB).";
                }
            } else {
                $errors[] = "Error uploading file $file_name.";
            }
        }
    } else {
        $errors[] = "No file selected for upload.";
    }

    if (empty($errors)) {
        $_SESSION['flash_message'] = "Files uploaded successfully.";
    } else {
        $_SESSION['flash_message'] = implode('<br>', $errors);
    }

    header("Location: view.php?id=" . $inc_id);
    exit();
}
?>
