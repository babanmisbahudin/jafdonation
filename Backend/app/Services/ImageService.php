<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Process any uploaded image: resize → convert to WebP → save.
     *
     * @param  int  $maxWidth   Max width in pixels (height auto-scaled)
     * @param  int  $quality    WebP quality 0-100
     */
    public function processAndSave(
        UploadedFile $file,
        string $folder,
        int $maxWidth = 1200,
        int $quality  = 82
    ): string {
        $dir = public_path("uploads/{$folder}");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = time() . '_' . Str::random(6) . '.webp';
        $destPath = "{$dir}/{$filename}";

        $src = $this->createImageResource($file);

        // Flatten transparency (PNG/GIF) onto white background
        $mime = $file->getMimeType();
        if (in_array($mime, ['image/png', 'image/gif'])) {
            $flat = imagecreatetruecolor(imagesx($src), imagesy($src));
            imagefill($flat, 0, 0, imagecolorallocate($flat, 255, 255, 255));
            imagecopy($flat, $src, 0, 0, 0, 0, imagesx($src), imagesy($src));
            imagedestroy($src);
            $src = $flat;
        }

        // Resize if too wide
        $origW = imagesx($src);
        $origH = imagesy($src);
        if ($origW > $maxWidth) {
            $newH    = (int) round($origH * $maxWidth / $origW);
            $resized = imagecreatetruecolor($maxWidth, $newH);
            imagecopyresampled($resized, $src, 0, 0, 0, 0, $maxWidth, $newH, $origW, $origH);
            imagedestroy($src);
            $src = $resized;
        }

        imagewebp($src, $destPath, $quality);
        imagedestroy($src);

        return "{$folder}/{$filename}";
    }

    private function createImageResource(UploadedFile $file)
    {
        return match ($file->getMimeType()) {
            'image/jpeg'  => imagecreatefromjpeg($file->getPathname()),
            'image/png'   => imagecreatefrompng($file->getPathname()),
            'image/gif'   => imagecreatefromgif($file->getPathname()),
            'image/webp'  => imagecreatefromwebp($file->getPathname()),
            'image/bmp'   => imagecreatefrombmp($file->getPathname()),
            default       => throw new \RuntimeException('Format gambar tidak didukung: ' . $file->getMimeType()),
        };
    }
}
