<?php
session_start();

// Set the session timeout duration (10 minutes)
$timeout_duration = 600;

// Check if the session is set
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

// Check if the session has been inactive longer than the timeout duration
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: admin-login.php");
    exit();
}
$activePage = basename($_SERVER['PHP_SELF'], ".php");


// Update last activity time
$_SESSION['last_activity'] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NED DIL | Admin Dashboard</title>
  <link rel="icon" type="image/png" href="../assets/favicon/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="../assets/favicon/favicon.svg" />
  <link rel="shortcut icon" href="../assets/favicon/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png" />
  <meta name="apple-mobile-web-app-title" content="Directorate Of Industrial Liaison" />
  <link rel="manifest" href="../assets/favicon/site.webmanifest" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }
    .sidebar {
      background: #343a40;
      color: white;
      padding: 20px;
      position: fixed;
      height: 100%;
      width: 250px;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 5px;
    }
    .sidebar a:hover {
      background: #495057;
    }
    .main-content {
      margin-left: 250px;
      padding: 20px;
      width: calc(100% - 250px);
      overflow-y: auto;
    }
    @media (max-width: 768px) {
      .sidebar { width: 200px; }
      .main-content { margin-left: 200px; width: calc(100% - 200px); }
    }
    @media (max-width: 576px) {
      .sidebar { width: 150px; }
      .main-content { margin-left: 150px; width: calc(100% - 150px); }
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
      <h4 class="text-center">Admin Panel</h4>
      <a href="index.php" class="<?= ($activePage == 'index') ? 'active' : '' ?>">🏠 Dashboard</a>
    <a href="team-management.php" class="<?= ($activePage == 'team-management') ? 'active' : '' ?>">👥 Team Management</a>
    <a href="form.php" disabled>📝 Manage Forms</a>
    <a href="Internship.php" class="<?= ($activePage == 'Internship') ? 'active' : '' ?>">🎓 Internship Table</a>
    <a href="Job.php" class="<?= ($activePage == 'Job') ? 'active' : '' ?>">💼 Job Table</a>
    <a href="Industrialcollaboration.php" class="<?= ($activePage == 'Industrialcollaboration') ? 'active' : '' ?>">🏭 Industrial Collaboration</a>
    <a href="newsletter.php" class="<?= ($activePage == 'newsletter') ? 'active' : '' ?>"> 📰 Newsletters</a>
    <a href="logout.php" class="bg-danger text-white">🚪 Logout</a>
  </div>
