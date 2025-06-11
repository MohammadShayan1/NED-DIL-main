<?php
include 'header.php';
include 'config.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

// Fetch employees from the database
$query = "SELECT * FROM employees ORDER BY position ASC";
$result = $conn->query($query);
if ($result === false) {
    die("Query failed: " . $conn->error);
}
?>

<div class="main-content">
    <h2 class="text-center">Employee Management</h2>
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

    <!-- Add Employee Form -->
    <form action="employee_add.php" method="POST" enctype="multipart/form-data" class="mb-4 p-3 border rounded bg-light">
        <h4>Add Employee</h4>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" >
        </div>
        <div class="mb-3">
            <label>Designation</label>
            <input type="text" name="designation" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Office Number</label>
            <input type="text" name="office_number" class="form-control">
        </div>
        <div class="mb-3">
            <label>Profile Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Add Employee</button>
    </form>

    <!-- Employee Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Designation</th>
                <th>Office Number</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['designation']) ?></td>
                    <td><?= htmlspecialchars($row['office_number'] ) ?></td>
                    <td><img src="<?= htmlspecialchars($row['image_path']) ?>" width="50" height="50" class="rounded-circle"></td>
                    <td>
                        <a href="employee_edit.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="employee_delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                        <a href="employee_move.php?id=<?= $row['id'] ?>&direction=up" class="btn btn-secondary btn-sm">⬆</a>
                        <a href="employee_move.php?id=<?= $row['id'] ?>&direction=down" class="btn btn-secondary btn-sm">⬇</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php';?>
