<?php

namespace App\Classes;

class General
{
    public static function compressImage($imageFile, $directory = 'public', $maxWidth = 1200, $maxHeight = 1200, $quality = 75): ?string
    {
        $imagePath = $imageFile->getRealPath();
        $imageInfo = getimagesize($imagePath);
        
        if (!$imageInfo) {
            return null;
        }
        
        list($width, $height, $imageType) = $imageInfo;
        
        // Only resize if image is larger than max dimensions
        $needsResize = $width > $maxWidth || $height > $maxHeight;
        
        if ($needsResize) {
            // Calculate new dimensions maintaining aspect ratio
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }
        
        // Create image resource based on type
        $source = null;
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $source = @imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_PNG:
                $source = @imagecreatefrompng($imagePath);
                break;
            case IMAGETYPE_GIF:
                $source = @imagecreatefromgif($imagePath);
                break;
            case IMAGETYPE_WEBP:
                if (function_exists('imagecreatefromwebp')) {
                    $source = @imagecreatefromwebp($imagePath);
                }
                break;
        }
        
        if (!$source) {
            return null;
        }
        
        // Create optimized image
        $optimized = imagecreatetruecolor($newWidth, $newHeight);
        
        // Enable better quality resampling
        imagealphablending($optimized, false);
        imagesavealpha($optimized, true);
        
        // Preserve transparency for PNG and GIF
        if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
            $transparent = imagecolorallocatealpha($optimized, 255, 255, 255, 127);
            imagefilledrectangle($optimized, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Use better resampling algorithm
        if ($needsResize) {
            imagecopyresampled($optimized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        } else {
            imagecopy($optimized, $source, 0, 0, 0, 0, $width, $height);
        }
        
        // Save optimized image as JPEG (best compression)
        $filename = uniqid() . '.jpg';
        $path = $directory . '/' . $filename;
        $fullPath = storage_path('app/' . $path);
        
        // Create directory if it doesn't exist
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Try progressive JPEG for better compression
        imageinterlace($optimized, 1);
        
        // Save with optimized quality
        $saved = imagejpeg($optimized, $fullPath, $quality);
        
        // Clean up
        imagedestroy($source);
        imagedestroy($optimized);
        
        if (!$saved) {
            return null;
        }
        
        // Try to further optimize file size by re-reading and re-saving if still too large
        $fileSize = filesize($fullPath);
        $maxFileSize = 500 * 1024; // 500KB target
        
        if ($fileSize > $maxFileSize && $quality > 60) {
            // Try with lower quality
            $lowerQuality = max(60, $quality - 10);
            $source2 = @imagecreatefromjpeg($fullPath);
            if ($source2) {
                imageinterlace($source2, 1);
                imagejpeg($source2, $fullPath, $lowerQuality);
                imagedestroy($source2);
            }
        }
        
        return $path;
    }
}