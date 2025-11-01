<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VisitLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TrackVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Execute the request
        $response = $next($request);
        
        // Calculate response time
        $responseTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds
        
        // Skip tracking for certain routes
        if ($this->shouldSkip($request)) {
            return $response;
        }

        try {
            // Get user agent parser if available (or use simple parsing)
            $userAgent = $request->userAgent();
            $parsedMetadata = $this->parseUserAgent($userAgent);
            
            // Get IP location data
            $ipAddress = $request->ip();
            $locationData = $this->getLocationData($ipAddress);
            
            // Merge all metadata
            $metadata = array_merge($parsedMetadata, $locationData, [
                'ip' => $ipAddress,
            ]);

            // Generate request ID if not exists
            $requestId = $request->header('X-Request-ID') ?? (string) Str::uuid();

            VisitLog::create([
                'user_id' => auth()->id(),
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent ?: null,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'route_name' => $request->route()?->getName(),
                'referer' => $request->header('referer'),
                'metadata' => $metadata,
                'session_id' => $request->session()->getId(),
                'request_id' => $requestId,
                'response_time' => round($responseTime, 2),
                'status_code' => $response->getStatusCode(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the request
            Log::error('Failed to track visit: ' . $e->getMessage());
        }

        return $response;
    }

    /**
     * Determine if the request should be skipped
     */
    private function shouldSkip(Request $request): bool
    {
        // Skip AJAX requests (optional - you can remove this if you want to track all)
        // if ($request->ajax() || $request->wantsJson()) {
        //     return true;
        // }

        // Skip certain routes (like assets, images, etc.)
        $skipRoutes = [
            'storage',
            'assets',
            'build',
            'vendor',
        ];

        $path = $request->path();
        foreach ($skipRoutes as $skip) {
            if (str_starts_with($path, $skip)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Parse user agent to extract browser, device, platform info
     */
    private function parseUserAgent(?string $userAgent): array
    {
        if (!$userAgent) {
            return [];
        }

        $metadata = [];

        // Browser detection
        if (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Edg')) {
            $metadata['browser'] = 'Chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            $metadata['browser'] = 'Firefox';
        } elseif (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) {
            $metadata['browser'] = 'Safari';
        } elseif (str_contains($userAgent, 'Edg')) {
            $metadata['browser'] = 'Edge';
        } elseif (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) {
            $metadata['browser'] = 'Opera';
        }

        // Platform/OS detection
        if (str_contains($userAgent, 'Windows')) {
            $metadata['platform'] = 'Windows';
        } elseif (str_contains($userAgent, 'Mac OS')) {
            $metadata['platform'] = 'macOS';
        } elseif (str_contains($userAgent, 'Linux')) {
            $metadata['platform'] = 'Linux';
        } elseif (str_contains($userAgent, 'Android')) {
            $metadata['platform'] = 'Android';
        } elseif (str_contains($userAgent, 'iOS')) {
            $metadata['platform'] = 'iOS';
        }

        // Device type
        if (str_contains($userAgent, 'Mobile')) {
            $metadata['device'] = 'Mobile';
        } elseif (str_contains($userAgent, 'Tablet')) {
            $metadata['device'] = 'Tablet';
        } else {
            $metadata['device'] = 'Desktop';
        }

        return $metadata;
    }

    /**
     * Get location data from IP address
     */
    private function getLocationData(string $ip): array
    {
        // Skip local IPs
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return [];
        }

        // Use cache to avoid repeated API calls
        $cacheKey = "visit_location_{$ip}";
        
        return cache()->remember($cacheKey, now()->addDays(30), function () use ($ip) {
            try {
                // Using ip-api.com (free, no API key required)
                $response = \Illuminate\Support\Facades\Http::timeout(2)
                    ->get("http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,regionName,city,isp,query");

                if ($response->successful() && $response->json('status') === 'success') {
                    return [
                        'country' => $response->json('country'),
                        'country_code' => $response->json('countryCode'),
                        'region' => $response->json('regionName'),
                        'city' => $response->json('city'),
                        'isp' => $response->json('isp'),
                    ];
                }
            } catch (\Exception $e) {
                // Silent fail
            }

            return [];
        });
    }
}

