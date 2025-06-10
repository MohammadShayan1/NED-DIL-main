<?php
// SEO Monitoring and Optimization Tools
class SEOMonitor {
    
    // Check page load speed and optimization
    public static function checkPageSpeed() {
        $start_time = microtime(true);
        return $start_time;
    }
    
    public static function endPageSpeed($start_time) {
        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time);
        
        // Log slow pages (> 2 seconds)
        if ($execution_time > 2) {
            error_log("Slow page load: " . $_SERVER['REQUEST_URI'] . " - " . $execution_time . "s");
        }
        
        return $execution_time;
    }
    
    // Generate robots.txt dynamically
    public static function generateRobotsTxt() {
        header('Content-Type: text/plain');
        echo "User-agent: *\n";
        echo "Allow: /\n\n";
        echo "# Sitemap location\n";
        echo "Sitemap: https://dil.neduet.edu.pk/sitemap.xml\n\n";
        echo "# Disallow admin areas\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /config.php\n";
        echo "Disallow: /assets/uploads/\n\n";
        echo "# Allow important directories\n";
        echo "Allow: /assets/css/\n";
        echo "Allow: /assets/images/\n";
        echo "Allow: /assets/pdfs/\n\n";
        echo "# Crawl delay\n";
        echo "Crawl-delay: 1\n";
    }
    
    // Check for broken links
    public static function checkBrokenLinks($urls) {
        $broken_links = [];
        
        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_exec($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($status_code >= 400) {
                $broken_links[] = ['url' => $url, 'status' => $status_code];
            }
        }
        
        return $broken_links;
    }
    
    // Generate dynamic sitemap
    public static function generateDynamicSitemap() {
        header('Content-Type: application/xml');
        
        $pages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => '/Home', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => '/About', 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['url' => '/Internships', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => '/Jobs', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/FYDPs', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/DILActivities', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => '/IndustrialCollaboration', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/Newsletter', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/Downloads', 'priority' => '0.7', 'changefreq' => 'monthly']
        ];
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($pages as $page) {
            echo '  <url>' . "\n";
            echo '    <loc>https://dil.neduet.edu.pk' . $page['url'] . '</loc>' . "\n";
            echo '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
            echo '    <changefreq>' . $page['changefreq'] . '</changefreq>' . "\n";
            echo '    <priority>' . $page['priority'] . '</priority>' . "\n";
            echo '  </url>' . "\n";
        }
        
        echo '</urlset>';
    }
    
    // Log SEO events
    public static function logSEOEvent($event_type, $page, $details = '') {
        $log_entry = date('Y-m-d H:i:s') . " - " . $event_type . " - " . $page . " - " . $details . "\n";
        file_put_contents('seo_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
    }
}

// Keywords and content optimization
class ContentOptimizer {
    
    // Primary keywords for DIL website
    private static $primary_keywords = [
        'DIL NED University',
        'industrial liaison',
        'internships Pakistan',
        'job placement',
        'academia industry collaboration',
        'NED University Karachi',
        'engineering internships',
        'cooperative education',
        'industrial visits',
        'career development'
    ];
    
    // Check keyword density
    public static function checkKeywordDensity($content, $keyword) {
        $word_count = str_word_count(strip_tags($content));
        $keyword_count = substr_count(strtolower($content), strtolower($keyword));
        
        if ($word_count > 0) {
            return ($keyword_count / $word_count) * 100;
        }
        
        return 0;
    }
    
    // Suggest SEO improvements
    public static function suggestImprovements($content, $title, $meta_description) {
        $suggestions = [];
        
        // Check title length
        if (strlen($title) < 30 || strlen($title) > 60) {
            $suggestions[] = "Title should be between 30-60 characters (currently: " . strlen($title) . ")";
        }
        
        // Check meta description length
        if (strlen($meta_description) < 150 || strlen($meta_description) > 160) {
            $suggestions[] = "Meta description should be between 150-160 characters (currently: " . strlen($meta_description) . ")";
        }
        
        // Check for primary keywords in content
        $content_lower = strtolower($content);
        $keywords_found = 0;
        
        foreach (self::$primary_keywords as $keyword) {
            if (strpos($content_lower, strtolower($keyword)) !== false) {
                $keywords_found++;
            }
        }
        
        if ($keywords_found < 3) {
            $suggestions[] = "Consider including more relevant keywords from: " . implode(', ', self::$primary_keywords);
        }
        
        return $suggestions;
    }
}
?>
