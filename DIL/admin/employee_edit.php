<?php
include 'config.php';

$id = $_GET['id'];
$query = "SELECT * FROM Employees WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $designation = $_POST["designation"];
    $office_number = $_POST["office_number"];
    
    $query = "UPDATE Employees SET name=?, email=?, designation=?, office_number=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $name, $email, $designation, $office_number, $id);

    if ($stmt->execute()) {
        header("Location: team-management.php");
    }
}
?>
