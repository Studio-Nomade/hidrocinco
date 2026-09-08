<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PostRepository;
use App\Repositories\ServiceRepository;

final class SitemapController
{
    public function index(): void
    {
        header('Content-Type: application/xml; charset=UTF-8');
        $entries = [
            ['loc' => url('/'), 'priority' => '1.0'],
            ['loc' => url('/blog'), 'priority' => '0.8'],
        ];
        foreach ((new ServiceRepository())->allPublishedOrdered() as $service) {
            $entries[] = ['loc' => url('/servicios/' . $service['slug']), 'lastmod' => substr((string) $service['updated_at'], 0, 10), 'priority' => '0.9'];
        }
        foreach ((new PostRepository())->published() as $post) {
            $entries[] = ['loc' => url('/blog/' . $post['slug']), 'lastmod' => substr((string) ($post['updated_at'] ?: $post['published_at']), 0, 10), 'priority' => '0.7'];
        }
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($entries as $entry) {
            echo "  <url>\n    <loc>" . e($entry['loc']) . "</loc>\n";
            if (!empty($entry['lastmod'])) echo '    <lastmod>' . e($entry['lastmod']) . "</lastmod>\n";
            echo '    <priority>' . e($entry['priority']) . "</priority>\n  </url>\n";
        }
        echo '</urlset>';
    }
}
