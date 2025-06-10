<?php
// Get the current URI and extract the first segment to determine the current page.
// This works when URLs are like "/Home", "/About", etc.
$requestURI = trim($_SERVER['REQUEST_URI'], '/');
$segments = explode('/', $requestURI);
$currentPage = isset($segments[0]) && $segments[0] !== '' ? $segments[0] : 'Home'; // default to Home if empty

// Include SEO configuration
require_once 'seo-config.php';
require_once 'analytics-seo.php';
require_once 'local-social-seo.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <?php echo SEOManager::generateMetaTags($currentPage); ?>
  
  <?php echo addGoogleAnalytics(); ?>
  <?php echo addPerformanceOptimizations(); ?>
  <?php echo addLdJsonOrganization(); ?>
  <?php echo LocalSEO::generateLocalBusinessSchema(); ?>
  <?php echo MobileOptimization::generateMobileMeta(); ?>
  <?php echo MobileOptimization::generateCriticalCSS(); ?>
  <!-- Preload critical resources -->
  <link rel="preload" href="./assets/css/style.css" as="style">
  <link rel="preload" href="./assets/images/heroimg.png" as="image">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" as="style">
  
  <!-- DNS Prefetch for external resources -->
  <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
  
  <link rel="icon" type="image/png" href="./assets/favicon/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="./assets/favicon/favicon.svg" />
<link rel="shortcut icon" href="./assets/favicon/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="./assets/favicon/apple-touch-icon.png" />
<meta name="apple-mobile-web-app-title" content="Directorate Of Industrial Liaison" />
<link rel="manifest" href="./assets/favicon/site.webmanifest" />

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="./assets/css/style.css" />
  <link rel="stylesheet" href="./assets/css/footer.css" />
</head>

<body>
  <header>
    <!-- Headbar -->
    <div class="gradient-bar headbar py-2">
      <div
        class="container d-flex flex-wrap justify-content-between align-items-center">
        <p
          class="text-white mb-2 mb-md-0 text-center text-md-start flex-grow-1">
          Directorate of Industrial Liaison (DIL) || <span style="color:#d6f402;"> <i>“Collaborate, Innovate, Transform”</i></span>
        </p>
        <div class="social-icons d-flex">
          <a href="#" class="text-blue mx-2"><i class="fab fa-linkedin"></i></a>
          <a href="#" class="text-black mx-2"><i class="fab fa-x-twitter"></i></a>
          <a href="#" class="text-danger mx-2"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
    </div>

    <!-- Contact Bar -->
    <div class="container-fluid contact-bar py-3">
      <div
        class="row align-items-center justify-content-center text-center row-cols-1 row-cols-md-5 g-3">
                <!-- Right Logo -->
                <div class="col d-flex justify-content-center">
          <img
            src="./assets/images/LOGONEDUET2LOGO_1 1.png"
            alt="Right Logo"
            class="logo img-fluid" />
        </div>
       

        <!-- Call Us -->
        <div class="col">
          <div
            class="contact-item d-flex align-items-center justify-content-center flex-column flex-md-row">
            <img
              src="./assets/images/call.png"
              alt="Call"
              class="contact-icon" />
            <div class="contact-text ms-md-3 mt-2 mt-md-0">
              <span>CALL US</span>
              <a href="tel:(92-21)-99261261-68" class="d-block">(92-21)-99261261-68 <br>Ext: 2274, 2218</a>
            </div>
          </div>
        </div>

        <!-- Email Us -->
        <div class="col">
          <div
            class="contact-item d-flex align-items-center justify-content-center flex-column flex-md-row">
            <img
              src="./assets/images/email.png"
              alt="Email"
              class="contact-icon" />
            <div class="contact-text ms-md-3 mt-2 mt-md-0">
              <span>EMAIL US</span>
              <a href="mailto:dil@neduet.edu.pk" class="d-block">dil@neduet.edu.pk</a>
            </div>
          </div>
        </div>

        <!-- Locate Us -->
        <div class="col">
          <div
            class="contact-item d-flex align-items-center justify-content-center flex-column flex-md-row">
            <img
              src="./assets/images/location.png"
              alt="Location"
              class="contact-icon" />
            <div class="contact-text ms-md-3 mt-2 mt-md-0">
              <span>LOCATE US</span>
              <a
                href="https://maps.app.goo.gl/S5U8Q6JDZpknhR1K6"
                class="d-block">University Road, Karachi</a>
            </div>
          </div>
        </div>
 <!-- Left Logo -->
 <div class="col d-flex justify-content-center">
          <img
            src="./assets/images/dillogo.png"
            alt="Left Logo"
            class="logo img-fluid w-50" />
        </div>

      </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar sticky-top">
    <div class="container">
        <!-- Navbar Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <?php
                $navItems = [
                    "Home" => "HOME",
                    "About" => "ABOUT US",
                    "Internships" => "INTERNSHIPS",
                    "Jobs" => "JOBS",
                    "FYDPs" => "FYDPs",
                    "DILActivities" => "DIL ACTIVITIES",
                    "IndustrialCollaboration" => "INDUSTRIAL COLLABORATION",
                    "Newsletter" => "NEWSLETTER",
                    "Downloads" => "DOWNLOADS"

                ];

                foreach ($navItems as $key => $label) {
                    $activeClass = ($currentPage === $key) ? 'active' : '';
                    echo "<li class='nav-item'><a class='nav-link $activeClass' href='../DIL/$key'>$label</a></li>";
                }
                ?>

                <!-- Industrial Collaboration Dropdown
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= ($currentPage === 'IndustrialCollaboration') ? 'active' : ''; ?>" href="#" id="industrialDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        INDUSTRIAL COLLABORATION
                    </a>
                    <ul class="dropdown-menu position-absolute" aria-labelledby="industrialDropdown">
                        <li><a class="dropdown-item" href="#">One Window Operation (In Progress)</a></li>
                        <li><a class="dropdown-item" href="../DIL/IndustrialCollaboration/MoUs">MoUs (Industry Logos)</a></li>
                        <li><a class="dropdown-item" href="../DIL/IndustrialCollaboration/CoopEducation">Coop Education (Flyer)</a></li>
                        <li><a class="dropdown-item" href="../DIL/IndustrialCollaboration/FIPP">FIPP Programme (Flyer)</a></li>
                        <li><a class="dropdown-item" href="../DIL/IndustrialCollaboration/R&D">R&D Joint Ventures</a></li>
                        <li><a class="dropdown-item" href="../DIL/IndustrialCollaboration/CareerFairs">Career Fairs</a></li>
                        <li><a class="dropdown-item" href="../DIL/IndustrialCollaboration/LeadershipConference">Leadership Conference</a></li>
                        <li><a class="dropdown-item" href="../DIL/IndustrialCollaboration/IndustrialPartners">Industrial Partners (Logos)</a></li>
                    </ul>
                </li> -->

            </ul>
        </div>
    </div>
</nav>



</header>
<script>
  document.addEventListener("click", function (event) {
    let dropdowns = document.querySelectorAll(".dropdown-menu");
    dropdowns.forEach((dropdown) => {
        if (!dropdown.parentElement.contains(event.target)) {
            dropdown.classList.remove("show");
        }
    });
});
</script>