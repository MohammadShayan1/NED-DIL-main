<?php
include('header.php');
require_once('config.php');

$activeTab = 'view';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $subject_text = $_POST['subject_text'];
        $category = $_POST['category'];

        $upload_dir = "../assets/uploads/forms/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        if (isset($_FILES['subject_file']) && $_FILES['subject_file']['error'] === 0) {
            $file_name = basename($_FILES["subject_file"]["name"]);
            $target_file = $upload_dir . time() . "_" . $file_name;

            if (move_uploaded_file($_FILES["subject_file"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO Forms (form, category, file_path) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $subject_text, $category, $target_file);
                $stmt->execute();
                $message = "Record added successfully!";
            } else {
                $message = "File upload failed.";
            }
        } else {
            $message = "Please upload a valid file.";
        }
        $activeTab = 'add';
    }

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $subject_text = $_POST['subject_text'];
        $category = $_POST['category'];

        $upload_dir = "../assets/uploads/forms/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        if (isset($_FILES['subject_file']) && $_FILES['subject_file']['error'] === 0) {
            $file_name = basename($_FILES["subject_file"]["name"]);
            $target_file = $upload_dir . time() . "_" . $file_name;

            if (move_uploaded_file($_FILES["subject_file"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("UPDATE Forms SET form = ?, category = ?, file_path = ? WHERE id = ?");
                $stmt->bind_param("sssi", $subject_text, $category, $target_file, $id);
                $stmt->execute();
                $message = "Record updated successfully!";
            } else {
                $message = "File upload failed.";
            }
        } else {
            $message = "Please upload a valid file.";
        }
        $activeTab = 'update';
    }

if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Get file path
    $stmt = $conn->prepare("SELECT file_path FROM Forms WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($file_path);
    $stmt->fetch();
    $stmt->close();

    // Delete file
    if ($file_path && file_exists($file_path)) {
        unlink($file_path);
    }

    // Delete record
    $stmt = $conn->prepare("DELETE FROM Forms WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $message = "Record and file deleted successfully!";
    $activeTab = 'delete';
}

}

$result = $conn->query("SELECT * FROM Forms");
?>

<div class="main-content">
  <h2>Downloads</h2>
<?php if ($message != ''): ?>
<div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>

<ul class="nav nav-tabs" id="crudTab" role="tablist">
  <li class="nav-item"><button class="nav-link <?php echo ($activeTab=='view') ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#view">View</button></li>
  <li class="nav-item"><button class="nav-link <?php echo ($activeTab=='add') ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#add">Add</button></li>
  <li class="nav-item"><button class="nav-link <?php echo ($activeTab=='update') ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#update">Update</button></li>
  <li class="nav-item"><button class="nav-link <?php echo ($activeTab=='delete') ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#delete">Delete</button></li>
</ul>

<div class="tab-content mt-3">
  <div class="tab-pane fade <?php echo ($activeTab=='view') ? 'show active' : ''; ?>" id="view">
    <h3>View Records</h3>
    <table class="table table-bordered">
      <thead><tr><th>ID</th><th>Subject</th><th>Category</th><th>File</th></tr></thead>
      <tbody>
      <?php if ($result->num_rows > 0): while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo $row['form']; ?></td>
          <td><?php echo $row['category']; ?></td>
          <td><a href="<?php echo $row['file_path']; ?>" download>Download</a></td>
        </tr>
      <?php endwhile; else: ?>
        <tr><td colspan="4">No records found</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="tab-pane fade <?php echo ($activeTab=='add') ? 'show active' : ''; ?>" id="add">
    <h3>Add Record</h3>
    <form method="POST" enctype="multipart/form-data">
      <input class="form-control mb-2" type="text" name="subject_text" placeholder="Subject Text" required>
      <select class="form-control mb-2" name="category" required>
        <option value="">Select Category</option>
        <option>Internship</option>
        <option>Job Placement</option>
        <option>Final Year Design Project</option>
        <option>Visit</option>
      </select>
      <input class="form-control mb-2" type="file" name="subject_file" required>
      <button class="btn btn-primary" name="add">Add</button>
    </form>
  </div>

  <div class="tab-pane fade <?php echo ($activeTab=='update') ? 'show active' : ''; ?>" id="update">
    <h3>Update Record</h3>
    <form method="POST" enctype="multipart/form-data">
      <input class="form-control mb-2" type="number" name="id" placeholder="Record ID" required>
      <input class="form-control mb-2" type="text" name="subject_text" placeholder="Subject Text" required>
      <select class="form-control mb-2" name="category" required>
        <option value="">Select Category</option>
        <option>Internship</option>
        <option>Job Placement</option>
        <option>Final Year Design Project</option>
        <option>Visit</option>
      </select>
      <input class="form-control mb-2" type="file" name="subject_file" required>
      <button class="btn btn-warning" name="update">Update</button>
    </form>
  </div>

  <div class="tab-pane fade <?php echo ($activeTab=='delete') ? 'show active' : ''; ?>" id="delete">
    <h3>Delete Record</h3>
    <form method="POST">
      <input class="form-control mb-2" type="number" name="id" placeholder="Record ID" required>
      <button class="btn btn-danger" name="delete">Delete</button>
    </form>
  </div>
</div>
</div>

<?php include('footer.php'); ?>
