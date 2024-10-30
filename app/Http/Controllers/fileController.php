<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Services\SupabaseStorageService;
use App\Http\Controllers\Controller;

class fileController extends Controller
{
    private $supabaseService;

    public function __construct(SupabaseStorageService $supabaseService)
    {
        $this->supabaseService = $supabaseService;
    }

    public function uploadDummyFile()
    {
        
        // Use the correct file path
        $filePath = 'C:\\Users\\HP ELITEBOOK 745 G6\\Pictures\\Screenshots\\Screenshot (1731).png';
        $fileName = 'Screenshot (1731).png'; // Use the file name directly

        // Check if the file exists
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found at ' . $filePath], 404);
        }

        try {
            // Call the upload method from SupabaseStorageService
            $result = $this->supabaseService->uploadFile($filePath, $fileName);
            return response()->json(['message' => 'File uploaded successfully', 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function upload(Request $request)
    {
        // Validate the file input
        $request->validate([
            'file' => 'required|file',
        ]);

        // Retrieve file data
        $file = $request->file('file');

        try {
            // Upload the file using the Supabase service
            $result = $this->supabaseService->uploadFile($file->getRealPath(), $file->getClientOriginalName());
            return response()->json(['message' => 'File uploaded successfully', 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
