<?php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$userId = $_SESSION['user_id'] ?? null;
$pageUrl = $_SERVER['SCRIPT_NAME'];  
$ip = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];

$browserName = simplifyBrowserName($userAgent);

$browserStmt = $conn->prepare("SELECT browser_id FROM web_browser WHERE browser_name = ?");
$browserStmt->bind_param("s", $browserName);
$browserStmt->execute();
$browserResult = $browserStmt->get_result();

if ($browserResult->num_rows === 0) {
    $insertBrowser = $conn->prepare("INSERT INTO web_browser (browser_name) VALUES (?)");
    $insertBrowser->bind_param("s", $browserName);
    $insertBrowser->execute();
    $browserId = $insertBrowser->insert_id;
} else {
    $browserId = $browserResult->fetch_assoc()['browser_id'];
}

$pageStmt = $conn->prepare("SELECT page_id FROM web_page WHERE url = ?");
$pageStmt->bind_param("s", $pageUrl);
$pageStmt->execute();
$pageResult = $pageStmt->get_result();

if ($pageResult->num_rows === 0) {
    $insertPage = $conn->prepare("INSERT INTO web_page (url) VALUES (?)");
    $insertPage->bind_param("s", $pageUrl);
    $insertPage->execute();
    $pageId = $insertPage->insert_id;
} else {
    $pageId = $pageResult->fetch_assoc()['page_id'];
}

$insertVisit = $conn->prepare("INSERT INTO visit (page_id, browser_id, ip_address, created_at) VALUES (?, ?, ?, NOW())");
$insertVisit->bind_param("iis", $pageId, $browserId, $ip);
$insertVisit->execute();
$visitId = $conn->insert_id;

if ($userId) {
    $stmt = $conn->prepare("INSERT INTO user_visit (inc_user_id, visit_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $visitId);
    $stmt->execute();
}

function simplifyBrowserName($ua) {
    if (stripos($ua, 'Chrome') !== false && stripos($ua, 'Edg') === false) return 'Chrome';
    if (stripos($ua, 'Firefox') !== false) return 'Firefox';
    if (stripos($ua, 'Safari') !== false && stripos($ua, 'Chrome') === false) return 'Safari';
    if (stripos($ua, 'Edg') !== false) return 'Edge';
    if (stripos($ua, 'Opera') !== false || stripos($ua, 'OPR') !== false) return 'Opera';
    return 'Other';
}
?>
