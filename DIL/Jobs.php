<?php
include 'header.php';
include_once './config.php';

// Fetch jobs with PDF links
$sql_fresh = "SELECT id, job_title, issue_date, job_pdf FROM job_openings_fresh ORDER BY issue_date DESC";
$result_fresh = $conn->query($sql_fresh);

$sql_experienced = "SELECT id, job_title, issue_date, job_pdf FROM job_openings_experienced ORDER BY issue_date DESC";
$result_experienced = $conn->query($sql_experienced);
?>
<link rel="stylesheet" href="./assets/css/jobs.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap');
    .new-label {
        font-family: 'Pixelify Sans', sans-serif;
        color: rgb(0, 0, 0);
        background-color: yellow;
        font-weight: bold;
        position: relative;
        animation: blink 1s infinite;
    }
    @keyframes blink {
        50% { opacity: 0; }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let labels = document.querySelectorAll('.new-label');
        labels.forEach(label => {
            let randomDelay = Math.random() * 3;
            label.style.animationDelay = randomDelay + "s";
        });
    });
</script>

<main>
<section>
<div class="hero-img">
    <div class="container text-center">
        <h1 class="fw-bold mb-4 box-color-light p-3 rounded" data-aos="zoom-in" data-aos-duration="800">JOB OPENINGS</h1>
        <div class="row mt-4 justify-content-center">
            <div class="col-md-3 mx-2" data-aos="fade-up" data-aos-delay="100">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">Employer Feedback Form</p>
                    <a href="./assets/pdfs/DIL_Employer_Survey_Feedback_Form.doc" class="btn btn-dark fw-bold bluecolor" target="_blank">DOWNLOAD</a>
                </div>
            </div>
            <div class="col-md-3 mx-2" data-aos="fade-up" data-aos-delay="200">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">Industry Invitation Letter</p>
                    <a href="./assets/pdfs/Recruitment_of Graduating.pdf" class="btn btn-dark fw-bold bluecolor" target="_blank">DOWNLOAD</a>
                </div>
            </div>
            <div class="col-md-3 mx-2" data-aos="fade-up" data-aos-delay="300">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">Process Flow</p>
                    <a href="./assets/pdfs/Process_Flow_of_Job_Placement_Activities.pdf" class="btn btn-dark fw-bold bluecolor" target="_blank">DOWNLOAD</a>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<section>
<div class="container mt-4">
    <!-- Fresh Graduates -->
    <h2 class="mb-3" data-aos="fade-up" data-aos-duration="200">JOB OPENINGS FOR FRESH GRADUATES</h2>
    <table class="table table-bordered table-striped" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
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
                echo "<td>";
                if (!empty($row["job_pdf"])) {
                    // Convert ../assets/ to ./assets/ for proper web path
                    $pdf_path = str_replace('../assets/', './assets/', $row["job_pdf"]);
                    echo "<a href='" . htmlspecialchars($pdf_path) . "' target='_blank' style='text-decoration: none; color: #073470; font-weight: bold;'>";
                    echo htmlspecialchars($row["job_title"]);
                    echo "</a>";
                } else {
                    echo htmlspecialchars($row["job_title"]);
                }
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

    <!-- Experienced -->
    <h2 class="mt-5 mb-3" data-aos="fade-up" data-aos-duration="200">JOB OPENINGS (EXPERIENCED)</h2>
    <table class="table table-bordered table-striped" data-aos="fade-up" data-aos-duration="1000" data-aos-duration="300">
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
                echo "<td>";
                if (!empty($row["job_pdf"])) {
                    // Convert ../assets/ to ./assets/ for proper web path
                    $pdf_path = str_replace('../assets/', './assets/', $row["job_pdf"]);
                    echo "<a href='" . htmlspecialchars($pdf_path) . "' target='_blank' style='text-decoration: none; color: #073470; font-weight: bold;'>";
                    echo htmlspecialchars($row["job_title"]);
                    echo "</a>";
                } else {
                    echo htmlspecialchars($row["job_title"]);
                }
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
