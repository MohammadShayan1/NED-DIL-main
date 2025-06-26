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

    // Debug information (remove after fixing)
    if (isset($_POST['add'])) {
        error_log("DEBUG - POST data received:");
        error_log("Subject: " . ($_POST['subject_text'] ?? 'Not set'));
        error_log("Date: " . ($_POST['issue_date'] ?? 'Not set'));
        
        if (isset($_FILES['pdf_file'])) {
            error_log("DEBUG - File upload info:");
            error_log("File name: " . $_FILES['pdf_file']['name']);
            error_log("File size: " . $_FILES['pdf_file']['size']);
            error_log("File type: " . $_FILES['pdf_file']['type']);
            error_log("File error: " . $_FILES['pdf_file']['error']);
            error_log("File tmp_name: " . $_FILES['pdf_file']['tmp_name']);
        } else {
            error_log("DEBUG - No pdf_file in _FILES array");
            error_log("DEBUG - Available _FILES keys: " . implode(', ', array_keys($_FILES)));
        }
        
        error_log("Upload dir exists: " . (is_dir($uploadDir) ? 'Yes' : 'No'));
        error_log("Upload dir writable: " . (is_writable($uploadDir) ? 'Yes' : 'No'));
    }

    // Function to handle PDF upload
    function handlePDFUpload($fileInputName, $uploadDir) {
        error_log("DEBUG - handlePDFUpload called with: " . $fileInputName);
        
        // Check if file was uploaded
        if (!isset($_FILES[$fileInputName])) {
            error_log("DEBUG - File input not found: " . $fileInputName);
            return null;
        }
        
        $file = $_FILES[$fileInputName];
        error_log("DEBUG - File details: name=" . $file['name'] . ", size=" . $file['size'] . ", error=" . $file['error']);
        
        // Check if a file was actually selected
        if (empty($file['name']) || $file['size'] == 0) {
            error_log("DEBUG - No file selected or file is empty");
            return null; // No file uploaded
        }
        
        $uploadError = $file['error'];
        
        // Check for upload errors
        if ($uploadError !== UPLOAD_ERR_OK) {
            switch($uploadError) {
                case UPLOAD_ERR_INI_SIZE:
                    error_log("File exceeds upload_max_filesize (error code: " . $uploadError . ")");
                    return "FILE_TOO_LARGE_INI";
                case UPLOAD_ERR_FORM_SIZE:
                    error_log("File exceeds MAX_FILE_SIZE (error code: " . $uploadError . ")");
                    return "FILE_TOO_LARGE_FORM";
                case UPLOAD_ERR_PARTIAL:
                    error_log("File upload incomplete (error code: " . $uploadError . ")");
                    return "UPLOAD_INCOMPLETE";
                case UPLOAD_ERR_NO_FILE:
                    error_log("No file uploaded (error code: " . $uploadError . ")");
                    return null; // No file uploaded
                default:
                    error_log("File upload error: " . $uploadError);
                    return "UPLOAD_ERROR";
            }
        }
        
        $fileTmpPath = $file['tmp_name'];
        $fileName = basename($file['name']);
        $fileSize = $file['size'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        error_log("DEBUG - Processing file: " . $fileName . " (extension: " . $fileExtension . ")");

        // Validate file extension
        $allowedfileExtensions = ['pdf'];
        if (!in_array($fileExtension, $allowedfileExtensions)) {
            error_log("Invalid file extension: " . $fileExtension . " for file: " . $fileName);
            return false;
        }

        // Validate file size (limit to 10MB)
        $maxFileSize = 10 * 1024 * 1024; // 10MB
        if ($fileSize > $maxFileSize) {
            error_log("File too large: " . $fileSize . " bytes");
            return false;
        }

        // Validate MIME type (make it more flexible)
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $fileTmpPath);
            finfo_close($finfo);
            
            $allowedMimeTypes = ['application/pdf', 'application/x-pdf'];
            if (!in_array($mimeType, $allowedMimeTypes)) {
                error_log("Invalid MIME type: " . $mimeType . " for file: " . $fileName);
                // Don't return false here, just log it - some valid PDFs might have different MIME types
            }
        }

        // Create unique filename
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $dest_path = $uploadDir . $newFileName;

        error_log("DEBUG - Attempting to move file from " . $fileTmpPath . " to " . $dest_path);

        // Move uploaded file
        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            error_log("File uploaded successfully: " . $dest_path);
            return $newFileName;
        } else {
            error_log("Failed to move uploaded file from " . $fileTmpPath . " to " . $dest_path);
            return false;
        }
    }

    // ----- ADD RECORD -----
    if (isset($_POST['add'])) {
        $subject_text = $_POST['subject_text'];
        $issue_date   = $_POST['issue_date'];

        $uploadedPDF = handlePDFUpload('pdf_file', $uploadDir);

        // Temporary debug display (remove after fixing)
        $debugInfo = "<br><strong>Debug Info:</strong><br>";
        $debugInfo .= "Form submitted: Yes<br>";
        $debugInfo .= "Subject: " . htmlspecialchars($subject_text) . "<br>";
        $debugInfo .= "Date: " . htmlspecialchars($issue_date) . "<br>";
        if (isset($_FILES['pdf_file'])) {
            $debugInfo .= "File name: " . htmlspecialchars($_FILES['pdf_file']['name']) . "<br>";
            $debugInfo .= "File size: " . $_FILES['pdf_file']['size'] . " bytes<br>";
            $debugInfo .= "File error: " . $_FILES['pdf_file']['error'] . "<br>";
        } else {
            $debugInfo .= "No file data found in _FILES<br>";
        }
        $debugInfo .= "Upload result: " . ($uploadedPDF === null ? 'null (no file)' : ($uploadedPDF === false ? 'false (error)' : 'success: ' . $uploadedPDF)) . "<br>";
        $debugInfo .= "PHP upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
        $debugInfo .= "PHP post_max_size: " . ini_get('post_max_size') . "<br>";
        $debugInfo .= "PHP max_file_uploads: " . ini_get('max_file_uploads') . "<br>";

        // Get current PHP upload limits for better error messages
        $upload_max_filesize = ini_get('upload_max_filesize');
        $post_max_size = ini_get('post_max_size');
        
        if ($uploadedPDF === "FILE_TOO_LARGE_INI") {
            $message = "Error: The PDF file is too large. Maximum allowed size is {$upload_max_filesize}. Please reduce the file size or ask your administrator to increase the upload limit." . $debugInfo;
            $activeTab = 'add';
        } elseif ($uploadedPDF === "FILE_TOO_LARGE_FORM") {
            $message = "Error: The PDF file exceeds the form's maximum file size limit." . $debugInfo;
            $activeTab = 'add';
        } elseif ($uploadedPDF === "UPLOAD_INCOMPLETE") {
            $message = "Error: The file upload was incomplete. Please try again." . $debugInfo;
            $activeTab = 'add';
        } elseif ($uploadedPDF === "UPLOAD_ERROR") {
            $message = "Error: There was a problem uploading the file. Please try again." . $debugInfo;
            $activeTab = 'add';
        } elseif ($uploadedPDF === false) {
            $message = "Error uploading PDF. Please ensure the file is a valid PDF (max 10MB)." . $debugInfo;
            $activeTab = 'add';
        } elseif ($uploadedPDF === null) {
            $message = "Please select a PDF file to upload." . $debugInfo;
            $activeTab = 'add';
        } else {
            // Store the relative path to the PDF file
            $pdf_path = '../assets/uploads/newsletters/' . $uploadedPDF;
            
            $stmt = $conn->prepare("INSERT INTO Newsletters (Publication, issue_date, pdf_file) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $subject_text, $issue_date, $pdf_path);
            if ($stmt->execute()) {
                $message = "Newsletter added successfully!";
                $activeTab = 'view';
            } else {
                $message = "Error saving to database: " . $stmt->error;
                $activeTab = 'add';
                // Delete uploaded file if database insert failed
                if (file_exists($uploadDir . $uploadedPDF)) {
                    unlink($uploadDir . $uploadedPDF);
                }
            }
            $stmt->close();
        }
    }

    // ----- UPDATE RECORD -----
    if (isset($_POST['update'])) {
        $id           = $_POST['id'];
        $subject_text = $_POST['subject_text'];
        $issue_date   = $_POST['issue_date'];

        // Check if new PDF uploaded
        $uploadedPDF = handlePDFUpload('pdf_file', $uploadDir);

        if ($uploadedPDF === false) {
            $message = "Error uploading PDF. Please upload a valid PDF file.";
            $activeTab = 'update';
        } else {
            if ($uploadedPDF !== null) {
                // New PDF uploaded, update pdf_file column
                $pdf_path = '../assets/uploads/newsletters/' . $uploadedPDF;
                $stmt = $conn->prepare("UPDATE Newsletters SET Publication=?, issue_date=?, pdf_file=? WHERE id=?");
                $stmt->bind_param("sssi", $subject_text, $issue_date, $pdf_path, $id);
            } else {
                // No new PDF, don't update pdf_file column
                $stmt = $conn->prepare("UPDATE Newsletters SET Publication=?, issue_date=? WHERE id=?");
                $stmt->bind_param("ssi", $subject_text, $issue_date, $id);
            }

            if ($stmt->execute()) {
                $message = "Record updated successfully!";
                $activeTab = 'view';
            } else {
                $message = "Error updating database: " . $stmt->error;
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
  <h2>Newsletters</h2>
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
          <label for="issue_date" class="form-label">Issue Date</label>
          <input type="date" class="form-control" id="issue_date" name="issue_date" required>
        </div>
        <div class="mb-3">
          <label for="pdf_file" class="form-label">Upload PDF</label>
          <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept="application/pdf" required>
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
