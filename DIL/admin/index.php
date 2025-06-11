<?php 
include 'header.php';
require_once 'config.php';

// Now, since $_SESSION['admin'] is an array, we assign it to $admin for ease of use.
$admin = $_SESSION['admin'];

// Check if $admin is set and is an array (this check is now optional since header.php
// already redirects if not logged in, but it's good practice)
if (!isset($admin) || !is_array($admin)) {
    header('Location: admin-login.php');
    exit(); 
}

// Get dashboard statistics with error handling
$stats = [];

// Function to safely execute queries and return count
function getTableCount($conn, $tableName, $condition = '') {
    try {
        $sql = "SELECT COUNT(*) as total FROM `$tableName`";
        if ($condition) {
            $sql .= " WHERE $condition";
        }
        $result = $conn->query($sql);
        return $result ? $result->fetch_assoc()['total'] : 0;
    } catch (mysqli_sql_exception $e) {
        error_log("Database error for table $tableName: " . $e->getMessage());
        return 0;
    }
}

// Function to safely get recent records with flexible column selection
function getRecentRecords($conn, $tableName, $columns, $limit = 5) {
    try {
        // First check if the table exists and what columns it has
        $table_check = $conn->query("SHOW TABLES LIKE '$tableName'");
        if ($table_check->num_rows == 0) {
            return false;
        }
        
        // Get actual column names
        $column_check = $conn->query("SHOW COLUMNS FROM `$tableName`");
        $available_columns = [];
        while ($col = $column_check->fetch_assoc()) {
            $available_columns[] = $col['Field'];
        }
        
        // Build safe column selection
        $requested_columns = explode(', ', $columns);
        $safe_columns = [];
        
        foreach ($requested_columns as $col) {
            $col = trim($col);
            if (in_array($col, $available_columns)) {
                $safe_columns[] = $col;
            } else {
                // Try common alternatives
                $alternatives = [
                    'title' => ['name', 'title', 'subject'],
                    'company' => ['company', 'organization', 'employer'],
                    'created_at' => ['created_at', 'date_created', 'created', 'date']
                ];
                
                if (isset($alternatives[$col])) {
                    foreach ($alternatives[$col] as $alt) {
                        if (in_array($alt, $available_columns)) {
                            $safe_columns[] = "$alt as $col";
                            break;
                        }
                    }
                }
            }
        }
        
        if (empty($safe_columns)) {
            $safe_columns = ['*'];
        }
        
        $safe_columns_str = implode(', ', $safe_columns);
        
        // Try to order by created_at or similar
        $order_by = 'id DESC'; // fallback
        if (in_array('created_at', $available_columns)) {
            $order_by = 'created_at DESC';
        } elseif (in_array('date_created', $available_columns)) {
            $order_by = 'date_created DESC';
        } elseif (in_array('created', $available_columns)) {
            $order_by = 'created DESC';
        }
        
        $sql = "SELECT $safe_columns_str FROM `$tableName` ORDER BY $order_by LIMIT $limit";
        return $conn->query($sql);
        
    } catch (mysqli_sql_exception $e) {
        error_log("Database error getting recent records from $tableName: " . $e->getMessage());
        return false;
    }
}

// Count total employees
$stats['employees'] = getTableCount($conn, 'employees');

// Count total internships
$stats['internships'] = getTableCount($conn, 'internship_programs');

// Count total jobs (combining both fresh and experienced)
$stats['jobs'] = getTableCount($conn, 'job_openings_fresh') + getTableCount($conn, 'job_openings_experienced');

// Count total newsletters
$stats['newsletters'] = getTableCount($conn, 'newsletters');

// Count active/pending items (if you have status columns)
$stats['active_internships'] = getTableCount($conn, 'internship_programs', "status = 'active'");
$stats['active_jobs'] = getTableCount($conn, 'job_openings_fresh', "status = 'active'") + getTableCount($conn, 'job_openings_experienced', "status = 'active'");

// Get recent activities (last 5 items)
$recent_internships = getRecentRecords($conn, 'internship_programs', 'title, company, created_at', 5);
$recent_jobs_fresh = getRecentRecords($conn, 'job_openings_fresh', 'title, company, created_at', 3);
$recent_jobs_experienced = getRecentRecords($conn, 'job_openings_experienced', 'title, company, created_at', 2);
$recent_newsletters = getRecentRecords($conn, 'newsletters', 'title, created_at', 3);

// Check database table status
$required_tables = ['employees', 'internship_programs', 'job_openings_fresh', 'job_openings_experienced', 'newsletters', 'admin_users'];
$missing_tables = [];

foreach ($required_tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows == 0) {
        $missing_tables[] = $table;
    }
}

$database_status = empty($missing_tables) ? 'complete' : 'incomplete';
?>
<!-- Main Content -->
<div class="main-content">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <?php if ($database_status === 'incomplete'): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Database Setup Required</h5>
                <p class="mb-2">Some database tables are missing: <strong><?php echo implode(', ', $missing_tables); ?></strong></p>
                <hr>
                <p class="mb-0">
                    <a href="init-database.php" class="btn btn-warning btn-sm">
                        <i class="fas fa-database me-1"></i>Initialize Database
                    </a>
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <div class="card border-0 bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h2 class="mb-2">Welcome back, <?php echo htmlspecialchars($admin['username']); ?>!</h2>
                            <p class="mb-0"><i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($admin['email']); ?></p>
                            <small class="opacity-75">Last login: <?php echo date('M d, Y H:i'); ?></small>
                        </div>
                        <div class="col-4 text-end">
                            <i class="fas fa-user-shield fa-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-muted mb-2">Total Employees</h6>
                            <h3 class="mb-0 text-primary"><?php echo $stats['employees']; ?></h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-users text-primary fa-lg"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="team-management.php" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>View All
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-muted mb-2">Total Internships</h6>
                            <h3 class="mb-0 text-success"><?php echo $stats['internships']; ?></h3>
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                <?php echo $stats['active_internships']; ?> Active
                            </small>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-briefcase text-success fa-lg"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="Internship.php" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-plus me-1"></i>Manage
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-muted mb-2">Total Jobs</h6>
                            <h3 class="mb-0 text-warning"><?php echo $stats['jobs']; ?></h3>
                            <small class="text-warning">
                                <i class="fas fa-briefcase me-1"></i>
                                <?php echo $stats['active_jobs']; ?> Active
                            </small>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-building text-warning fa-lg"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="Job.php" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit me-1"></i>Manage
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title text-muted mb-2">Newsletters</h6>
                            <h3 class="mb-0 text-info"><?php echo $stats['newsletters']; ?></h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-newspaper text-info fa-lg"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="newsletter.php" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-plus me-1"></i>Create
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Recent Activities -->
    <div class="row">
        <!-- Recent Internships -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-briefcase me-2 text-success"></i>Recent Internships
                    </h6>
                </div>
                <div class="card-body">
                    <?php if ($recent_internships && $recent_internships->num_rows > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php while ($internship = $recent_internships->fetch_assoc()): ?>
                                <div class="list-group-item border-0 px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold"><?php echo htmlspecialchars($internship['title'] ?? 'N/A'); ?></h6>
                                            <p class="mb-1 text-muted small"><?php echo htmlspecialchars($internship['company'] ?? 'N/A'); ?></p>
                                            <small class="text-muted"><?php echo isset($internship['created_at']) ? date('M d, Y', strtotime($internship['created_at'])) : 'N/A'; ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No recent internships</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Jobs -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-building me-2 text-warning"></i>Recent Jobs
                    </h6>
                </div>
                <div class="card-body">
                    <?php 
                    $has_jobs = false;
                    // Display fresh graduate jobs
                    if ($recent_jobs_fresh && $recent_jobs_fresh->num_rows > 0): 
                        $has_jobs = true;
                    ?>
                        <div class="list-group list-group-flush">
                            <small class="text-muted fw-bold mb-2">Fresh Graduate Positions</small>
                            <?php while ($job = $recent_jobs_fresh->fetch_assoc()): ?>
                                <div class="list-group-item border-0 px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold"><?php echo htmlspecialchars($job['title'] ?? 'N/A'); ?></h6>
                                            <p class="mb-1 text-muted small"><?php echo htmlspecialchars($job['company'] ?? 'N/A'); ?></p>
                                            <small class="text-muted"><?php echo isset($job['created_at']) ? date('M d, Y', strtotime($job['created_at'])) : 'N/A'; ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php 
                    // Display experienced jobs
                    if ($recent_jobs_experienced && $recent_jobs_experienced->num_rows > 0): 
                        $has_jobs = true;
                    ?>
                        <div class="list-group list-group-flush">
                            <?php if ($recent_jobs_fresh && $recent_jobs_fresh->num_rows > 0): ?>
                                <hr class="my-2">
                            <?php endif; ?>
                            <small class="text-muted fw-bold mb-2">Experienced Positions</small>
                            <?php while ($job = $recent_jobs_experienced->fetch_assoc()): ?>
                                <div class="list-group-item border-0 px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold"><?php echo htmlspecialchars($job['title'] ?? 'N/A'); ?></h6>
                                            <p class="mb-1 text-muted small"><?php echo htmlspecialchars($job['company'] ?? 'N/A'); ?></p>
                                            <small class="text-muted"><?php echo isset($job['created_at']) ? date('M d, Y', strtotime($job['created_at'])) : 'N/A'; ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!$has_jobs): ?>
                        <p class="text-muted text-center py-3">No recent jobs</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Newsletters & Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-newspaper me-2 text-info"></i>Recent Newsletters
                    </h6>
                </div>
                <div class="card-body">
                    <?php if ($recent_newsletters && $recent_newsletters->num_rows > 0): ?>
                        <div class="list-group list-group-flush mb-3">
                            <?php while ($newsletter = $recent_newsletters->fetch_assoc()): ?>
                                <div class="list-group-item border-0 px-0 py-2">
                                    <h6 class="mb-1 fw-semibold"><?php echo htmlspecialchars($newsletter['title'] ?? 'N/A'); ?></h6>
                                    <small class="text-muted"><?php echo isset($newsletter['created_at']) ? date('M d, Y', strtotime($newsletter['created_at'])) : 'N/A'; ?></small>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-2">No recent newsletters</p>
                    <?php endif; ?>
                    
                    <!-- Quick Actions -->
                    <div class="border-top pt-3">
                        <h6 class="fw-semibold mb-2">Quick Actions</h6>
                        <div class="d-grid gap-2">
                            <a href="employee_add.php" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-user-plus me-1"></i>Add Employee
                            </a>
                            <a href="Internship.php" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-plus me-1"></i>Add Internship
                            </a>
                            <a href="Job.php" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-briefcase me-1"></i>Add Job
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cogs me-2 text-secondary"></i>System Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Server Status:</strong>
                            <span class="badge bg-success ms-2">Online</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Database:</strong>
                            <?php if ($database_status === 'complete'): ?>
                                <span class="badge bg-success ms-2">Complete</span>
                            <?php else: ?>
                                <span class="badge bg-warning ms-2">Setup Required</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Last Backup:</strong>
                            <span class="text-muted">June 09, 2025</span>
                        </div>
                        <div class="col-md-3">
                            <strong>SEO Status:</strong>
                            <span class="badge bg-info ms-2">Optimized</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #073470, #0571ff) !important;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
}

@media (max-width: 768px) {
    .border-end {
        border-right: none !important;
        border-bottom: 1px solid #dee2e6 !important;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
}
</style>
<?php include 'footer.php';?>
