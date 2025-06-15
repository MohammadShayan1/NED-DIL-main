<?php 
include 'header.php';
include_once './config.php';

// Define categories
$categories = ['Internship', 'Job Placement', 'Final Year Design Project', 'Visit'];

// Initialize storage array
$groupedForms = [];

// Fetch all forms with category
$sql = "SELECT form, file_path, category FROM Forms";
$result = $conn->query($sql);

// Function to trim file path
function trimUploadPath($path) {
    $prefix = '../assets/uploads/';
    if (strpos($path, $prefix) === 0) {
        return str_replace($prefix, './assets/uploads/forms/', $path);
    }
    return $path;
}

// Group results by category
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cat = $row['category'];
        $trimmedPath = trimUploadPath($row['file_path']);
        if (!isset($groupedForms[$cat])) {
            $groupedForms[$cat] = [];
        }
        $groupedForms[$cat][] = [
            'form' => $row['form'],
            'file_path' => $trimmedPath
        ];
    }
}
?>
<link rel="stylesheet" href="./assets/css/internship.css">
<main>
<section>
<div class="container mt-4">
    <div class="row text-center">
        <h1 class="mb-4" data-aos="fade-down" data-aos-duration="800">Downloads</h1>
    </div>
    <div class="table-responsive" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <?php foreach ($categories as $category): ?>
                        <th style="background-color: #073470; color: white;"><?= htmlspecialchars($category) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Calculate the maximum number of rows among categories
                $maxRows = max(array_map(fn($c) => count($groupedForms[$c] ?? []), $categories));

                for ($i = 0; $i < $maxRows; $i++) {
                    echo "<tr>";
                    foreach ($categories as $category) {
                        if (isset($groupedForms[$category][$i])) {
                            $form = $groupedForms[$category][$i];
                            echo "<td><a href='" . htmlspecialchars($form['file_path']) . "' target='_blank'>"
                                 . htmlspecialchars($form['form']) . "</a></td>";
                        } else {
                            echo "<td>—</td>"; // Empty cell if no file
                        }
                    }
                    echo "</tr>";
                }

                if ($maxRows === 0) {
                    echo "<tr><td colspan='" . count($categories) . "'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</section>
</main>
<?php include 'footer.php'; ?>
