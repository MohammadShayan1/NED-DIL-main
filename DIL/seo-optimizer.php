<?php
// Additional SEO Functions
class SEOOptimizer {
    
    // Generate breadcrumb navigation
    public static function generateBreadcrumbs($currentPage, $breadcrumbs = []) {
        $breadcrumbSchema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => []
        ];
        
        // Always start with Home
        $breadcrumbSchema["itemListElement"][] = [
            "@type" => "ListItem",
            "position" => 1,
            "name" => "Home",
            "item" => "https://dil.neduet.edu.pk/"
        ];
        
        // Add additional breadcrumbs if provided
        $position = 2;
        foreach ($breadcrumbs as $crumb) {
            $breadcrumbSchema["itemListElement"][] = [
                "@type" => "ListItem",
                "position" => $position,
                "name" => $crumb['name'],
                "item" => $crumb['url']
            ];
            $position++;
        }
        
        return json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES);
    }
    
    // Generate FAQ Schema
    public static function generateFAQSchema($faqs) {
        $faqSchema = [
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => []
        ];
        
        foreach ($faqs as $faq) {
            $faqSchema["mainEntity"][] = [
                "@type" => "Question",
                "name" => $faq['question'],
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => $faq['answer']
                ]
            ];
        }
        
        return json_encode($faqSchema, JSON_UNESCAPED_SLASHES);
    }
    
    // Generate Article Schema for blog posts/news
    public static function generateArticleSchema($title, $description, $author, $datePublished, $image = null) {
        $articleSchema = [
            "@context" => "https://schema.org",
            "@type" => "Article",
            "headline" => $title,
            "description" => $description,
            "author" => [
                "@type" => "Organization",
                "name" => $author
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => "Directorate of Industrial Liaison (DIL) - NED University",
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => "https://dil.neduet.edu.pk/assets/images/dillogo.png"
                ]
            ],
            "datePublished" => $datePublished,
            "dateModified" => $datePublished
        ];
        
        if ($image) {
            $articleSchema["image"] = [
                "@type" => "ImageObject",
                "url" => $image
            ];
        }
        
        return json_encode($articleSchema, JSON_UNESCAPED_SLASHES);
    }
    
    // Generate Course Schema for academic programs
    public static function generateCourseSchema($courseName, $description, $provider) {
        $courseSchema = [
            "@context" => "https://schema.org",
            "@type" => "Course",
            "name" => $courseName,
            "description" => $description,
            "provider" => [
                "@type" => "Organization",
                "name" => $provider,
                "sameAs" => "https://dil.neduet.edu.pk"
            ]
        ];
        
        return json_encode($courseSchema, JSON_UNESCAPED_SLASHES);
    }
    
    // Optimize images with lazy loading and proper alt text
    public static function optimizeImage($src, $alt, $class = "", $loading = "lazy") {
        return "<img src=\"{$src}\" alt=\"{$alt}\" class=\"{$class}\" loading=\"{$loading}\">";
    }
    
    // Generate meta tags for social sharing
    public static function generateSocialMeta($title, $description, $image, $url) {
        return "
        <!-- Open Graph -->
        <meta property=\"og:title\" content=\"{$title}\">
        <meta property=\"og:description\" content=\"{$description}\">
        <meta property=\"og:image\" content=\"{$image}\">
        <meta property=\"og:url\" content=\"{$url}\">
        <meta property=\"og:type\" content=\"website\">
        
        <!-- Twitter Card -->
        <meta name=\"twitter:card\" content=\"summary_large_image\">
        <meta name=\"twitter:title\" content=\"{$title}\">
        <meta name=\"twitter:description\" content=\"{$description}\">
        <meta name=\"twitter:image\" content=\"{$image}\">
        ";
    }
}
?>
