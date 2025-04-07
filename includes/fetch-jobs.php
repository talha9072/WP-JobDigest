<?php
function wpjd_fetch_jobs_now($keyword, $location = '', $limit = 10, $debug = false) {
    $api_url = 'https://remoteok.com/api';

    if ($debug) {
        echo "<p><strong>API URL:</strong> <code>$api_url</code></p>";
    }

    $response = wp_remote_get($api_url, ['sslverify' => false]);

    if (is_wp_error($response)) {
        if ($debug) {
            echo '<p style="color:red;">API request failed: ' . esc_html($response->get_error_message()) . '</p>';
        }
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!is_array($data) || count($data) < 2) {
        if ($debug) echo '<p style="color:red;">Invalid API response format.</p>';
        return [];
    }

    array_shift($data); // remove metadata

    $scored_jobs = [];

    foreach ($data as $job) {
        if (!isset($job['position']) || !isset($job['company'])) continue;

        $score = 0;
        $title = $job['position'];
        $company = $job['company'];
        $job_loc = $job['location'] ?? '';
        $tags = $job['tags'] ?? [];

        if (!empty($keyword)) {
            if (stripos($title, $keyword) !== false) $score += 2;
            foreach ($tags as $tag) {
                if (stripos($tag, $keyword) !== false) $score += 1;
            }
        }

        if (!empty($location) && stripos($job_loc, $location) !== false) {
            $score += 1;
        }

        $scored_jobs[] = [
            'title'     => sanitize_text_field($title),
            'company'   => sanitize_text_field($company),
            'url'       => esc_url_raw($job['url'] ?? '#'),
            'location'  => sanitize_text_field($job_loc),
            'score'     => $score,
            'timestamp' => time()
        ];
    }

    usort($scored_jobs, fn($a, $b) => $b['score'] <=> $a['score']);
    $top_jobs = array_slice($scored_jobs, 0, $limit);

    if ($debug && !empty($top_jobs)) {
        echo '<p><strong>Top Matches:</strong></p><ul>';
        foreach ($top_jobs as $j) {
            echo '<li>' . esc_html($j['title']) . ' — ' . esc_html($j['company']) . ' (' . esc_html($j['location']) . ')</li>';
        }
        echo '</ul>';
    }

    return $top_jobs;
}
