<?php
namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SupabaseStorageVerif extends Controller
{
    private $supabaseUrl;
    private $supabaseApiKey;
    private $bucketName;

    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_URL');
        $this->supabaseApiKey = env('SUPABASE_API_KEY');
        $this->bucketName = env('SUPABASE_BUCKET2', 'verif'); // Default bucket verif
    }

    public function uploadFile($filePath, $filePathInSupabase)
    {
        Log::info("Uploading file to Supabase at path: {$filePathInSupabase}");
    
        try {
            $fileName = '1.jpg';
            $fileIndex = 1;
    
            while ($this->fileExists("{$this->bucketName}/{$filePathInSupabase}/{$fileName}")) {
                $fileIndex++;
                $fileName = "{$fileIndex}.jpg";
            }
    
            $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->supabaseApiKey,
                    'apikey' => $this->supabaseApiKey,
                ])
                ->attach('file', file_get_contents($filePath), $fileName)
                ->post("{$this->supabaseUrl}/storage/v1/object/{$this->bucketName}/{$filePathInSupabase}/{$fileName}");
    
            if ($response->successful()) {
                return ['url' => "{$this->supabaseUrl}/storage/v1/object/public/{$this->bucketName}/{$filePathInSupabase}/{$fileName}"];
            } else {
                throw new \Exception('Error uploading file: ' . $response->body());
            }
        } catch (Exception $e) {
            Log::error("Upload error: " . $e->getMessage());
            throw new \Exception('Upload failed: ' . $e->getMessage());
        }
    }

    public function uploadMultipleFiles(array $files, $filePathInSupabase)
    {
        $results = [];
        foreach ($files as $file) {
            try {
                $result = $this->uploadFile($file->getRealPath(), $filePathInSupabase);
                $results[] = ['message' => 'File uploaded successfully', 'data' => $result];
            } catch (Exception $e) {
                $results[] = ['error' => $e->getMessage()];
            }
        }
        return $results;
    }

    public function replaceFile($filePath, $filePathInSupabase)
    {
        Log::info("Replacing file in Supabase at path: {$filePathInSupabase}");

        try {
            // Delete the existing file if it exists
            if ($this->fileExists($filePathInSupabase)) {
                $this->deleteFile($filePathInSupabase);
            }

            // Upload the new file
            $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->supabaseApiKey,
                    'apikey' => $this->supabaseApiKey,
                ])
                ->attach('file', file_get_contents($filePath), basename($filePathInSupabase))
                ->post("{$this->supabaseUrl}/storage/v1/object/{$this->bucketName}/{$filePathInSupabase}");

            if ($response->successful()) {
                return ['url' => "{$this->supabaseUrl}/storage/v1/object/public/{$this->bucketName}/{$filePathInSupabase}"];
            } else {
                throw new \Exception('Error uploading file: ' . $response->body());
            }
        } catch (Exception $e) {
            Log::error("Replace error: " . $e->getMessage());
            throw new \Exception('Replace failed: ' . $e->getMessage());
        }
    }

    public function fileExists($filePathInSupabase)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->supabaseApiKey,
            'apikey' => $this->supabaseApiKey,
        ])->get("{$this->supabaseUrl}/storage/v1/object/{$this->bucketName}/{$filePathInSupabase}");

        return $response->successful();
    }

    public function deleteFile($filePathInSupabase)
    {
        try {
            Log::info("Attempting to delete file at path: {$filePathInSupabase}");

            $deleteResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->supabaseApiKey,
                'apikey' => $this->supabaseApiKey,
            ])->delete("{$this->supabaseUrl}/storage/v1/object/{$this->bucketName}/{$filePathInSupabase}");

            if ($deleteResponse->successful()) {
                Log::info("File successfully deleted from Supabase: {$filePathInSupabase}");
                return true;
            } elseif ($deleteResponse->status() === 404) {
                Log::warning("File not found in Supabase storage, cannot delete: {$filePathInSupabase}");
                return false;
            } else {
                throw new \Exception('Error deleting file: ' . $deleteResponse->body());
            }
        } catch (\Exception $e) {
            Log::error("Delete error: " . $e->getMessage());
            throw new \Exception('File deletion failed: ' . $e->getMessage());
        }
    }

    
}
