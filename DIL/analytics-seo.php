<?php
// Google Analytics and Performance Tracking
function addGoogleAnalytics() {
    return '
    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag("js", new Date());
      gtag("config", "G-XXXXXXXXXX"); // Replace with your actual GA4 measurement ID
    </script>
    ';
}

function addPerformanceOptimizations() {
    return '
    <!-- Critical CSS inline optimization -->
    <style>
        /* Critical above-the-fold CSS */
        .hero { 
            background: url(./assets/images/heroimg.png) no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }
        .custom-navbar {
            background: linear-gradient(45deg, #073470, #0571ff);
        }
    </style>
    
    <!-- Resource hints -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    ';
}

function addLdJsonOrganization() {
    return '
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "EducationalOrganization",
      "name": "Directorate of Industrial Liaison (DIL) - NED University",
      "alternateName": "DIL NED University",
      "url": "https://dil.neduet.edu.pk",
      "logo": "https://dil.neduet.edu.pk/assets/images/dillogo.png",
      "description": "The Directorate of Industrial Liaison (DIL) at NED University bridges academia, industry, and government to foster collaboration and provide internship, job placement, and research opportunities.",
      "foundingDate": "1997",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "University Road",
        "addressLocality": "Karachi",
        "addressRegion": "Sindh",
        "postalCode": "75270",
        "addressCountry": "Pakistan"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+92-21-99261261",
        "contactType": "customer service",
        "email": "dil@neduet.edu.pk",
        "availableLanguage": ["English", "Urdu"]
      },
      "parentOrganization": {
        "@type": "EducationalOrganization",
        "name": "NED University of Engineering and Technology",
        "url": "https://www.neduet.edu.pk"
      },
      "department": [
        {
          "@type": "Organization",
          "name": "Career Services",
          "description": "Job placement and career counseling services"
        },
        {
          "@type": "Organization", 
          "name": "Internship Programs",
          "description": "Industrial internship opportunities and coordination"
        },
        {
          "@type": "Organization",
          "name": "Industry Collaboration",
          "description": "Academia-industry partnership and research collaboration"
        }
      ],
      "offers": [
        {
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service",
            "name": "Internship Placement",
            "description": "Industrial internship opportunities for engineering students"
          }
        },
        {
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service", 
            "name": "Job Placement",
            "description": "Career placement services for graduates"
          }
        },
        {
          "@type": "Offer",
          "itemOffered": {
            "@type": "Service",
            "name": "Industry Collaboration",
            "description": "Research and development partnerships with industry"
          }
        }
      ],
      "sameAs": [
        "https://www.linkedin.com/company/ned-university",
        "https://www.facebook.com/neduniversity",
        "https://twitter.com/neduniversity"
      ]
    }
    </script>';
}
?>
