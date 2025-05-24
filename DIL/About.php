<?php 
include 'header.php';
require_once "config.php"; // Database connection

// Fetch all employees' data
$sql = "SELECT * FROM Employees ORDER BY position";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>
<link rel="stylesheet" href="./assets/css/about.css">
<main>

<!-- Mission Section -->
<section class="mission">
  <div class="container text-center">
    <!-- Heading & Image Inline -->
    <div class="d-flex align-items-center justify-content-center gap-3 mb-4">
      <h3 class="fw-bold mb-0">“Collaborate, Innovate, Transform”</h3>
      <img src="./assets/images/Asset 1.png" alt="" style="max-height: 50px;">
    </div>

    <!-- Vision & Mission Section -->
    <div class="row justify-content-center">
      <div class="col-md-6 col-12 p-3">
        <h4 class="fw-bold">DIL VISION</h4>
        <p class="text-justify">
          "Bridging academia, industry, and government to cultivate skilled
          professionals and foster a vibrant ecosystem for sustainable
          socio-economic impact, aligned with the UN-SDGs."
        </p>
      </div>
      <div class="col-md-6 col-12 p-3">
        <h4 class="fw-bold">DIL MISSION</h4>
        <p class="text-justify">
          “To serve as a vital bridge among academia, industry, and
          government, catalyzing students' seamless transition into
          professional realms and technological progress by offering
          insightful industry visits, extensive internship opportunities,
          comprehensive career counseling, mentorship, stimulating
          final-year design projects, effective job placement services, and
          fostering collaborative avenues for research and development
          initiatives.”
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Activities Section -->
<section class="container py-5">
  <h2 class="fw-bold text-center mb-4">Our Key Activities</h2>
  <div class="row g-4">

    <!-- Industrial Visits -->
    <div class="col-md-4">
      <div class="card h-100 shadow-sm p-3">
        <h5 class="fw-bold"><i class="fas fa-industry me-2" style="color: #d6f402;"></i> Industrial Visits</h5>
        <p>Organizing study visits for students and faculty to provide real-world exposure in various industries and organizations.</p>
      </div>
    </div>

    <!-- Internships & Co-op -->
    <div class="col-md-4">
      <div class="card h-100 shadow-sm p-3">
        <h5 class="fw-bold"><i class="fas fa-briefcase me-2" style="color: #d6f402;"></i> Internships & Co-op Education</h5>
        <p>Arranging internship opportunities from the first year to final year, with potential for long-term placement through performance-based continuation.</p>
      </div>
    </div>

    <!-- FYDP -->
    <div class="col-md-4">
      <div class="card h-100 shadow-sm p-3">
        <h5 class="fw-bold"><i class="fas fa-project-diagram me-2" style="color: #d6f402;"></i> Final Year Design Projects (FYDPs)</h5>
        <p>Facilitating industry-driven final year projects with technical and practical support to ensure impactful student outcomes.</p>
      </div>
    </div>

    <!-- Job Placement -->
    <div class="col-md-4">
      <div class="card h-100 shadow-sm p-3">
        <h5 class="fw-bold"><i class="fas fa-user-tie me-2" style="color: #d6f402;"></i> Job Placement</h5>
        <p>Supporting employers with on-campus drives, career fairs, graduate directories, and online job portals to connect with skilled graduates.</p>
      </div>
    </div>

    <!-- Mentorship -->
    <div class="col-md-4">
      <div class="card h-100 shadow-sm p-3">
        <h5 class="fw-bold"><i class="fas fa-chalkboard-teacher me-2" style="color: #d6f402;"></i> Mentorship & Career Counseling</h5>
        <p>Organizing mentoring sessions and career counseling to guide students in shaping their professional journey.</p>
      </div>
    </div>

    <!-- Exhibitions -->
    <div class="col-md-4">
      <div class="card h-100 shadow-sm p-3">
        <h5 class="fw-bold"><i class="fas fa-lightbulb me-2" style="color: #d6f402;"></i> Exhibitions</h5>
        <p>Encouraging students to showcase their projects and innovations at professional fairs, expos, and technical shows.</p>
      </div>
    </div>

    <!-- Faculty Industrial Placement -->
    <div class="col-md-6">
      <div class="card h-100 shadow-sm p-3">
        <h5 class="fw-bold"><i class="fas fa-user-graduate me-2" style="color: #d6f402;"></i> Faculty Industrial Placement (FIPP)</h5>
        <p>Inviting industries to host NED faculty during vacations, enriching teaching methodologies and preparing students for market demands.</p>
      </div>
    </div>

    <!-- Other Industrial Collaboration -->
    <div class="col-md-6">
      <div class="card h-100 shadow-sm p-3">
        <h5 class="fw-bold"><i class="fas fa-handshake me-2" style="color: #d6f402;"></i> Other Industrial Collaboration</h5>
        <p>Facilitating joint efforts in industrial training, lab testing, consultancy services, and R&D through NED's expert faculty and facilities.</p>
      </div>
    </div>

  </div>
</section>


<!-- Contact Section -->
<div class="container py-5">
  <div class="text-center">
    <h1 class="fw-bold">“Reach Out to Your Support System”</h1>
    <p class="text-muted">
      The Directorate of Industrial Liaison team is here to help — 
      connect with our faculty and staff today.
    </p>
  </div>

  <div class="row justify-content-center">
    <div class="col-lg-6 text-center my-3">
      <h2 class="fw-bold">CONTACT US</h2>
      <hr class="w-50 mx-auto">
    </div>
  </div>

  <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
          <div class="row align-items-center text-center my-4 bg-light shadow-sm p-3 rounded">
              <div class="col-md-3">
                  <h5 class="fw-semibold"><?= htmlspecialchars($row['name']); ?></h5>
                  <p class="text-muted m-0"><?= htmlspecialchars($row['email']); ?></p>
              </div>
              <div class="col-md-3">
                  <p class="fw-medium text-dark"><?= htmlspecialchars($row['designation']); ?></p>
              </div>
              <div class="col-md-3">
                  <p class="fw-medium"><?= htmlspecialchars($row['office_number']); ?></p>
              </div>
              <div class="col-md-3">
                  <img src="<?= htmlspecialchars(substr($row['image_path'], 1)); ?>" 
                       alt="<?= htmlspecialchars($row['name']); ?>" 
                       class="rounded-circle img-fluid shadow" 
                       style="max-width: 80px;">
              </div>
          </div>
          <hr class="mt-4">
      <?php endwhile; ?>
  <?php else: ?>
      <div class="text-center text-muted">
          <p>No Employee information available at the moment.</p>
      </div>
  <?php endif; ?>
</div>

</main>
<?php include 'footer.php'; ?>
