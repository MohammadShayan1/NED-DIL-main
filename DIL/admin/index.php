<?php 
include 'header.php';

// Now, since $_SESSION['admin'] is an array, we assign it to $admin for ease of use.
$admin = $_SESSION['admin'];

// Check if $admin is set and is an array (this check is now optional since header.php
// already redirects if not logged in, but it's good practice)
if (!isset($admin) || !is_array($admin)) {
    header('Location: admin-login.php');
    exit(); 
}
?>
<!-- Main Content -->
<div class="main-content">
    <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($admin['username']); ?>!</h2>
    <p>Email: <?php echo htmlspecialchars($admin['email']); ?></p>
</div>
<?php include 'footer.php';?>
