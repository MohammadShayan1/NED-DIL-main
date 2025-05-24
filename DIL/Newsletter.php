<?php include 'header.php';
include_once './config.php';

// Query to fetch data
$sql = "SELECT Publication, pdf_file, issue_date
        FROM Newsletters
        ORDER BY issue_date ASC";
$result = $conn->query($sql);
?>
<link rel="stylesheet" href="./assets/css/internship.css">
<main>
<section>
<div class="container mt-4">
    <div class="row text-center">
    <h1 class="mb-4">Newsletters</h1>
    </div>
    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th style="background-color: #073470; color: white;">Publications</th>
                <th style="background-color: #073470; color: white;">Issue Date</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // 3. Loop through the results and display each row
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><a href='./assets/uploads/" . $row["pdf_file"] . "' target='_blank'>"
                     . $row["Publication"] . "</a></td>";
                echo "<td class='text-center'>" . strtoupper(date("d-M-Y", strtotime($row["issue_date"]))) . "</td>";
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