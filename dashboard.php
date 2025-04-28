<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once('db.php');

// Function to fetch all necessary data
function getDashboardData($conn, $user_id, $user_role)
{
    $data = [];

    try {
        // Total incidents
        // Total incidents (utan rollbaserat filter)
        $query = "SELECT COUNT(*) as total_incidents FROM incident";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $data['total_incidents'] = $result->fetch_assoc()['total_incidents'];
        $stmt->close();

        // Severity counts (utan rollbaserat filter)
        $query = "SELECT is2.severity_name, COUNT(i.inc_id) as count_by_severity 
          FROM incident i 
          JOIN incident_severity is2 ON i.inc_sev_id = is2.inc_sev_id
          GROUP BY is2.severity_name";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $data['severity_counts'] = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Type counts (utan rollbaserat filter)
        $query = "SELECT it.type_name, COUNT(i.inc_id) as count_by_type 
          FROM incident i 
          JOIN incident_type it ON i.inc_type_id = it.inc_type_id
          GROUP BY it.type_name";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $data['type_counts'] = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        // Status counts (utan rollbaserat filter)

        $query = "SELECT st.status_type, COUNT(i_s.inc_id) as count_by_status
          FROM incident_status i_s
          JOIN status_type st ON i_s.status_type_id = st.status_type_id
          GROUP BY st.status_type";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die('MySQL prepare failed: ' . $conn->error);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data['status_counts'] = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();


        // Incidents per month (utan rollbaserat filter)
        $labelsIncidents = [];
        $dataIncidents = [];
        $res = $conn->query("SELECT COUNT(*) AS total, DATE_FORMAT(reported_at, '%Y-%m') AS month
                     FROM incident
                     GROUP BY month
                     ORDER BY month DESC LIMIT 6");
        while ($row = $res->fetch_assoc()) {
            $labelsIncidents[] = $row['month'];
            $dataIncidents[] = $row['total'];
        }
        $data['incidents_per_month'] = ['labels' => $labelsIncidents, 'data' => $dataIncidents];

        // Incident types (utan rollbaserat filter)
        $incidentTypes = ['labels' => [], 'data' => []];
        $res = $conn->query("SELECT it.type_name, COUNT(*) AS total
                     FROM incident i
                     JOIN incident_type it ON i.inc_type_id = it.inc_type_id
                     GROUP BY it.type_name");
        while ($row = $res->fetch_assoc()) {
            $incidentTypes['labels'][] = $row['type_name'];
            $incidentTypes['data'][] = $row['total'];
        }
        $data['incident_types'] = $incidentTypes;



        // Users over time (utan rollbaserat filter)
        $userStats = ['labels' => [], 'data' => []];
        $res = $conn->query("SELECT DATE_FORMAT(i.reported_at, '%Y-%m') AS month, COUNT(DISTINCT iu.inc_user_id) AS total
                     FROM incident i
                     JOIN incident_user iu ON i.inc_user_id = iu.inc_user_id
                     GROUP BY month
                     ORDER BY month DESC LIMIT 6");
        while ($row = $res->fetch_assoc()) {
            $userStats['labels'][] = $row['month'];
            $userStats['data'][] = $row['total'];
        }
        $data['users_over_time'] = $userStats;

        // Total stats (utan rollbaserat filter)
        $totals = [
            'users' => $conn->query("SELECT COUNT(*) FROM incident_user")->fetch_row()[0],
            'incidents' => $conn->query("SELECT COUNT(*) FROM incident")->fetch_row()[0],
            'assets' => $conn->query("SELECT COUNT(*) FROM asset")->fetch_row()[0],
        ];
        $data['totals'] = $totals;
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }

    return $data;
}

// Get session data
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Fetch all the data for the dashboard
$dashboardData = getDashboardData($conn, $user_id, $user_role);

// Title + layout
$title = "Dashboard";
$content = "pages/dashboard_content.php";
include('layout/layout.php');
