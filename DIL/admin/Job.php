<?php
// Include header and database connection
include('header.php');
require_once('config.php');

$message = '';
$activeTab = $_GET['active_tab'] ?? 'view';

function redirectWithMessage($msg, $tab) {
    header("Location: ".$_SERVER['PHP_SELF']."?message=" . urlencode($msg) . "&active_tab=" . urlencode($tab));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jobType = $_POST['job_type'] ?? '';
    $table = ($jobType === 'experienced') ? 'job_openings_experienced' : 'job_openings_fresh';

    // FILE UPLOAD HANDLER
    function handleFileUpload($oldFilePath = '') {
        if (isset($_FILES['job_file']) && $_FILES['job_file']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = '../assets/uploads/job_openings/';
            $fileName = basename($_FILES['job_file']['name']);
            $targetFile = $uploadDir . time() . '_' . $fileName;

            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            if ($fileType !== 'pdf') return [false, 'Only PDF files are allowed.'];

            if (move_uploaded_file($_FILES['job_file']['tmp_name'], $targetFile)) {
                if ($oldFilePath && file_exists($oldFilePath)) unlink($oldFilePath);
                return [true, $targetFile];
            } else {
                return [false, 'Error uploading the file.'];
            }
        }
        return [true, $oldFilePath]; // If no new file uploaded, keep old one
    }

    // ADD JOB
    if (isset($_POST['add'])) {
        list($uploadSuccess, $uploadResult) = handleFileUpload();
        if (!$uploadSuccess) redirectWithMessage($uploadResult, 'add');

        $stmt = $conn->prepare("INSERT INTO $table (job_title, issue_date, job_pdf) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_POST['job_title'], $_POST['issue_date'], $uploadResult);
        $msg = $stmt->execute() ? "Job added successfully!" : "Error: " . $stmt->error;
        $stmt->close();
        redirectWithMessage($msg, 'add');
    }

    // UPDATE JOB
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        // Get old file path
        $result = $conn->query("SELECT job_pdf FROM $table WHERE id=$id");
        $oldPath = $result->fetch_assoc()['job_pdf'] ?? '';

        list($uploadSuccess, $uploadResult) = handleFileUpload($oldPath);
        if (!$uploadSuccess) redirectWithMessage($uploadResult, 'update');

        $stmt = $conn->prepare("UPDATE $table SET job_title=?, issue_date=?, job_pdf=? WHERE id=?");
        $stmt->bind_param("sssi", $_POST['job_title'], $_POST['issue_date'], $uploadResult, $id);
        $msg = $stmt->execute() ? "Job updated successfully!" : "Error: " . $stmt->error;
        $stmt->close();
        redirectWithMessage($msg, 'update');
    }

    // DELETE JOB
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $result = $conn->query("SELECT job_pdf FROM $table WHERE id=$id");
        $file = $result->fetch_assoc()['job_pdf'] ?? '';
        if ($file && file_exists($file)) unlink($file);

        $stmt = $conn->prepare("DELETE FROM $table WHERE id=?");
        $stmt->bind_param("i", $id);
        $msg = $stmt->execute() ? "Job deleted successfully!" : "Error: " . $stmt->error;
        $stmt->close();
        redirectWithMessage($msg, 'view');
    }
}

// Fetch jobs
$jobs_fresh = $conn->query("SELECT * FROM job_openings_fresh ORDER BY issue_date DESC");
$jobs_experienced = $conn->query("SELECT * FROM job_openings_experienced ORDER BY issue_date DESC");
?>

<div class="main-content">
    <h3>Job Openings</h3>
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link <?php echo ($activeTab=='view') ? 'active' : ''; ?>" data-bs-toggle="tab" href="#view">View Jobs</a></li>
        <li class="nav-item"><a class="nav-link <?php echo ($activeTab=='add') ? 'active' : ''; ?>" data-bs-toggle="tab" href="#add">Add Job</a></li>
        <li class="nav-item"><a class="nav-link <?php echo ($activeTab=='update') ? 'active' : ''; ?>" data-bs-toggle="tab" href="#update">Update Job</a></li>
    </ul>

    <div class="tab-content mt-3">
        <!-- View Jobs -->
        <div class="tab-pane fade <?php echo ($activeTab=='view') ? 'show active' : ''; ?>" id="view">
            <h4>Fresh Graduates</h4>
            <table class="table table-bordered">
                <tr><th>ID</th><th>Title</th><th>Date</th><th>File</th><th>Actions</th></tr>
                <?php while ($row = $jobs_fresh->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['job_title']) ?></td>
                        <td><?= strtoupper(date("d-M-Y", strtotime($row['issue_date']))) ?></td>
                        <td><a href="<?= htmlspecialchars($row['job_pdf']) ?>" target="_blank">View</a></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="job_type" value="fresh">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <h4>Experienced Professionals</h4>
            <table class="table table-bordered">
                <tr><th>ID</th><th>Title</th><th>Date</th><th>File</th><th>Actions</th></tr>
                <?php while ($row = $jobs_experienced->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['job_title']) ?></td>
                        <td><?= strtoupper(date("d-M-Y", strtotime($row['issue_date']))) ?></td>
                        <td><a href="<?= htmlspecialchars($row['job_pdf']) ?>" target="_blank">View</a></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="job_type" value="experienced">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Update Job -->
        <div class="tab-pane fade <?php echo ($activeTab=='update') ? 'show active' : ''; ?>" id="update">
            <h3>Update Job</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="active_tab" value="update">
                <div class="mb-3"><label>Job ID</label><input type="number" name="id" class="form-control" required></div>
                <div class="mb-3"><label>Job Title</label><input type="text" name="job_title" class="form-control" required></div>
                <div class="mb-3"><label>Issue Date</label><input type="date" name="issue_date" class="form-control" required></div>
                <div class="mb-3"><label>Upload New File (PDF)</label><input type="file" name="job_file" class="form-control"></div>
                <div class="mb-3">
                    <label>Job Type</label>
                    <select name="job_type" class="form-control">
                        <option value="fresh">Fresh Graduates</option>
                        <option value="experienced">Experienced Professionals</option>
                    </select>
                </div>
                <button name="update" class="btn btn-warning">Update Job</button>
            </form>
        </div>

        <!-- Add Job -->
        <div class="tab-pane fade <?php echo ($activeTab=='add') ? 'show active' : ''; ?>" id="add">
            <h3>Add Job</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="active_tab" value="add">
                <div class="mb-3"><label>Job Title</label><input type="text" name="job_title" class="form-control" required></div>
                <div class="mb-3"><label>Issue Date</label><input type="date" name="issue_date" class="form-control" required></div>
                <div class="mb-3"><label>Upload Job File (PDF)</label><input type="file" name="job_file" class="form-control" required></div>
                <div class="mb-3">
                    <label>Job Type</label>
                    <select name="job_type" class="form-control">
                        <option value="fresh">Fresh Graduates</option>
                        <option value="experienced">Experienced Professionals</option>
                    </select>
                </div>
                <button name="add" class="btn btn-primary">Add Job</button>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
