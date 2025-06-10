<?php
// SEO Configuration and Meta Data
class SEOManager {
    private static $pages = [
        'Home' => [
            'title' => 'Directorate of Industrial Liaison (DIL) - NED University | Industry-Academia Collaboration',
            'description' => 'DIL NED University bridges academia, industry & government. Offering internships, job placements, industrial visits, cooperative education, and research collaborations in Karachi, Pakistan.',
            'keywords' => 'DIL NED University, industrial liaison, internships Pakistan, job placement, academia industry collaboration, NED University Karachi, engineering internships, cooperative education, industrial visits, career development',
            'canonical' => 'https://dil.neduet.edu.pk/',
            'og_image' => 'https://dil.neduet.edu.pk/assets/images/dillogo.png'
        ],
        'About' => [
            'title' => 'About DIL - Directorate of Industrial Liaison | NED University',
            'description' => 'Learn about DIL\'s mission, vision, and team. Established in 1997, DIL fosters collaboration between NED University students, industry, and government for sustainable development.',
            'keywords' => 'about DIL, NED University liaison, industrial collaboration mission, academia industry partnership, DIL team, university industry bridge',
            'canonical' => 'https://dil.neduet.edu.pk/About',
            'og_image' => 'https://dil.neduet.edu.pk/assets/images/missionvision.png'
        ],
        'Internships' => [
            'title' => 'Internship Opportunities | DIL NED University - Engineering & Technology Internships',
            'description' => 'Explore engineering and technology internship opportunities through DIL NED University. Connect with leading industries for hands-on experience and career development.',
            'keywords' => 'internships Pakistan, engineering internships, NED University internships, technology internships Karachi, industrial training, student placements',
            'canonical' => 'https://dil.neduet.edu.pk/Internships',
            'og_image' => 'https://dil.neduet.edu.pk/assets/images/internshipinterface.png'
        ],
        'Jobs' => [
            'title' => 'Job Opportunities & Career Placement | DIL NED University',
            'description' => 'Find engineering and technology job opportunities through DIL NED University. Career placement services connecting graduates with top industries in Pakistan.',
            'keywords' => 'jobs Pakistan, engineering jobs, NED University careers, graduate placement, technology jobs Karachi, career opportunities',
            'canonical' => 'https://dil.neduet.edu.pk/Jobs',
            'og_image' => 'https://dil.neduet.edu.pk/assets/images/dillogo.png'
        ],
        'FYDPs' => [
            'title' => 'Final Year Design Projects (FYDPs) | DIL NED University',
            'description' => 'Explore innovative Final Year Design Projects (FYDPs) at NED University. Industry-sponsored projects fostering innovation and practical engineering solutions.',
            'keywords' => 'FYDP NED University, final year projects, engineering design projects, innovation projects Pakistan, student research projects',
            'canonical' => 'https://dil.neduet.edu.pk/FYDPs',
            'og_image' => 'https://dil.neduet.edu.pk/assets/images/dillogo.png'
        ],
        'DILActivities' => [
            'title' => 'DIL Activities & Events | NED University Industrial Liaison Programs',
            'description' => 'Discover DIL activities including career fairs, industrial visits, workshops, and industry-academia collaboration events at NED University.',
            'keywords' => 'DIL activities, career fair, industrial visits, NED University events, industry workshops, academia collaboration events',
            'canonical' => 'https://dil.neduet.edu.pk/DILActivities',
            'og_image' => 'https://dil.neduet.edu.pk/assets/images/dillogo.png'
        ],
        'IndustrialCollaboration' => [
            'title' => 'Industrial Collaboration & Partnerships | DIL NED University',
            'description' => 'Explore industrial collaboration opportunities, MoUs, cooperative education programs, and research partnerships through DIL NED University.',
            'keywords' => 'industrial collaboration, MoU partnerships, cooperative education, research collaboration, industry partnerships NED University',
            'canonical' => 'https://dil.neduet.edu.pk/IndustrialCollaboration',
            'og_image' => 'https://dil.neduet.edu.pk/assets/images/dillogo.png'
        ],
        'Newsletter' => [
            'title' => 'DIL Newsletter | Latest Updates from NED University Industrial Liaison',
            'description' => 'Stay updated with DIL newsletter featuring latest industry collaborations, internship opportunities, and academic-industry partnership news.',
            'keywords' => 'DIL newsletter, industry updates, NED University news, academic industry partnership news, internship updates',
            'canonical' => 'https://dil.neduet.edu.pk/Newsletter',
            'og_image' => 'https://dil.neduet.edu.pk/assets/images/dillogo.png'
        ],
        'Downloads' => [
            'title' => 'Downloads & Resources | DIL NED University Forms & Documents',
            'description' => 'Download internship forms, FIPP flyers, cooperative education documents, and other resources from DIL NED University.',
            'keywords' => 'DIL downloads, internship forms, FIPP flyer, cooperative education forms, NED University resources',
            'canonical' => 'https://dil.neduet.edu.pk/Downloads',
            'og_image' => 'https://dil.neduet.edu.pk/assets/images/dillogo.png'
        ]
    ];

    public static function getPageData($page) {
        return isset(self::$pages[$page]) ? self::$pages[$page] : self::$pages['Home'];
    }

    public static function generateMetaTags($page) {
        $data = self::getPageData($page);
        
        $meta = "
    <!-- Primary Meta Tags -->
    <title>{$data['title']}</title>
    <meta name=\"title\" content=\"{$data['title']}\">
    <meta name=\"description\" content=\"{$data['description']}\">
    <meta name=\"keywords\" content=\"{$data['keywords']}\">
    <meta name=\"author\" content=\"Directorate of Industrial Liaison, NED University\">
    <meta name=\"robots\" content=\"index, follow\">
    <meta name=\"language\" content=\"English\">
    <meta name=\"revisit-after\" content=\"7 days\">
    <link rel=\"canonical\" href=\"{$data['canonical']}\">

    <!-- Open Graph / Facebook -->
    <meta property=\"og:type\" content=\"website\">
    <meta property=\"og:url\" content=\"{$data['canonical']}\">
    <meta property=\"og:title\" content=\"{$data['title']}\">
    <meta property=\"og:description\" content=\"{$data['description']}\">
    <meta property=\"og:image\" content=\"{$data['og_image']}\">
    <meta property=\"og:site_name\" content=\"DIL NED University\">
    <meta property=\"og:locale\" content=\"en_US\">

    <!-- Twitter -->
    <meta property=\"twitter:card\" content=\"summary_large_image\">
    <meta property=\"twitter:url\" content=\"{$data['canonical']}\">
    <meta property=\"twitter:title\" content=\"{$data['title']}\">
    <meta property=\"twitter:description\" content=\"{$data['description']}\">
    <meta property=\"twitter:image\" content=\"{$data['og_image']}\">

    <!-- Additional SEO Meta Tags -->
    <meta name=\"geo.region\" content=\"PK-SD\">
    <meta name=\"geo.placename\" content=\"Karachi\">
    <meta name=\"geo.position\" content=\"24.8607;67.0011\">
    <meta name=\"ICBM\" content=\"24.8607, 67.0011\">
    
    <!-- Schema.org structured data -->
    <script type=\"application/ld+json\">
    {
        \"@context\": \"https://schema.org\",
        \"@type\": \"EducationalOrganization\",
        \"name\": \"Directorate of Industrial Liaison (DIL) - NED University\",
        \"url\": \"https://dil.neduet.edu.pk\",
        \"logo\": \"https://dil.neduet.edu.pk/assets/images/dillogo.png\",
        \"description\": \"DIL NED University bridges academia, industry & government for collaborative education and research.\",
        \"address\": {
            \"@type\": \"PostalAddress\",
            \"streetAddress\": \"University Road\",
            \"addressLocality\": \"Karachi\",
            \"addressRegion\": \"Sindh\",
            \"postalCode\": \"75270\",
            \"addressCountry\": \"PK\"
        },
        \"contactPoint\": {
            \"@type\": \"ContactPoint\",
            \"telephone\": \"+92-21-99261261\",
            \"contactType\": \"customer service\",
            \"email\": \"dil@neduet.edu.pk\"
        },
        \"sameAs\": [
            \"https://www.linkedin.com/company/ned-university\",
            \"https://www.facebook.com/neduniversity\",
            \"https://twitter.com/neduniversity\"
        ]
    }
    </script>";
        
        return $meta;
    }
}
?>
