<?php
include 'config.php';

$id = $_GET['id'];
$query = "DELETE FROM Employees WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: team-management.php");
?>
