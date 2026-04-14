<?php
class ImageHelper
{

    /**
     * Resize and save image to 600x600
     */
    public static function resizeAndSave($sourceFile, $destinationPath, $maxWidth = 600, $maxHeight = 600): bool
    {
        list($width, $height, $type) = getimagesize($sourceFile);

        // Calculate ratio
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);

        // Create image resource based on type
        switch ($type) {
            case IMAGETYPE_JPEG:
                $srcImage = imagecreatefromjpeg($sourceFile);
                break;
            case IMAGETYPE_PNG:
                $srcImage = imagecreatefrompng($sourceFile);
                break;
            case IMAGETYPE_WEBP:
                $srcImage = imagecreatefromwebp($sourceFile);
                break;
            default:
                return false;
        }

        // Create new blank image
        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG
        if ($type == IMAGETYPE_PNG) {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
            $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
            imagefilledrectangle($dstImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Resize
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Save based on original type
        $result = false;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $result = imagejpeg($dstImage, $destinationPath, 90);
                break;
            case IMAGETYPE_PNG:
                $result = imagepng($dstImage, $destinationPath, 8);
                break;
            case IMAGETYPE_WEBP:
                $result = imagewebp($dstImage, $destinationPath, 90);
                break;
        }

        // Free memory
        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return $result;
    }

    /**
     * Generate unique filename
     */
    public static function generateFilename($originalName, $productId): string
    {
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $timestamp = time();
        return "product_{$productId}_{$timestamp}." . strtolower($ext);
    }

    /**
     * Delete image file
     */
    public static function deleteImage($filename): bool
    {
        $path = __DIR__ . '/../public/productImages/' . $filename;
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
}
