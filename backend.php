<?php
// backend.php
$servername = "localhost";
$username = "root"; // default for XAMPP
$password = "Popyface12";     // default no password
$dbname = "smart_attendance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

$action = $_POST['action'] ?? '';

if ($action === 'generate') {
  $className = $_POST['className'];
  $classDate = $_POST['classDate'];
  $classTime = $_POST['classTime'];
  $sessionId = $_POST['sessionId'];
  $qrData = $_POST['qrData'];

  $stmt = $conn->prepare("INSERT INTO sessions (class_name, date_time, qr_code, status) VALUES (?, ?, ?, 'active')");
  $datetime = $classDate . ' ' . $classTime;
  $stmt->bind_param("sss", $className, $datetime, $qrData);
  $stmt->execute();
  echo json_encode(["status" => "success", "message" => "Session QR saved to database."]);

} elseif ($action === 'endSession') {
  $conn->query("UPDATE sessions SET status='ended' WHERE status='active'");
  echo json_encode(["status" => "success", "message" => "Active session ended."]);
} else {
  echo json_encode(["status" => "error", "message" => "Invalid action."]);
}

$conn->close();
?>
