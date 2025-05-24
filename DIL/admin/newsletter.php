<?php
include('header.php');
require_once('config.php');

$activeTab = 'view'; 
$message = '';

// Directory for uploads
$uploadDir = '../assets/uploads/newsletters/';

// Ensure upload dir exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Function to handle PDF upload
    function handlePDFUpload($fileInputName, $uploadDir) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$fileInputName]['tmp_name'];
            $fileName = basename($_FILES[$fileInputName]['name']);
            $fileSize = $_FILES[$fileInputName]['size'];
            $fileType = $_FILES[$fileInputName]['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Allowed extensions
            $allowedfileExtensions = ['pdf'];

            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Sanitize file name and create unique name
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadDir . $newFileName;

                if(move_uploaded_file($fileTmpPath, $dest_path)) {
                    return $newFileName; // Return the new file name to save in DB
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        return null; // No file uploaded
    }

    // ----- ADD RECORD -----
    if (isset($_POST['add'])) {
        $subject_text = $_POST['subject_text'];
        $subject_link = $_POST['subject_link'];
        $issue_date   = $_POST['issue_date'];

        $uploadedPDF = handlePDFUpload('pdf_file', $uploadDir);

        if ($uploadedPDF === false) {
            $message = "Error uploading PDF. Please upload a valid PDF file.";
            $activeTab = 'add';
        } else {
            $stmt = $conn->prepare("INSERT INTO Newsletters (Publication, Publication_link, issue_date, pdf_file) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $subject_text, $subject_link, $issue_date, $uploadedPDF);
            if ($stmt->execute()) {
                $message = "Record added successfully!";
                $activeTab = 'view';
            } else {
                $message = "Error: " . $stmt->error;
                $activeTab = 'add';
            }
            $stmt->close();
        }
    }

    // ----- UPDATE RECORD -----
    if (isset($_POST['update'])) {
        $id           = $_POST['id'];
        $subject_text = $_POST['subject_text'];
        $subject_link = $_POST['subject_link'];
        $issue_date   = $_POST['issue_date'];

        // Check if new PDF uploaded
        $uploadedPDF = handlePDFUpload('pdf_file', $uploadDir);

        if ($uploadedPDF === false) {
            $message = "Error uploading PDF. Please upload a valid PDF file.";
            $activeTab = 'update';
        } else {
            if ($uploadedPDF !== null) {
                // New PDF uploaded, update pdf_file column
                $stmt = $conn->prepare("UPDATE Newsletters SET Publication=?, Publication_link=?, issue_date=?, pdf_file=? WHERE id=?");
                $stmt->bind_param("ssssi", $subject_text, $subject_link, $issue_date, $uploadedPDF, $id);
            } else {
                // No new PDF, don't update pdf_file column
                $stmt = $conn->prepare("UPDATE Newsletters SET Publication=?, Publication_link=?, issue_date=? WHERE id=?");
                $stmt->bind_param("sssi", $subject_text, $subject_link, $issue_date, $id);
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
    }

    // ----- DELETE RECORD -----
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        // Optionally: fetch and delete the uploaded PDF file from server
        $pdfFetch = $conn->prepare("SELECT pdf_file FROM Newsletters WHERE id=?");
        $pdfFetch->bind_param("i", $id);
        $pdfFetch->execute();
        $pdfFetch->bind_result($pdfFile);
        $pdfFetch->fetch();
        $pdfFetch->close();

        if ($pdfFile && file_exists($uploadDir . $pdfFile)) {
            unlink($uploadDir . $pdfFile); // delete PDF file from server
        }

        $stmt = $conn->prepare("DELETE FROM Newsletters WHERE id=?");
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

// Fetch records
$result = $conn->query("SELECT * FROM Newsletters ORDER BY issue_date ASC");
?>

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
            <th>Publication</th>
            <th>Link</th>
            <th>Issue Date</th>
            <th>PDF</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['Publication']); ?></td>
                <td><a href="<?php echo htmlspecialchars($row['Publication_link']); ?>" target="_blank">Visit</a></td>
                <td><?php echo strtoupper(date("d-M-Y", strtotime($row["issue_date"]))); ?></td>
                <td>
                  <?php if ($row['pdf_file']): ?>
                    <a href="<?php echo $uploadDir . htmlspecialchars($row['pdf_file']); ?>" target="_blank">Download PDF</a>
                  <?php else: ?>
                    No PDF
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

    <!-- Add Data Tab -->
    <div class="tab-pane fade <?php echo ($activeTab=='add') ? 'show active' : ''; ?>" id="add" role="tabpanel" aria-labelledby="add-tab">
      <h3>Add New Record</h3>
      <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="subject_text" class="form-label">Publication Text</label>
          <input type="text" class="form-control" id="subject_text" name="subject_text" required>
        </div>
        <div class="mb-3">
          <label for="subject_link" class="form-label">Publication Link</label>
          <input type="url" class="form-control" id="subject_link" name="subject_link" required>
        </div>
        <div class="mb-3">
          <label for="issue_date" class="form-label">Issue Date</label>
          <input type="date" class="form-control" id="issue_date" name="issue_date" required>
        </div>
        <div class="mb-3">
          <label for="pdf_file" class="form-label">Upload PDF</label>
          <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept="application/pdf">
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
          <label for="subject_text" class="form-label">Publication Text</label>
          <input type="text" class="form-control" id="subject_text" name="subject_text" required>
        </div>
        <div class="mb-3">
          <label for="subject_link" class="form-label">Publication Link</label>
          <input type="url" class="form-control" id="subject_link" name="subject_link" required>
        </div>
        <div class="mb-3">
          <label for="issue_date" class="form-label">Issue Date</label>
          <input type="date" class="form-control" id="issue_date" name="issue_date" required>
        </div>
        <div class="mb-3">
          <label for="pdf_file" class="form-label">Upload New PDF (optional)</label>
          <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept="application/pdf">
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
  </div>
</div>

<?php include('footer.php'); ?>
