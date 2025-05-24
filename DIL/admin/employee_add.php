<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $designation = trim($_POST["designation"]);
    $office_number = trim($_POST["office_number"]);
    
    // Handle image upload
    $target_dir = "../assets/images/employees/";
    $image_path = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    
    // Get max position for new employee
    $result = $conn->query("SELECT MAX(position) AS max_pos FROM Employees");
    $row = $result->fetch_assoc();
    $new_position = $row['max_pos'] + 1;

    // Insert employee into database
    $query = "INSERT INTO Employees (name, email, designation, office_number, image_path, position) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $name, $email, $designation, $office_number, $image_path, $new_position);
    
    if ($stmt->execute()) {
        header("Location: team-management.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
