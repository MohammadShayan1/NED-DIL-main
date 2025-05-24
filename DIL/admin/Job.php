<?php
// Include header and database connection
include('header.php');
require_once('config.php');

$activeTab = isset($_POST['active_tab']) ? $_POST['active_tab'] : 'view';
$message = '';

// ----- HANDLE FORM SUBMISSIONS -----
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jobType = $_POST['job_type'] ?? '';
    $table = ($jobType === 'experienced') ? 'job_openings_experienced' : 'job_openings_fresh';
    
    // ADD JOB
    if (isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO $table (job_title, job_link, issue_date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_POST['job_title'], $_POST['job_link'], $_POST['issue_date']);
        $message = $stmt->execute() ? "Job added successfully!" : "Error: " . $stmt->error;
        $stmt->close();
        $activeTab = 'add';
    }
    
    // UPDATE JOB
    if (isset($_POST['update'])) {
        $stmt = $conn->prepare("UPDATE $table SET job_title=?, job_link=?, issue_date=? WHERE id=?");
        $stmt->bind_param("sssi", $_POST['job_title'], $_POST['job_link'], $_POST['issue_date'], $_POST['id']);
        $message = $stmt->execute() ? "Job updated successfully!" : "Error: " . $stmt->error;
        $stmt->close();
        $activeTab = 'update';
    }
    
    // DELETE JOB
    if (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM $table WHERE id=?");
        $stmt->bind_param("i", $_POST['id']);
        $message = $stmt->execute() ? "Job deleted successfully!" : "Error: " . $stmt->error;
        $stmt->close();
        $activeTab = 'view';
    }
}

// Fetch jobs from both tables
$jobs_fresh = $conn->query("SELECT * FROM job_openings_fresh ORDER BY issue_date DESC");
$jobs_experienced = $conn->query("SELECT * FROM job_openings_experienced ORDER BY issue_date DESC");
?>

<div class="main-content">
    <?php if ($message): ?>
        <div class="alert alert-info"> <?php echo htmlspecialchars($message); ?> </div>
    <?php endif; ?>
    
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <button class="nav-link <?php echo ($activeTab=='view') ? 'active' : ''; ?>" 
                    data-bs-toggle="tab" data-bs-target="#view">
                View Jobs
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link <?php echo ($activeTab=='update') ? 'active' : ''; ?>" 
                    data-bs-toggle="tab" data-bs-target="#update">
                Update Jobs
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link <?php echo ($activeTab=='add') ? 'active' : ''; ?>" 
                    data-bs-toggle="tab" data-bs-target="#add">
                Add Job
            </button>
        </li>
    </ul>
    
    <div class="tab-content mt-3">
        <!-- View Jobs -->
        <div class="tab-pane fade <?php echo ($activeTab=='view') ? 'show active' : ''; ?>" id="view">
            <h3>Job Openings</h3>
            
            <h4>Fresh Graduates</h4>
            <table class="table table-bordered table-striped">
                <tr>
                    <th>ID</th>
                    <th>Job Title</th>
                    <th>Job Link</th>
                    <th>Issue Date</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $jobs_fresh->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($row['job_link']); ?>" target="_blank">
                                Visit
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars(strtoupper(date("d-M-Y", strtotime($row["issue_date"])))); ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="job_type" value="fresh">
                                <input type="hidden" name="active_tab" value="view">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            
            <h4>Experienced Professionals</h4>
            <table class="table table-bordered table-striped">
                <tr>
                    <th>ID</th>
                    <th>Job Title</th>
                    <th>Job Link</th>
                    <th>Issue Date</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $jobs_experienced->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($row['job_link']); ?>" target="_blank">
                                Visit
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars(strtoupper(date("d-M-Y", strtotime($row["issue_date"])))); ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="job_type" value="experienced">
                                <input type="hidden" name="active_tab" value="view">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
        
        <!-- Update Job -->
        <div class="tab-pane fade <?php echo ($activeTab=='update') ? 'show active' : ''; ?>" id="update">
            <h3>Update Job</h3>
            <form method="POST">
                <input type="hidden" name="active_tab" value="update">
                <div class="mb-3">
                    <label class="form-label">Job ID</label>
                    <input type="number" class="form-control" name="id" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Title</label>
                    <input type="text" class="form-control" name="job_title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Link</label>
                    <input type="url" class="form-control" name="job_link" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Issue Date</label>
                    <input type="date" class="form-control" name="issue_date" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Type</label>
                    <select class="form-control" name="job_type">
                        <option value="fresh">Fresh Graduates</option>
                        <option value="experienced">Experienced Professionals</option>
                    </select>
                </div>
                <button type="submit" name="update" class="btn btn-warning">
                    Update Job
                </button>
            </form>
        </div>
        
        <!-- Add Job -->
        <div class="tab-pane fade <?php echo ($activeTab=='add') ? 'show active' : ''; ?>" id="add">
            <h3>Add Job</h3>
            <form method="POST">
                <input type="hidden" name="active_tab" value="add">
                <div class="mb-3">
                    <label class="form-label">Job Title</label>
                    <input type="text" class="form-control" name="job_title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Link</label>
                    <input type="url" class="form-control" name="job_link" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Issue Date</label>
                    <input type="date" class="form-control" name="issue_date" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Type</label>
                    <select class="form-control" name="job_type">
                        <option value="fresh">Fresh Graduates</option>
                        <option value="experienced">Experienced Professionals</option>
                    </select>
                </div>
                <button type="submit" name="add" class="btn btn-primary">
                    Add Job
                </button>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
