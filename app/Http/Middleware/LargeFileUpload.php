<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LargeFileUpload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if this is a file upload request
        if ($request->hasFile('images') || $request->hasFile('image')) {
            $maxSize = config('large-uploads.max_file_size', 150000); // 150MB in KB
            $maxSizeBytes = $maxSize * 1024; // Convert to bytes

            $files = $request->file('images') ?? [$request->file('image')];
            $files = array_filter($files); // Remove null values

            foreach ($files as $file) {
                if ($file && $file->getSize() > $maxSizeBytes) {
                    $fileSizeMB = round($file->getSize() / (1024 * 1024), 2);
                    $maxSizeMB = round($maxSizeBytes / (1024 * 1024), 2);

                    Log::warning('Large file upload attempt', [
                        'filename' => $file->getClientOriginalName(),
                        'size' => $fileSizeMB . 'MB',
                        'max_size' => $maxSizeMB . 'MB',
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);

                    return back()->withErrors([
                        'images' => "حجم الملف '{$file->getClientOriginalName()}' ({$fileSizeMB}MB) يتجاوز الحد المسموح ({$maxSizeMB}MB)"
                    ]);
                }
            }
        }

        return $next($request);
    }
}
