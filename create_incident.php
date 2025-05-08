<?php
require_once('db.php');
session_start();
require_once('track_visit.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit();
}

$title = "Create Incident";

$content = "pages/create_incident_content.php";
?>


    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $inc_type_id = filter_input(INPUT_POST, 'inc_type_id', FILTER_VALIDATE_INT);
        $inc_sev_id = filter_input(INPUT_POST, 'inc_sev_id', FILTER_VALIDATE_INT);
        $inc_user_id = $_SESSION['user_id']; 
        $description = htmlspecialchars(trim($_POST['description']));
        $reported_at = date('Y-m-d H:i:s'); 
        $affected_assets = isset($_POST['affected_assets']) ? $_POST['affected_assets'] : array(); 

        $files = $_FILES['evidence_files'];
        $uploaded_files = array();

        $errors = array();
        if (!$inc_type_id) {
            $errors[] = "Incident Type is required";
        }
        if (!$inc_sev_id) {
            $errors[] = "Incident Severity is required";
        }
        if (empty($description)) {
            $errors[] = "Description is required";
        }
        if (empty($affected_assets)) {
            $errors[] = "Affected Assets are required";
        }

        if ($files['name'][0] != '') { 
            foreach ($files['name'] as $key => $name) {
                $file_name = $files['name'][$key];
                $file_tmp = $files['tmp_name'][$key];
                $file_size = $files['size'][$key];
                $file_error = $files['error'][$key];

                if ($file_error == 0) { 
                    if ($file_size <= 2000000) { 
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'); 

                        if (in_array($file_ext, $allowed_ext)) {
                            $new_file_name = uniqid() . '.' . $file_ext;
                            $upload_dir = 'media/uploads/'; 
                            if (!file_exists($upload_dir)) {
                                mkdir($upload_dir, 0777, true); 
                            }
                            $file_destination = $upload_dir . $new_file_name;
                            if (move_uploaded_file($file_tmp, $file_destination)) {
                                $uploaded_files[] = $file_destination; 
                            } else {
                                $errors[] = "Failed to upload file: " . $file_name;
                            }
                        } else {
                            $errors[] = "Invalid file type: " . $file_name;
                        }
                    } else {
                        $errors[] = "File too large (max 2MB): " . $file_name;
                    }
                } else {
                    $errors[] = "Error uploading file: " . $file_name;
                }
            }
        }


        if (empty($errors)) {
            try {
                $conn->begin_transaction();

                $query = "INSERT INTO incident (inc_type_id, inc_sev_id, inc_user_id, description, reported_at)
                          VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);

                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $conn->error . "<br>SQL: " . $query);
                }

                $stmt->bind_param("iiiss", $inc_type_id, $inc_sev_id, $inc_user_id, $description, $reported_at);

                if (!$stmt->execute()) {
                    throw new Exception("Error creating incident: " . $stmt->error . "</div>");
                }

                $last_inserted_id = $conn->insert_id;

                foreach ($affected_assets as $asset_id) {
                    $insert_asset_query = "INSERT INTO incident_asset (incident_id, asset_id) VALUES (?, ?)";
                    $asset_stmt = $conn->prepare($insert_asset_query);
                    if (!$asset_stmt) {
                        throw new Exception("Prepare failed: " . $conn->error . "<br>SQL: " . $insert_asset_query);
                    }
                    $asset_stmt->bind_param("ii", $last_inserted_id, $asset_id);
                    if (!$asset_stmt->execute()) {
                        throw new Exception("Error creating incident asset: " . $asset_stmt->error . "</div>");
                    }
                    $asset_stmt->close();
                }

                if (!empty($uploaded_files)) {
                    foreach ($uploaded_files as $file_path) {
                        $original_file_name = '';
                        foreach ($files['tmp_name'] as $key => $tmp_name) {
                            if (strpos($file_path, basename($files['tmp_name'][$key])) !== false) {
                                $original_file_name = $files['name'][$key];
                                break;
                            }
                        }

                        $file_insert_query = "INSERT INTO incident_evidence (inc_id, inc_user_id, file_path, uploaded_at) VALUES (?, ?, ?, ?)"; // Removed file_name
                        $file_stmt = $conn->prepare($file_insert_query);
                        if (!$file_stmt) {
                            throw new Exception("Prepare failed: " . $conn->error . "<br>SQL: " . $file_insert_query);
                        }
                        $file_stmt->bind_param("iiss", $last_inserted_id, $_SESSION['user_id'], $file_path, $reported_at); // corrected
                        if (!$file_stmt->execute()) {
                            throw new Exception("Error creating incident evidence: " . $file_stmt->error . "</div>");
                        }
                        $file_stmt->close();
                    }
                }

                $conn->commit();
                echo "<div class='alert alert-success'>Incident created successfully!</div>";
                header("Location: incidents.php"); //  Redirect to the incidents.php
                exit;
            } catch (Exception $e) {
                $conn->rollback();
                echo "<div class='alert alert-danger'>An error occurred: " . $e->getMessage() . "</div>";
            } finally {
                if ($stmt) {
                    $stmt->close();
                }
            }
        } else {
            echo "<div class='alert alert-danger'><strong>The following errors occurred:</strong><br>";
            foreach ($errors as $error) {
                echo "- " . $error . "<br>";
            }
            echo "</div>";
        }
    }
    include('layout/layout.php');

    ?>