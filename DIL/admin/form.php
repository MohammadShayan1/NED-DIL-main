<?php
// Include header (session management, sidebar, etc.)
include('header.php');
// Include database connection
require_once('config.php');

$activeTab = 'view'; // Default active tab is "View Data"
$message = '';

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ----- ADD RECORD -----
    if (isset($_POST['add'])) {
        $subject_text = $_POST['subject_text'];
    
        // Ensure the uploads directory exists
        $upload_dir = "../assets/uploads/forms/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
    
        // Check file upload and output error if any
        if (isset($_FILES['subject_file'])) {
            if ($_FILES['subject_file']['error'] !== 0) {
                $message = "File upload error: " . $_FILES['subject_file']['error'];
                $activeTab = 'add';
            } else {
                $file_name   = basename($_FILES["subject_file"]["name"]);
                $target_file = $upload_dir . time() . "_" . $file_name; // Unique file name
    
                if (move_uploaded_file($_FILES["subject_file"]["tmp_name"], $target_file)) {
                    // Insert record into the database using file path instead of link
                    $stmt = $conn->prepare("INSERT INTO Forms (form, file_path) VALUES (?, ?)");
                    $stmt->bind_param("ss", $subject_text, $target_file);
                    if ($stmt->execute()) {
                        $message = "Record added successfully!";
                        $activeTab = 'view';
                    } else {
                        $message = "Error: " . $stmt->error;
                        $activeTab = 'add';
                    }
                    $stmt->close();
                } else {
                    $message = "File upload failed.";
                    $activeTab = 'add';
                }
            }
        } else {
            $message = "Please upload a valid file.";
            $activeTab = 'add';
        }
    }
    

    // ----- UPDATE RECORD -----
    if (isset($_POST['update'])) {
        $id           = $_POST['id'];
        $subject_text = $_POST['subject_text'];

        // Ensure the uploads directory exists
        $upload_dir = "../assets/uploads/forms/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Check if a file is uploaded
        if (isset($_FILES['subject_file']) && $_FILES['subject_file']['error'] === 0) {
            $file_name   = basename($_FILES["subject_file"]["name"]);
            $target_file = $upload_dir . time() . "_" . $file_name; // Unique file name

            if (move_uploaded_file($_FILES["subject_file"]["tmp_name"], $target_file)) {
                // Update record with new file path
                $stmt = $conn->prepare("UPDATE Forms SET form = ?, file_path = ? WHERE id = ?");
                $stmt->bind_param("ssi", $subject_text, $target_file, $id);
                if ($stmt->execute()) {
                    $message = "Record updated successfully!";
                    $activeTab = 'view';
                } else {
                    $message = "Error: " . $stmt->error;
                    $activeTab = 'update';
                }
                $stmt->close();
            } else {
                $message = "File upload failed.";
                $activeTab = 'update';
            }
        } else {
            $message = "Please upload a valid file.";
            $activeTab = 'update';
        }
    }

    // ----- DELETE RECORD -----
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM Forms WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Record deleted successfully!";
            $activeTab = 'view';
        } else {
            $message = "Error: " . $stmt->error;
            $activeTab = 'delete';
        }
        $stmt->close();
    }
}

// Fetch records for the View Data tab
$result = $conn->query("SELECT * FROM Forms");
?>

<!-- Main Content Area -->
<div class="main-content">
  <?php if ($message != ''): ?>
      <div class="alert alert-info"><?php echo $message; ?></div>
  <?php endif; ?>

  <!-- Bootstrap Tabs -->
  <ul class="nav nav-tabs" id="crudTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link <?php echo ($activeTab=='view') ? 'active' : ''; ?>" id="view-tab" data-bs-toggle="tab" data-bs-target="#view" type="button" role="tab" aria-controls="view" aria-selected="<?php echo ($activeTab=='view') ? 'true' : 'false'; ?>">View Data</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link <?php echo ($activeTab=='add') ? 'active' : ''; ?>" id="add-tab" data-bs-toggle="tab" data-bs-target="#add" type="button" role="tab" aria-controls="add" aria-selected="<?php echo ($activeTab=='add') ? 'true' : 'false'; ?>">Add Data</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link <?php echo ($activeTab=='update') ? 'active' : ''; ?>" id="update-tab" data-bs-toggle="tab" data-bs-target="#update" type="button" role="tab" aria-controls="update" aria-selected="<?php echo ($activeTab=='update') ? 'true' : 'false'; ?>">Update Data</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link <?php echo ($activeTab=='delete') ? 'active' : ''; ?>" id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete" type="button" role="tab" aria-controls="delete" aria-selected="<?php echo ($activeTab=='delete') ? 'true' : 'false'; ?>">Delete Data</button>
    </li>
  </ul>
  <div class="tab-content mt-3" id="crudTabContent">
    <!-- View Data Tab -->
    <div class="tab-pane fade <?php echo ($activeTab=='view') ? 'show active' : ''; ?>" id="view" role="tabpanel" aria-labelledby="view-tab">
      <h3>View Records</h3>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>File</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['form']; ?></td>
                <td>
                  <?php if (!empty($row['file_path'])): ?>
                    <a href="<?php echo $row['file_path']; ?>" download>Download</a>
                  <?php else: ?>
                    No file available
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="3">No records found</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Add Data Tab -->
    <div class="tab-pane fade <?php echo ($activeTab=='add') ? 'show active' : ''; ?>" id="add" role="tabpanel" aria-labelledby="add-tab">
      <h3>Add New Record</h3>
      <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="subject_text" class="form-label">Subject Text</label>
          <input type="text" class="form-control" id="subject_text" name="subject_text" required>
        </div>
        <div class="mb-3">
          <label for="subject_file" class="form-label">Upload File</label>
          <input type="file" class="form-control" id="subject_file" name="subject_file" required>
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add Record</button>
      </form>
    </div>

    <!-- Update Data Tab -->
    <div class="tab-pane fade <?php echo ($activeTab=='update') ? 'show active' : ''; ?>" id="update" role="tabpanel" aria-labelledby="update-tab">
      <h3>Update Record</h3>
      <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="id" class="form-label">Record ID</label>
          <input type="number" class="form-control" id="id" name="id" required>
        </div>
        <div class="mb-3">
          <label for="subject_text" class="form-label">Subject Text</label>
          <input type="text" class="form-control" id="subject_text" name="subject_text" required>
        </div>
        <div class="mb-3">
          <label for="subject_file" class="form-label">Upload File</label>
          <input type="file" class="form-control" id="subject_file" name="subject_file" required>
        </div>
        <button type="submit" name="update" class="btn btn-warning">Update Record</button>
      </form>
    </div>

    <!-- Delete Data Tab -->
    <div class="tab-pane fade <?php echo ($activeTab=='delete') ? 'show active' : ''; ?>" id="delete" role="tabpanel" aria-labelledby="delete-tab">
      <h3>Delete Record</h3>
      <form method="POST" action="">
        <div class="mb-3">
          <label for="id" class="form-label">Record ID</label>
          <input type="number" class="form-control" id="id" name="id" required>
        </div>
        <button type="submit" name="delete" class="btn btn-danger">Delete Record</button>
      </form>
    </div>
  </div> <!-- End Tab Content -->
</div> <!-- End Main Content -->

<?php
include('footer.php');
?>
