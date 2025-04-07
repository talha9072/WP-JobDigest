<?php
function wpjd_generate_and_download_file($jobs) {
    if (empty($jobs)) {
        // Stop WordPress rendering + return plain text error
        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=job-digest.txt");
        echo "No jobs found based on your filters.";
        exit;
    }

    // Prevent any accidental output buffering
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Set content headers
    header("Content-type: text/plain; charset=utf-8");
    header("Content-Disposition: attachment; filename=job-digest.txt");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Pragma: no-cache");

    // Build content
    $content = "📅 Latest Job Digest\n\n";
    $i = 1;

    foreach ($jobs as $job) {
        $content .= "{$i}. {$job['title']}\n";
        $content .= "   Company: {$job['company']}\n";
        $content .= "   Location: {$job['location']}\n";
        $content .= "   Link: {$job['url']}\n\n";
        $i++;
    }

    echo $content;
    exit;
}
