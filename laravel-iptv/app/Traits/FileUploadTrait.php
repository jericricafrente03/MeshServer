<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

trait FileUploadTrait
{   
    /**
     * Upload a file to the specified directory in the public disk.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     */
    public function uploadFile(UploadedFile $file, string $directory = 'upload'): string
    {
        // Store the file in the specified directory on the 'public' disk
        $filePath = $file->store($directory, 'public');

        return $filePath; // This returns the file path relative to the 'public' directory
    }
    /**
     * Upload a file to the specified directory in the public disk.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return array
     */
    public function uploadFileWithThumbnail(UploadedFile $file, string $directory = 'upload'): array
    {
        // Store the file in the specified directory on the 'public' disk
        $filePath = $file->store($directory, 'public');

        // Create a thumbnail path
        $thumbnailPath = $directory . '/thumbnails/' . $file->hashName();
        $thumbnailDir = storage_path('app/public/' . dirname($thumbnailPath)); // Get the directory part of the path

        // Check if the directory exists, if not, create it
        if (!File::exists($thumbnailDir)) {
            File::makeDirectory($thumbnailDir, 0755, true); // Create directory and subdirectories if needed
        }

        // Initialize the ImageManager with the appropriate configuration
        $image = ImageManager::gd()->read($file->getRealPath());

        // Resize the image (50x50 in this case)
        $image->resize(50, 50);

        // Save the resized image (thumbnail)
        $image->save(storage_path('app/public/' . $thumbnailPath));

        // Return paths of original and thumbnail images
        return [
            'original' => $filePath,
            'thumbnail' => $thumbnailPath,
        ];
    }

    public function keepFilenameUploadFile(UploadedFile $file, string $directory = 'upload'): string
    {
        // Get the original filename
        $originalName = $file->getClientOriginalName(); 
        
        // Store the file with the original name in the specified directory on the 'public' disk
        $filePath = $file->storeAs($directory, $originalName, 'public');

        return $filePath; // Returns the file path relative to the 'public' directory
    }

    /**
     * Delete a file from the specified directory in the public disk.
     *
     * @param string $filePath
     * @return bool
     */
    public function deleteFile(string $filePath): bool
    {
        // Check if the file exists before attempting to delete
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath); // Delete the file
        }

        return false; // Return false if the file does not exist
    }
}