<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SupabaseStorageService extends Controller
{
    private $supabaseUrl;
    private $supabaseApiKey;
    private $bucketName;

    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_URL');
        $this->supabaseApiKey = env('SUPABASE_API_KEY');
        $this->bucketName = env('SUPABASE_BUCKET', 'products/$supplier_id'); // Set a default bucket name
    }

    public function uploadFile($filePath, $filePathInSupabase)
    {
        Log::info("Uploading file to Supabase at path: {$filePathInSupabase}");

        try {
            $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->supabaseApiKey,
                    'apikey' => $this->supabaseApiKey,
                ])
                ->attach('file', file_get_contents($filePath), basename($filePathInSupabase))
                ->post("{$this->supabaseUrl}/storage/v1/object/{$filePathInSupabase}");

            if ($response->successful()) {
                return ['url' => "{$this->supabaseUrl}/storage/v1/object/public/{$filePathInSupabase}"];
            } else {
                throw new \Exception('Error uploading file: ' . $response->body());
            }
        } catch (Exception $e) {
            Log::error("Upload error: " . $e->getMessage());
            throw new \Exception('Upload failed: ' . $e->getMessage());
        }
    }

}
