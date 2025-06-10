<?php
include 'config.php';

// Ensure admin is logged in
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

// Validate input
if (!isset($_GET['id'], $_GET['direction'])) {
    die("Invalid request.");
}

$employee_id = intval($_GET['id']);
$direction = $_GET['direction'];

// Fetch the current employee details
$sql = "SELECT id, position FROM employees WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    die("Employee not found.");
}

$current_position = $employee['position'];

if ($direction === 'up') {
    // Find the employee just above the current one
    $sql = "SELECT id, position FROM employees WHERE position < ? ORDER BY position DESC LIMIT 1";
} elseif ($direction === 'down') {
    // Find the employee just below the current one
    $sql = "SELECT id, position FROM employees WHERE position > ? ORDER BY position ASC LIMIT 1";
} else {
    die("Invalid direction.");
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_position);
$stmt->execute();
$result = $stmt->get_result();
$swap_employee = $result->fetch_assoc();

if (!$swap_employee) {
    // No employee to swap with (first or last position)
    header("Location: team_management.php");
    exit();
}

// Swap positions
$conn->begin_transaction();

try {
    // Update the swapped employee's position
    $sql = "UPDATE employees SET position = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $current_position, $swap_employee['id']);
    $stmt->execute();

    // Update the current employee's position
    $sql = "UPDATE employees SET position = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $swap_employee['position'], $employee_id);
    $stmt->execute();

    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    die("Error updating positions: " . $e->getMessage());
}

// Redirect back
header("Location: team_management.php");
exit();
?>
