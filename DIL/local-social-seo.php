<?php
// Local SEO Optimization for Karachi, Pakistan
class LocalSEO {
    
    // Generate local business schema
    public static function generateLocalBusinessSchema() {
        return '
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "EducationalOrganization",
          "name": "Directorate of Industrial Liaison (DIL) - NED University",
          "image": "https://dil.neduet.edu.pk/assets/images/dillogo.png",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "University Road",
            "addressLocality": "Karachi",
            "addressRegion": "Sindh",
            "postalCode": "75270",
            "addressCountry": "PK"
          },
          "geo": {
            "@type": "GeoCoordinates",
            "latitude": 24.8607,
            "longitude": 67.0011
          },
          "telephone": "+92-21-99261261",
          "email": "dil@neduet.edu.pk",
          "url": "https://dil.neduet.edu.pk",
          "openingHoursSpecification": [
            {
              "@type": "OpeningHoursSpecification",
              "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
              "opens": "08:00",
              "closes": "17:00"
            }
          ],
          "areaServed": {
            "@type": "State",
            "name": "Sindh, Pakistan"
          },
          "serviceArea": {
            "@type": "GeoCircle",
            "geoMidpoint": {
              "@type": "GeoCoordinates",
              "latitude": 24.8607,
              "longitude": 67.0011
            },
            "geoRadius": "50000"
          }
        }
        </script>';
    }
    
    // Generate local keywords for Pakistan/Karachi
    public static function getLocalKeywords() {
        return [
            'DIL NED University Karachi',
            'industrial liaison Karachi',
            'engineering internships Karachi',
            'job placement Pakistan',
            'university industry collaboration Pakistan',
            'NED University Sindh',
            'technical education Karachi',
            'industrial training Pakistan',
            'cooperative education Karachi',
            'engineering jobs Karachi',
            'internships Sindh Pakistan',
            'career services Karachi',
            'academia industry linkage Pakistan'
        ];
    }
}

// Social Media Integration and Optimization
class SocialMediaSEO {
    
    // Generate Open Graph meta tags
    public static function generateOpenGraph($title, $description, $image, $url, $type = 'website') {
        return "
        <meta property=\"og:site_name\" content=\"DIL NED University\">
        <meta property=\"og:title\" content=\"{$title}\">
        <meta property=\"og:description\" content=\"{$description}\">
        <meta property=\"og:image\" content=\"{$image}\">
        <meta property=\"og:image:width\" content=\"1200\">
        <meta property=\"og:image:height\" content=\"630\">
        <meta property=\"og:url\" content=\"{$url}\">
        <meta property=\"og:type\" content=\"{$type}\">
        <meta property=\"og:locale\" content=\"en_US\">
        <meta property=\"article:publisher\" content=\"https://www.facebook.com/neduniversity\">
        ";
    }
    
    // Generate Twitter Card meta tags
    public static function generateTwitterCard($title, $description, $image, $site = '@neduniversity') {
        return "
        <meta name=\"twitter:card\" content=\"summary_large_image\">
        <meta name=\"twitter:site\" content=\"{$site}\">
        <meta name=\"twitter:creator\" content=\"{$site}\">
        <meta name=\"twitter:title\" content=\"{$title}\">
        <meta name=\"twitter:description\" content=\"{$description}\">
        <meta name=\"twitter:image\" content=\"{$image}\">
        <meta name=\"twitter:image:alt\" content=\"DIL NED University Logo\">
        ";
    }
    
    // Generate LinkedIn meta tags
    public static function generateLinkedInMeta($title, $description, $image) {
        return "
        <meta property=\"linkedin:owner\" content=\"company/ned-university\">
        <meta property=\"linkedin:title\" content=\"{$title}\">
        <meta property=\"linkedin:description\" content=\"{$description}\">
        <meta property=\"linkedin:image\" content=\"{$image}\">
        ";
    }
}

// Mobile and Performance Optimization
class MobileOptimization {
    
    // Generate mobile-specific meta tags
    public static function generateMobileMeta() {
        return '
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="DIL NED">
        <meta name="theme-color" content="#073470">
        <meta name="msapplication-TileColor" content="#073470">
        <meta name="msapplication-navbutton-color" content="#073470">
        ';
    }
    
    // Generate AMP (Accelerated Mobile Pages) link
    public static function generateAMPLink($amp_url) {
        return "<link rel=\"amphtml\" href=\"{$amp_url}\">";
    }
    
    // Generate critical CSS for above-the-fold content
    public static function generateCriticalCSS() {
        return '
        <style>
        /* Critical CSS for above-the-fold content */
        body{font-family:"Inter",sans-serif;margin:0;padding:0;background-color:#f8f9fa}
        .hero{background:url(./assets/images/heroimg.png) no-repeat center center/cover;height:100vh;display:flex;justify-content:center;align-items:center;color:#fff;padding:4rem 2rem;margin:0}
        .custom-navbar{background:linear-gradient(45deg,#073470,#0571ff);position:sticky;top:0;z-index:1000}
        .gradient-bar{background:linear-gradient(45deg,#073470,#0571ff)}
        .contact-bar{background-color:#f8f9fa;border-bottom:1px solid #dee2e6}
        .hero h1{font-size:2.5rem;text-transform:uppercase;font-weight:bold}
        .hero p{font-size:1.2rem;line-height:1.6}
        .btn{padding:10px 20px;font-size:1rem;font-weight:600;background:#073470;border:none;color:white;text-decoration:none;display:inline-block}
        </style>';
    }
}
?>
