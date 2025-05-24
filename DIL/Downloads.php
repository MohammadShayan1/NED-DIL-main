<?php include 'header.php';
include_once './config.php';

// Query to fetch data
$sql = "SELECT form, file_path
        FROM Forms";
$result = $conn->query($sql);
function trimUploadPath($path) {
    $prefix = '../assets/uploads/';
    if (strpos($path, $prefix) === 0) {
        return str_replace($prefix, './assets/uploads/forms/', $path);
    }
    return $path;
}
?>
<link rel="stylesheet" href="./assets/css/internship.css">
<main>
<section>
<div class="container mt-4">
    <div class="row text-center">
    <h1 class="mb-4">Downloads</h1>
    </div>
    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th style="background-color: #073470; color: white;">Downloads</th>
            </tr>
        </thead>
        <tbody class='text-center'>
        <?php
        // 3. Loop through the results and display each row
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $trimmedPath = trimUploadPath($row['file_path']);
                echo "<tr>";
                echo "<td><a href='" . htmlspecialchars($trimmedPath) . "' target='_blank'>"
                     . $row["form"] . "</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No records found</td></tr>";
        }

        // 4. Close the connection
        $conn->close();
        ?>
        </tbody>
    </table>
</div>
</section>

</main>
<?php include 'footer.php';?>