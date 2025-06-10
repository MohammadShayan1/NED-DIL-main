<?php
include 'config.php';

$id = $_GET['id'];

// Step 1: Get the file path from the database
$query = "SELECT image_path FROM employees WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($file_path);
$stmt->fetch();
$stmt->close();

// Step 2: Delete the file from the server
if ($file_path && file_exists($file_path)) {
    unlink($file_path);
}

// Step 3: Delete the database record
$query = "DELETE FROM employees WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Redirect
header("Location: team-management.php");
exit;
?>
