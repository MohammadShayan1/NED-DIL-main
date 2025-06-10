<?php
include 'header.php';
include 'config.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $designation = trim($_POST["designation"]);
    $office_number = trim($_POST["office_number"]);
    
    // Handle empty email - set to NULL if empty
    $email = empty($email) ? NULL : $email;
    
    // Handle image upload
    $target_dir = "../assets/images/employees/";
    $image_path = "";
    
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $image_name = basename($_FILES["image"]["name"]);
        $image_path = $target_dir . $image_name;
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
        // Store relative path for database
        $image_path = "../assets/images/employees/" . $image_name;
    }
    
    // Get max position for new employee (using correct table name)
    $result = $conn->query("SELECT MAX(position) AS max_pos FROM employees");
    $row = $result->fetch_assoc();
    $new_position = $row['max_pos'] + 1;

    // Insert employee into database with NULL handling for email
    $query = "INSERT INTO employees (name, email, designation, office_number, image_path, position) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $name, $email, $designation, $office_number, $image_path, $new_position);
    
    if ($stmt->execute()) {
        $success_message = "Employee added successfully!";
        // Clear form data after successful submission
        $name = $email = $designation = $office_number = "";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
}
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Back to Team Management -->
        <div class="mb-3">
            <a href="team-management.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Team Management
            </a>
        </div>

        <!-- Messages -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Help Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-header bg-info bg-opacity-10">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Important Notes
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li><strong>Email Field:</strong> This field is optional. If an employee doesn't have an email, you can leave it empty.</li>
                            <li><strong>Profile Image:</strong> Please upload a professional photo. The image will be displayed in the team section of the website.</li>
                            <li><strong>Position:</strong> New employees will automatically be added to the end of the team list. You can reorder them later from the Team Management page.</li>
                            <li><strong>Required Fields:</strong> Name, Designation, and Profile Image are mandatory fields.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
