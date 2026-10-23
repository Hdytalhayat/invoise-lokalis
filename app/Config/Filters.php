<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\AuthFilter;

class Filters extends BaseFilters
{
    /** @var array<string, class-string|list<class-string>> */
    public array $aliases = [
        'csrf'           => CSRF::class,
        'toolbar'        => DebugToolbar::class,
        'honeypot'       => Honeypot::class,
        'invalidchars'   => InvalidChars::class,
        'secureheaders'  => SecureHeaders::class,
        'cors'           => Cors::class,
        'forcehttps'     => ForceHTTPS::class,
        'pagecache'      => PageCache::class,
        'performance'    => PerformanceMetrics::class,
        'auth'           => AuthFilter::class,
    ];

    /** @var array{before: list<string>, after: list<string>} */
    public array $required = [
        'before' => ['forcehttps', 'pagecache'],
        'after'  => ['pagecache', 'performance', 'toolbar'],
    ];

    /** @var array{before: array|string, after: array|string} */
    public array $globals = [
        'before' => [],
        'after'  => [],
    ];

    /** @var array<string, list<string>> */
    public array $methods = [];

    /** @var array<string, array{before?: list<string>, after?: list<string>}> */
    public array $filters = [
        'auth' => [
            'before' => ['/profile/*', '/dashboard'],
        ],
    ];
}
