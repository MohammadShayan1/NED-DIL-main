<?php include 'header.php';
include_once './config.php';

// Query to fetch the latest 5 job openings for fresh graduates
$sql_fresh = "SELECT job_title, job_link, issue_date FROM job_openings_fresh ORDER BY issue_date DESC";
$result_fresh = $conn->query($sql_fresh);

// Query to fetch the latest 5 job openings for experienced professionals
$sql_experienced = "SELECT job_title, job_link, issue_date FROM job_openings_experienced ORDER BY issue_date DESC";
$result_experienced = $conn->query($sql_experienced);
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap');
    .new-label {
        font-family: 'Pixelify Sans', sans-serif;
        color:rgb(0, 0, 0);
        background-color: yellow;
        font-weight: bold;
        position: relative;
        animation: blink 1s infinite;
    }

    @keyframes blink {
        50% { opacity: 0; }
    }
    a {
        text-decoration: none;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let labels = document.querySelectorAll('.new-label');

        labels.forEach(label => {
            let randomDelay = Math.random() * 3; // Random delay between 0-2s
            label.style.animationDelay = randomDelay + "s";
        });
    });
</script>

<main>
     <section>
<div class="hero-img">
    <div class="container text-center">
        <h1 class="fw-bold mb-4 box-color-light p-3 rounded">PATH WAY TO OPPORTUNITIES</h1>
        <!-- Forms Section -->
        <div class="row mt-4 justify-content-center">
            <div class="col-md-3 mx-2">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">Industry Invitation Letter</p>
                    <a href="./assets/pdfs/Industry Invitation Letter.pdf" class="btn btn-dark fw-bold" target="_blank">DOWNLOAD</a>
                </div>
            </div>
            <div class="col-md-3 mx-2">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">Industry Invitation Letter</p>
                    <a href="./assets/pdfs/Industry Invitation Letter.pdf" class="btn btn-dark fw-bold" target="_blank">DOWNLOAD</a>
                </div>
            </div>
            <div class="col-md-3 mx-2">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">DIL Notice Board</p>
                    <a href="./assets/pdfs/Experienced Job Form (For Companies).pdf" class="btn btn-dark fw-bold" target="_blank">DOWNLOAD</a>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
    <section>
        <div class="container mt-4">
            <div class="row text-center">
                <h1 class="mb-4">JOB OPENINGS</h1>
            </div>
            
            <!-- Job Openings for Fresh Graduates -->
            <h2 class="mb-3">JOB OPENINGS FOR FRESH GRADUATES</h2>
            <table class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th style="background-color: #073470; color: white;">Job Title</th>
                        <th style="background-color: #073470; color: white;">Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result_fresh->num_rows > 0) {
                    $count = 0;
                    while ($row = $result_fresh->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><a href='" . htmlspecialchars($row["job_link"]) . "' target='_blank'>"
                            . htmlspecialchars($row["job_title"]) . "</a>";

                        // Add "NEW!" label for top 5 latest jobs
                        if ($count < 5) {
                            echo " <span class='new-label'>NEW!</span>";
                        }

                        echo "</td>";
                        echo "<td class='text-center'>" . strtoupper(date("d-M-Y", strtotime($row["issue_date"]))) . "</td>";
                        echo "</tr>";
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='2' class='text-center'>No records found</td></tr>";
                }
                ?>
                </tbody>
            </table>
            
            <!-- Job Openings for Experienced -->
            <h2 class="mt-5 mb-3">JOB OPENINGS (EXPERIENCED)</h2>
            <table class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th style="background-color: #073470; color: white;">Job Title</th>
                        <th style="background-color: #073470; color: white;">Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result_experienced->num_rows > 0) {
                    $count = 0;
                    while ($row = $result_experienced->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><a href='" . htmlspecialchars($row["job_link"]) . "' target='_blank'>"
                            . htmlspecialchars($row["job_title"]) . "</a>";

                        // Add "NEW!" label for top 5 latest jobs
                        if ($count < 5) {
                            echo " <span class='new-label'>NEW!</span>";
                        }

                        echo "</td>";
                        echo "<td class='text-center'>" . strtoupper(date("d-M-Y", strtotime($row["issue_date"]))) . "</td>";
                        echo "</tr>";
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='2' class='text-center'>No records found</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
