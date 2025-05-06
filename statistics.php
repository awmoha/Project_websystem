<?php
require_once('db.php');
session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



function runQuery($conn, $sql) {
   $result = $conn->query($sql);
   if ($result === false) {
       die("Query failed: " . $conn->error . "<br>SQL: " . $sql);
   }
   return $result;
}


$perPage = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $perPage;




$countResult = runQuery($conn, "
   SELECT COUNT(*) as total
   FROM visit v
   JOIN web_page p ON v.page_id = p.page_id
   JOIN web_browser b ON v.browser_id = b.browser_id
   LEFT JOIN user_visit uv ON v.visit_id = uv.visit_id
   LEFT JOIN incident_user u ON uv.inc_user_id = u.inc_user_id
");
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $perPage);




$result = runQuery($conn, "
   SELECT v.*, uv.inc_user_id AS user_id, u.user_name, p.url AS page_url, b.browser_name
   FROM visit v
   JOIN web_page p ON v.page_id = p.page_id
   JOIN web_browser b ON v.browser_id = b.browser_id
   LEFT JOIN user_visit uv ON v.visit_id = uv.visit_id
   LEFT JOIN incident_user u ON uv.inc_user_id = u.inc_user_id
   ORDER BY v.created_at DESC
   LIMIT $perPage OFFSET $offset
");




$usersResult = runQuery($conn, "SELECT inc_user_id, user_name FROM incident_user ORDER BY user_name");
$users = [];
while ($row = $usersResult->fetch_assoc()) {
   $users[] = $row;
}


if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
   $selectedUserId = (int) $_GET['user_id'];


  
   $userPerPage = 10;
   $userPage = isset($_GET['user_page']) && is_numeric($_GET['user_page']) ? (int) $_GET['user_page'] : 1;
   $userOffset = ($userPage - 1) * $userPerPage;


  
   $countUserVisitsResult = runQuery($conn, "
       SELECT COUNT(*) as total
       FROM incident_user iu
       LEFT JOIN user_visit uv ON iu.inc_user_id = uv.inc_user_id
       LEFT JOIN visit v ON uv.visit_id = v.visit_id
       WHERE iu.inc_user_id = $selectedUserId
   ");
   $totalUserRows = $countUserVisitsResult->fetch_assoc()['total'];
   $totalUserPages = ceil($totalUserRows / $userPerPage);


 
   $userVisitQuery = "
       SELECT iu.inc_user_id, iu.user_name, v.created_at,
              v.ip_address, b.browser_name, p.url AS page_url
       FROM incident_user iu
       LEFT JOIN user_visit uv ON iu.inc_user_id = uv.inc_user_id
       LEFT JOIN visit v ON uv.visit_id = v.visit_id
       LEFT JOIN web_page p ON v.page_id = p.page_id
       LEFT JOIN web_browser b ON v.browser_id = b.browser_id
       WHERE iu.inc_user_id = $selectedUserId
       ORDER BY v.created_at DESC
       LIMIT $userPerPage OFFSET $userOffset
   ";
   $userVisits = runQuery($conn, $userVisitQuery);
}




$summaryResult = runQuery($conn, "
   SELECT p.url AS page_url, COUNT(v.visit_id) AS total_visits,
          COUNT(DISTINCT uv.inc_user_id) AS unique_users
   FROM visit v
   JOIN web_page p ON v.page_id = p.page_id
   LEFT JOIN user_visit uv ON v.visit_id = uv.visit_id
   GROUP BY p.url
   ORDER BY total_visits DESC
");

$title = "Visit Statistics";
$content = "pages/statistics_content.php";


include("layout/layout.php");


