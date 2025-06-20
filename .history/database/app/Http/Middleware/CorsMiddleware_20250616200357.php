<!--I have had to edit due to CORS issues between signup forms and API endpoint to database-->
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; // Correct import for Response

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define your allowed origins
        $allowedOrigins = [
            'http://localhost:8080',       // Your user interface app
            'http://localhost:8080/admin', // Your admin interface app
            // Add any other specific development origins here
        ];

        // Get the origin from the request header
        $origin = $request->header('Origin');
        
        // Determine the Access-Control-Allow-Origin header value
        // For development, we allow specific origins or fallback to '*' if none match (LESS SECURE)
        $allowOriginHeader = in_array($origin, $allowedOrigins) ? $origin : '*'; 
        // In PRODUCTION, you should NOT use '*' and ensure $origin is from $allowedOrigins

        // Define the common CORS headers
        $headers = [
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS', // Include all methods your API uses
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With', // Common headers
            'Access-Control-Allow-Credentials' => 'true', // Set to 'true' if your frontend sends cookies/auth headers
        ];

        // Handle preflight OPTIONS requests
        if ($request->isMethod('OPTIONS')) {
            $response = new Response('', 204); // 204 No Content for preflight success
            $response->header('Access-Control-Allow-Origin', $allowOriginHeader);
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }
            return $response;
        }

        // Handle actual requests
        $response = $next($request);

        // Add CORS headers to the actual response
        $response->header('Access-Control-Allow-Origin', $allowOriginHeader);
        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }
        
        return $response;
    }
}
