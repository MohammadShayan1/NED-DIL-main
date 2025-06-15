<?php
include 'header.php';
include_once './config.php';

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']) && isset($_POST['id']) && isset($_POST['type'])) {
    $id = intval($_POST['id']);
    $type = $_POST['type'] === 'fresh' ? 'job_openings_fresh' : 'job_openings_experienced';
    $stmt = $conn->prepare("DELETE FROM $type WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch jobs
$sql_fresh = "SELECT id, job_title, issue_date FROM job_openings_fresh ORDER BY issue_date DESC";
$result_fresh = $conn->query($sql_fresh);

$sql_experienced = "SELECT id, job_title, issue_date FROM job_openings_experienced ORDER BY issue_date DESC";
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
        <h1 class="fw-bold mb-4 box-color-light p-3 rounded" data-aos="zoom-in" data-aos-duration="800">PATH WAY TO OPPORTUNITIES</h1>
        <div class="row mt-4 justify-content-center">
            <div class="col-md-3 mx-2" data-aos="fade-up" data-aos-delay="100">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">Industry Invitation Letter</p>
                    <a href="./assets/pdfs/Industry Invitation Letter.pdf" class="btn btn-dark fw-bold" target="_blank">DOWNLOAD</a>
                </div>
            </div>
            <div class="col-md-3 mx-2" data-aos="fade-up" data-aos-delay="200">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">Industry Invitation Letter</p>
                    <a href="./assets/pdfs/Industry Invitation Letter.pdf" class="btn btn-dark fw-bold" target="_blank">DOWNLOAD</a>
                </div>
            </div>
            <div class="col-md-3 mx-2" data-aos="fade-up" data-aos-delay="300">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">DIL Notice Board</p>
                    <a href="./assets/pdfs/Experienced Job Form (For Companies).pdf" class="btn btn-dark fw-bold" target="_blank">OPEN</a>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<section>
<div class="container mt-4">
    <div class="row text-center">
        <h1 class="mb-4" data-aos="fade-down" data-aos-duration="800">JOB OPENINGS</h1>
    </div>

    <!-- Fresh Graduates -->
    <h2 class="mb-3" data-aos="slide-right" data-aos-duration="800">JOB OPENINGS FOR FRESH GRADUATES</h2>
    <table class="table table-bordered table-striped" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
        <thead class="text-center">
            <tr>
                <th style="background-color: #073470; color: white;">Job Title</th>
                <th style="background-color: #073470; color: white;">Date</th>
                <th style="background-color: #073470; color: white;">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result_fresh->num_rows > 0) {
            $count = 0;
            while ($row = $result_fresh->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["job_title"]);
                if ($count < 5) {
                    echo " <span class='new-label'>NEW!</span>";
                }
                echo "</td>";
                echo "<td class='text-center'>" . strtoupper(date("d-M-Y", strtotime($row["issue_date"]))) . "</td>";
                echo "<td class='text-center'>
                        <form method='POST' onsubmit=\"return confirm('Are you sure you want to delete this job?');\">
                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                            <input type='hidden' name='type' value='fresh'>
                            <button type='submit' name='delete' class='btn btn-danger btn-sm'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
                $count++;
            }
        } else {
            echo "<tr><td colspan='3' class='text-center'>No records found</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <!-- Experienced -->
    <h2 class="mt-5 mb-3">JOB OPENINGS (EXPERIENCED)</h2>
    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th style="background-color: #073470; color: white;">Job Title</th>
                <th style="background-color: #073470; color: white;">Date</th>
                <th style="background-color: #073470; color: white;">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result_experienced->num_rows > 0) {
            $count = 0;
            while ($row = $result_experienced->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["job_title"]);
                if ($count < 5) {
                    echo " <span class='new-label'>NEW!</span>";
                }
                echo "</td>";
                echo "<td class='text-center'>" . strtoupper(date("d-M-Y", strtotime($row["issue_date"]))) . "</td>";
                echo "<td class='text-center'>
                        <form method='POST' onsubmit=\"return confirm('Are you sure you want to delete this job?');\">
                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                            <input type='hidden' name='type' value='experienced'>
                            <button type='submit' name='delete' class='btn btn-danger btn-sm'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
                $count++;
            }
        } else {
            echo "<tr><td colspan='3' class='text-center'>No records found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</section>
</main>

<?php include 'footer.php'; ?>
