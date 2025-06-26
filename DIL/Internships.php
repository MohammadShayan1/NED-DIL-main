<?php include 'header.php';
include_once './config.php';

$sql = "SELECT subject_text, pdf_file, issue_date
        FROM internship_programs
        ORDER BY issue_date ASC";
$result = $conn->query($sql);
?>
<link rel="stylesheet" href="./assets/css/internship.css">
<main>
<!-- Hero Section -->
 <section>
<div class="hero-img">
    <div class="container text-center">
        <h1 class="fw-bold mb-4 box-color-light p-3 rounded" data-aos="zoom-in" data-aos-duration="800">PATH WAY TO OPPORTUNITIES</h1>
        <!-- Forms Section -->
        <div class="row mt-4 justify-content-center">
            <div class="col-md-3 mx-2" data-aos="fade-up" data-aos-delay="100">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">SELF INTERNSHIP APPLICATION FORM</p>
                    <a href="./assets/pdfs/Self Internships Application Form.pdf" class="btn btn-dark fw-bold bluecolor" target="_blank">DOWNLOAD</a>
                </div>
            </div>
            <div class="col-md-3 mx-2" data-aos="fade-up" data-aos-delay="200">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">INTERNSHIP FEEDBACK FORM</p>
                    <a href="./assets/pdfs/Internship Feedback Form.pdf" class="btn btn-dark fw-bold bluecolor" target="_blank">DOWNLOAD</a>
                </div>
            </div>
            <div class="col-md-3 mx-2" data-aos="fade-up" data-aos-delay="300">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">INTERNEE INTERNSHIP FEEDBACK FORM</p>
                    <a href="./assets/pdfs/Internship Feedback Form.pdf" class="btn btn-dark fw-bold bluecolor" target="_blank">DOWNLOAD</a>
                </div>
            </div>
        </div>
        <div class="row mt-4 justify-content-center">
            <div class="col-md-3 mx-2" data-aos="fade-up" data-aos-delay="400">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">Internship Notice Board</p>
                    <a href="https://pl.neduet.edu.pk/notices/DIL_Notices.jsp" class="btn btn-dark fw-bold bluecolor" target="_blank">OPEN</a>
                </div>
            </div>
            <div class="col-md-3 mx-2" data-aos="fade-up" data-aos-delay="500">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">Process Flow</p>
                    <a href="./assets/pdfs/INTERNSHIPS - Process Flow Chart.pdf" class="btn btn-dark fw-bold bluecolor" target="_blank">DOWNLOAD</a>
                </div>
            </div>
            <div class="col-md-3 mx-2" data-aos="fade-up" data-aos-delay="600">
                <div class="card box-color-light text-center p-3">
                    <p class="fw-bold">Industry Invitation Letter</p>
                    <a href="./assets/pdfs/Invitation for Internships-2025.pdf" class="btn btn-dark fw-bold bluecolor" target="_blank">DOWNLOAD</a>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<section>
<div class="container mt-4">
    <div class="row text-center">
    <h1 class="mb-4">Internships</h1>
    </div>
    <table class="table table-bordered table-striped">
        <thead class="text-center">
            <tr>
                <th style="background-color: #073470; color: white;">Subject</th>
                <th style="background-color: #073470; color: white;">Issue Date</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // 3. Loop through the results and display each row
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
$pdf = $row["pdf_file"];
$text = htmlspecialchars($row["subject_text"]);

if (!empty($pdf)) {
    echo "<td><a href='./assets/uploads/internships/$pdf' target='_blank'>$text</a></td>";
} else {
    echo "<td>$text</td>";
}

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