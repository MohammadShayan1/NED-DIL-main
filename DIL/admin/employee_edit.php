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

$id = $_GET['id'];
$query = "SELECT * FROM employees WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    die("Employee not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = trim($_POST["email"]);
    $designation = $_POST["designation"];
    $office_number = $_POST["office_number"];
    
    // Handle empty email - set to NULL if empty
    $email = empty($email) ? NULL : $email;
    
    $query = "UPDATE employees SET name=?, email=?, designation=?, office_number=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $name, $email, $designation, $office_number, $id);

    if ($stmt->execute()) {
        $success_message = "Employee updated successfully!";
        // Refresh employee data
        $query = "SELECT * FROM employees WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();
    } else {
        $error_message = "Error: " . $stmt->error;
    }
}
?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4">
            <i class="fas fa-user-edit me-2"></i>Edit Employee
        </h2>
        
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

        <!-- Edit Employee Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-circle me-2"></i>Edit Employee Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-user me-1"></i>Full Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="name" 
                                               name="name" 
                                               value="<?php echo htmlspecialchars($employee['name']); ?>"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-1"></i>Email Address
                                            <small class="text-muted">(Optional)</small>
                                        </label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               value="<?php echo $employee['email'] ? htmlspecialchars($employee['email']) : ''; ?>"
                                               placeholder="employee@neduet.edu.pk">
                                        <small class="text-muted">Leave empty if not available</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="designation" class="form-label">
                                            <i class="fas fa-briefcase me-1"></i>Designation <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="designation" 
                                               name="designation" 
                                               value="<?php echo htmlspecialchars($employee['designation']); ?>"
                                               placeholder="e.g., Professor, Assistant Professor, Lecturer"
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="office_number" class="form-label">
                                            <i class="fas fa-door-open me-1"></i>Office Number
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="office_number" 
                                               name="office_number" 
                                               value="<?php echo htmlspecialchars($employee['office_number'] ?? ''); ?>"
                                               placeholder="e.g., Room 201, Block A">
                                    </div>
                                </div>
                            </div>

                            <!-- Current Profile Image Display -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-camera me-1"></i>Current Profile Image
                                </label>
                                <div class="border rounded p-3">
                                    <?php if ($employee['image_path']): ?>
                                        <img src="<?php echo htmlspecialchars($employee['image_path']); ?>" 
                                             alt="Current Profile" 
                                             class="rounded-circle" 
                                             style="width: 100px; height: 100px; object-fit: cover;">
                                        <p class="mt-2 mb-0 text-muted">
                                            <small>To change the profile image, please contact the administrator.</small>
                                        </p>
                                    <?php else: ?>
                                        <p class="text-muted mb-0">No profile image available</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="team-management.php" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i>Update Employee
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-header bg-info bg-opacity-10">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Edit Notes
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li><strong>Email Field:</strong> This field is optional. If an employee doesn't have an email, you can leave it empty.</li>
                            <li><strong>Profile Image:</strong> Image editing is not available in this form. Contact the administrator to change the profile image.</li>
                            <li><strong>Position:</strong> Employee position in the team list cannot be changed here. Use the move up/down buttons in the Team Management page.</li>
                            <li><strong>Required Fields:</strong> Name and Designation are mandatory fields.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
