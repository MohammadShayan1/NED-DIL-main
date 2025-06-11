<?php
include('header.php');
require_once('config.php');

$activeTab = $_GET['tab'] ?? 'view';
$message = $_GET['msg'] ?? '';

// File upload directory
$uploadDir = '../assets/uploads/internships/';

// Handle file upload
function handlePdfUpload($fileInputName, &$errorMessage) {
    global $uploadDir;

    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == UPLOAD_ERR_OK) {
        $fileTmp = $_FILES[$fileInputName]['tmp_name'];
        $fileName = basename($_FILES[$fileInputName]['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileExt != 'pdf') {
            $errorMessage = "Only PDF files are allowed.";
            return false;
        }

        $newName = uniqid() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $fileName);
        $destination = $uploadDir . $newName;

        if (move_uploaded_file($fileTmp, $destination)) {
            return $newName;
        } else {
            $errorMessage = "Failed to move uploaded file.";
            return false;
        }
    }

    return null; // No file uploaded
}

// ----- POST HANDLING -----
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ----- ADD RECORD -----
    if (isset($_POST['add'])) {
        $subject_text = $_POST['subject_text'];
        $issue_date   = $_POST['issue_date'];

        $pdf_file = handlePdfUpload('pdf_file', $message);

        if ($message == '') {
            $stmt = $conn->prepare("INSERT INTO internship_programs (subject_text, issue_date, pdf_file) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $subject_text, $issue_date, $pdf_file);
            if ($stmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?msg=Record added successfully!&tab=view");
                exit();
            } else {
                $message = "Error: " . $stmt->error;
                $activeTab = 'add';
            }
            $stmt->close();
        } else {
            $activeTab = 'add';
        }
    }

    // ----- UPDATE RECORD -----
    if (isset($_POST['update'])) {
        $id           = $_POST['id'];
        $subject_text = $_POST['subject_text'];
        $issue_date   = $_POST['issue_date'];

        $pdf_file = handlePdfUpload('pdf_file', $message);
        if ($message == '') {
            if ($pdf_file) {
                $stmt = $conn->prepare("UPDATE internship_programs SET subject_text=?, issue_date=?, pdf_file=? WHERE id=?");
                $stmt->bind_param("sssi", $subject_text, $issue_date, $pdf_file, $id);
            } else {
                $stmt = $conn->prepare("UPDATE internship_programs SET subject_text=?, issue_date=? WHERE id=?");
                $stmt->bind_param("ssi", $subject_text, $issue_date, $id);
            }

            if ($stmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?msg=Record updated successfully!&tab=view");
                exit();
            } else {
                $message = "Error: " . $stmt->error;
                $activeTab = 'update';
            }
            $stmt->close();
        } else {
            $activeTab = 'update';
        }
    }

    // ----- DELETE RECORD -----
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM internship_programs WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?msg=Record deleted successfully!&tab=view");
            exit();
        } else {
            $message = "Error: " . $stmt->error;
            $activeTab = 'delete';
        }
        $stmt->close();
    }
}

// Fetch records
$result = $conn->query("SELECT * FROM internship_programs ORDER BY issue_date ASC");
?>

<div class="main-content">
  <h2>Internships</h2>
<?php if ($message != ''): ?>
  <div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>

<ul class="nav nav-tabs" id="crudTab" role="tablist">
  <li class="nav-item"><button class="nav-link <?php echo ($activeTab=='view') ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#view">View Data</button></li>
  <li class="nav-item"><button class="nav-link <?php echo ($activeTab=='add') ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#add">Add Data</button></li>
  <li class="nav-item"><button class="nav-link <?php echo ($activeTab=='update') ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#update">Update Data</button></li>
  <li class="nav-item"><button class="nav-link <?php echo ($activeTab=='delete') ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#delete">Delete Data</button></li>
</ul>

<div class="tab-content mt-3">
  <!-- View -->
  <div class="tab-pane fade <?php echo ($activeTab=='view') ? 'show active' : ''; ?>" id="view">
    <h3>View Records</h3>
    <table class="table table-bordered">
      <thead><tr><th>ID</th><th>Subject</th><th>Issue Date</th><th>PDF</th></tr></thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['subject_text']; ?></td>
              <td><?php echo strtoupper(date("d-M-Y", strtotime($row["issue_date"]))); ?></td>
              <td>
                <?php if ($row['pdf_file']): ?>
                  <a href="../assets/uploads/internships/<?php echo $row['pdf_file']; ?>" target="_blank">View PDF</a>
                <?php else: ?>
                  N/A
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5">No records found</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Add -->
  <div class="tab-pane fade <?php echo ($activeTab=='add') ? 'show active' : ''; ?>" id="add">
    <h3>Add New Record</h3>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Subject Text</label>
        <input type="text" class="form-control" name="subject_text" required>
      </div>
      <div class="mb-3">
        <label>Issue Date</label>
        <input type="date" class="form-control" name="issue_date" required>
      </div>
      <div class="mb-3">
        <label>PDF File</label>
        <input type="file" class="form-control" name="pdf_file" accept="application/pdf">
      </div>
      <button type="submit" name="add" class="btn btn-primary">Add Record</button>
    </form>
  </div>

  <!-- Update -->
  <div class="tab-pane fade <?php echo ($activeTab=='update') ? 'show active' : ''; ?>" id="update">
    <h3>Update Record</h3>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Record ID</label>
        <input type="number" class="form-control" name="id" required>
      </div>
      <div class="mb-3">
        <label>Subject Text</label>
        <input type="text" class="form-control" name="subject_text" required>
      </div>
      <div class="mb-3">
        <label>Issue Date</label>
        <input type="date" class="form-control" name="issue_date" required>
      </div>
      <div class="mb-3">
        <label>PDF File (Optional)</label>
        <input type="file" class="form-control" name="pdf_file" accept="application/pdf">
      </div>
      <button type="submit" name="update" class="btn btn-warning">Update Record</button>
    </form>
  </div>

  <!-- Delete -->
  <div class="tab-pane fade <?php echo ($activeTab=='delete') ? 'show active' : ''; ?>" id="delete">
    <h3>Delete Record</h3>
    <form method="POST">
      <div class="mb-3">
        <label>Record ID</label>
        <input type="number" class="form-control" name="id" required>
      </div>
      <button type="submit" name="delete" class="btn btn-danger">Delete Record</button>
    </form>
  </div>
</div>
</div>

<?php include('footer.php'); ?>
