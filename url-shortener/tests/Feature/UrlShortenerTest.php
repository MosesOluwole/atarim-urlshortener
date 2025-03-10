<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class UrlShortenerTest extends TestCase
{
    protected function tearDown(): void
    {
        // Clear the cache after each test
        Cache::flush();
        parent::tearDown();
    }

    /**
     * Test encoding a URL.
     *
     * @return void
     */
    public function test_encode_url()
    {
        // Define the input URL
        $originalUrl = 'https://www.example.com';

        // Send a POST request to the /encode endpoint
        $response = $this->postJson('/api/encode', [
            'url' => $originalUrl,
        ]);

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the response contains a short_url
        $response->assertJsonStructure([
            'short_url',
        ]);

        // Extract the short URL from the response
        $shortUrl = $response->json('short_url');

        // Assert that the short URL is valid
        $this->assertStringStartsWith('http://', $shortUrl);

        // Assert that the mapping is stored in the cache
        $shortCode = basename(parse_url($shortUrl, PHP_URL_PATH));
        $this->assertEquals($originalUrl, Cache::get($shortCode));
    }

    /**
     * Test decoding a short URL.
     *
     * @return void
     */
    public function test_decode_url()
    {
        // Define the original URL
        $originalUrl = 'https://www.example.com';

        // Encode the URL to get a short URL
        $encodeResponse = $this->postJson('/api/encode', [
            'url' => $originalUrl,
        ]);

        // Extract the short URL from the encode response
        $shortUrl = $encodeResponse->json('short_url');

        // Send a POST request to the /decode endpoint
        $decodeResponse = $this->postJson('/api/decode', [
            'short_url' => $shortUrl,
        ]);

        // Assert that the response status is 200 (OK)
        $decodeResponse->assertStatus(200);

        // Assert that the response contains the original URL
        $decodeResponse->assertJson([
            'original_url' => $originalUrl,
        ]);
    }

    /**
     * Test decoding an invalid short URL.
     *
     * @return void
     */
    public function test_decode_invalid_url()
    {
        // Define an invalid short URL
        $invalidShortUrl = 'http://127.0.0.1:8000/invalid';

        // Send a POST request to the /decode endpoint
        $response = $this->postJson('/api/decode', [
            'short_url' => $invalidShortUrl,
        ]);

        // Assert that the response status is 404 (Not Found)
        $response->assertStatus(404);

        // Assert that the response contains an error message
        $response->assertJson([
            'error' => 'Short URL not found',
        ]);
    }

    /**
     * Test encoding an invalid URL.
     *
     * @return void
     */
    public function test_encode_invalid_url()
    {
        // Define an invalid URL
        $invalidUrl = 'not-a-valid-url';

        // Send a POST request to the /encode endpoint
        $response = $this->postJson('/api/encode', [
            'url' => $invalidUrl,
        ]);

        // Assert that the response status is 400 (Bad Request)
        $response->assertStatus(400);

        // Assert that the response contains an error message
        $response->assertJson([
            'error' => 'Invalid URL',
        ]);
    }

}