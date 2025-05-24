<?php include 'header.php';
include_once './config.php';

// Query to fetch data
$sql = "SELECT subject_text, subject_link, issue_date FROM industrial_collaboration ORDER BY issue_date DESC";
$result = $conn->query($sql);
?>
<main>
    <section>
        <div class="container mt-4">
            <div class="row text-center">
                <h1 class="mb-4">INDUSTRIAL COLLABORATION</h1>
            </div>
            <table class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th style="background-color: #073470; color: white;">Company</th>
                        <th style="background-color: #073470; color: white;">Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Loop through the results and display each row
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><a href='" . htmlspecialchars($row["subject_link"]) . "' target='_blank'>"
                            . htmlspecialchars($row["subject_text"]) . "</a></td>";
                        echo "<td class='text-center'>" . htmlspecialchars(strtoupper(date("d-M-Y", strtotime($row["issue_date"])))) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2' class='text-center'>No records found</td></tr>";
                }

                // Close the connection
                $conn->close();
                ?>
                </tbody>
            </table>
        </div>
    </section>
    <section>
        <div class="container mt-5">
            <div class="row text-center">
                <h3 class="mb-4">MEMORANDUM OF UNDERSTANDING SIGNED WITH INDUSTRIES / ORGANIZATIONS</h1>
            </div>
            <p class="text-justify">
                The purpose of MoU is to create a long-term framework of collaboration, cooperation, and development of a strong linkage between industry and NED on training, consultancy, research and development, or any other activity which is in the interest of both 'Parties'. The industrial collaboration and linkage would provide opportunities to students and faculty of NED for training, consultancy, R&D, and exposure to professional practices, while the industry would benefit from resolving their technical/managerial issues through local solutions. The collaboration would bring a positive impact on the environment and society.
            </p>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>