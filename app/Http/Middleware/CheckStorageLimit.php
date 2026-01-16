<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStorageLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Get the file size from the request if a file is being uploaded
        if ($request->hasFile('song')) {
            $fileSize = $request->file('song')->getSize();

            if (!$user->hasStorageSpace($fileSize)) {
                return back()
                    ->with('error', 'Storage limit exceeded. Please delete some songs first.')
                    ->withInput();
            }
        }

        return $next($request);
    }
}
