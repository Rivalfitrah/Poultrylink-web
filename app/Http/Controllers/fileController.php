<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            'product_id' => 'required|integer',
        ]);

        // Retrieve file data
        $file = $request->file('file');
        $productId = $request->input('product_id');
        
        try {
            // Upload the file using the Supabase service with product ID as folder name
            $filePathInSupabase = "{$productId}";
            $result = $this->supabaseService->uploadFile($file->getRealPath(), $filePathInSupabase);
            return response()->json(['message' => 'File uploaded successfully', 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateImage(Request $request)
    {
        // Validate the file input
        $request->validate([
            'file' => 'required|file',
            'product_id' => 'required|integer',
            'product_name' => 'required|string',
        ]);

        // Retrieve file data
        $file = $request->file('file');
        $productId = $request->input('product_id');
        $productName = $request->input('product_name');

        try {
            // Replace the file using the Supabase service with product ID as folder name
            $filePathInSupabase = "{$productId}/{$productName}";
            $result = $this->supabaseService->replaceFile($file->getRealPath(), $filePathInSupabase);
            return response()->json(['message' => 'File replaced successfully', 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteImage(Request $request)
    {
        // Validate the input
        $request->validate([
            'product_id' => 'required|integer',
            'product_name' => 'required|string',
        ]);

        // Retrieve input data
        $productId = $request->input('product_id');
        $productName = $request->input('product_name');

        try {
            // Delete the file using the Supabase service with product ID and product name as folder and file name
            $filePathInSupabase = "{$productId}/{$productName}";
            $result = $this->supabaseService->deleteFile($filePathInSupabase);
            if ($result) {
                return response()->json(['message' => 'File deleted successfully']);
            } else {
                return response()->json(['error' => 'File not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function uploadMultipleFiles(Request $request)
    {
        // Validate the file input
        $request->validate([
            'files.*' => 'required|file',
            'product_id' => 'required|integer',
        ]);

        // Retrieve file data
        $files = $request->file('files');
        $productId = $request->input('product_id');

        // Use the new method in SupabaseStorageService to upload multiple files
        $results = $this->supabaseService->uploadMultipleFiles($files, $productId);

        return response()->json($results);
    }
}
