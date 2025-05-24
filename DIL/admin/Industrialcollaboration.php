<?php
// Include header (session management, sidebar, etc.)
include('header.php');

// Include database connection
require_once('config.php');

$activeTab = 'view'; // Default active tab
$message = '';
$uploadDir = '../assets/uploads/industrial_collaboration/'; // Upload directory

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Add Record
    if (isset($_POST['add'])) {
        $subject_text = $_POST['subject_text'];
        $issue_date   = $_POST['issue_date'];

        if (!empty($_FILES['subject_file']['name'])) {
            $fileName = basename($_FILES['subject_file']['name']);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['subject_file']['tmp_name'], $filePath)) {
                $stmt = $conn->prepare("INSERT INTO industrial_collaboration (subject_text, subject_file, issue_date) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $subject_text, $filePath, $issue_date);
                $stmt->execute();
                $stmt->close();
                $message = "Record added successfully!";
                $activeTab = 'view';
            } else {
                $message = "File upload failed.";
                $activeTab = 'add';
            }
        } else {
            $message = "Please upload a file.";
            $activeTab = 'add';
        }
    }

    // Update Record
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $subject_text = $_POST['subject_text'];
        $issue_date = $_POST['issue_date'];
        $filePath = '';

        if (!empty($_FILES['subject_file']['name'])) {
            $fileName = basename($_FILES['subject_file']['name']);
            $filePath = $uploadDir . $fileName;
            move_uploaded_file($_FILES['subject_file']['tmp_name'], $filePath);
        }

        if ($filePath) {
            $stmt = $conn->prepare("UPDATE industrial_collaboration SET subject_text=?, subject_file=?, issue_date=? WHERE id=?");
            $stmt->bind_param("sssi", $subject_text, $filePath, $issue_date, $id);
        } else {
            $stmt = $conn->prepare("UPDATE industrial_collaboration SET subject_text=?, issue_date=? WHERE id=?");
            $stmt->bind_param("ssi", $subject_text, $issue_date, $id);
        }

        if ($stmt->execute()) {
            $message = "Record updated successfully!";
            $activeTab = 'view';
        } else {
            $message = "Error: " . $stmt->error;
            $activeTab = 'update';
        }
        $stmt->close();
    }

    // Delete Record
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM industrial_collaboration WHERE id=?");
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

$result = $conn->query("SELECT * FROM industrial_collaboration ORDER BY issue_date ASC");
?>

<div class="main-content">
<?php if ($message): ?>
  <div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>

<ul class="nav nav-tabs">
  <li class="nav-item">
    <button class="nav-link <?php echo ($activeTab == 'view') ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#view">View</button>
  </li>
  <li class="nav-item">
    <button class="nav-link <?php echo ($activeTab == 'add') ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#add">Add</button>
  </li>
  <li class="nav-item">
    <button class="nav-link <?php echo ($activeTab == 'update') ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#update">Update</button>
  </li>
  <li class="nav-item">
    <button class="nav-link <?php echo ($activeTab == 'delete') ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#delete">Delete</button>
  </li>
</ul>

<div class="tab-content mt-3">
  <!-- View Tab -->
  <div class="tab-pane fade <?php echo ($activeTab == 'view') ? 'show active' : ''; ?>" id="view">
    <h3>View Records</h3>
    <table class="table table-bordered">
      <thead>
        <tr><th>ID</th><th>Subject</th><th>File</th><th>Date</th></tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['subject_text']; ?></td>
            <td><a href="<?php echo $row['subject_file']; ?>" target="_blank">Download</a></td>
            <td><?php echo $row['issue_date']; ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Add Tab -->
  <div class="tab-pane fade <?php echo ($activeTab == 'add') ? 'show active' : ''; ?>" id="add">
    <h3>Add Record</h3>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Subject Text</label>
        <input type="text" name="subject_text" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Upload File</label>
        <input type="file" name="subject_file" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Issue Date</label>
        <input type="date" name="issue_date" class="form-control" required>
      </div>
      <button type="submit" name="add" class="btn btn-primary">Add</button>
    </form>
  </div>

  <!-- Update Tab -->
  <div class="tab-pane fade <?php echo ($activeTab == 'update') ? 'show active' : ''; ?>" id="update">
    <h3>Update Record</h3>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Record ID</label>
        <input type="number" name="id" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Subject Text</label>
        <input type="text" name="subject_text" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Upload New File (optional)</label>
        <input type="file" name="subject_file" class="form-control">
      </div>
      <div class="mb-3">
        <label>Issue Date</label>
        <input type="date" name="issue_date" class="form-control" required>
      </div>
      <button type="submit" name="update" class="btn btn-warning">Update</button>
    </form>
  </div>

  <!-- Delete Tab -->
  <div class="tab-pane fade <?php echo ($activeTab == 'delete') ? 'show active' : ''; ?>" id="delete">
    <h3>Delete Record</h3>
    <form method="POST">
      <div class="mb-3">
        <label>Record ID</label>
        <input type="number" name="id" class="form-control" required>
      </div>
      <button type="submit" name="delete" class="btn btn-danger">Delete</button>
    </form>
  </div>
</div>

</div>
<?php include('footer.php'); ?>
