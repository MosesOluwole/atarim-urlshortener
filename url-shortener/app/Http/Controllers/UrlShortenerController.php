<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class UrlShortenerController extends Controller
{
    public function encode(Request $request)
    {
        // Validate the input URL
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid URL'], 400);
        }

        $originalUrl = $request->input('url');

        // Check if the URL already exists in the cache
        $shortCode = Cache::get($originalUrl);
        if ($shortCode) {
            $shortUrl = url("/$shortCode");
            return response()->json(['short_url' => $shortUrl]);
        }

        // Generate a unique short code
        $shortCode = $this->generateUniqueShortCode();

        // Store the mapping in the cache
        Cache::put($shortCode, $originalUrl); // short_code => original_url
        Cache::put($originalUrl, $shortCode); // original_url => short_code

        $shortUrl = url("/$shortCode");

        return response()->json(['short_url' => $shortUrl]);
    }

    public function decode(Request $request)
    {
        // Validate the input short URL
        $validator = Validator::make($request->all(), [
            'short_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid short URL'], 400);
        }

        $shortUrl = $request->input('short_url');

        // Extract the short code from the URL
        $path = parse_url($shortUrl, PHP_URL_PATH);
        $shortCode = basename($path);

        // Find the original URL in the cache
        $originalUrl = Cache::get($shortCode);
        if (!$originalUrl) {
            return response()->json(['error' => 'Short URL not found'], 404);
        }

        return response()->json(['original_url' => $originalUrl]);
    }

    private function generateUniqueShortCode()
    {
        $shortCode = Str::random(6);

        // Ensure the short code is unique
        while (Cache::has($shortCode)) {
            $shortCode = Str::random(6);
        }

        return $shortCode;
    }
}