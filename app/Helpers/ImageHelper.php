<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageHelper
{
    /**
     * Convert an uploaded image to optimized WebP with an SEO-friendly filename.
     *
     * Filename pattern: {slug}-{id}-{suffix}.webp
     * Example: luxury-flat-42-main.webp, luxury-flat-42-gallery-1.webp
     *
     * - Scoped by $id so two records with the same name never collide.
     * - Re-uploading the same suffix overwrites the existing file (no orphans).
     *
     * @param  UploadedFile  $file      The uploaded image file.
     * @param  string        $seoName   Human-readable name used to build the slug.
     * @param  int           $id        Record ID scoping the filename (e.g. property ID).
     * @param  string        $suffix    Optional label appended after the ID (e.g. "main", "gallery-1").
     * @param  string        $folder    Sub-folder inside storage/app/public (default: "properties").
     * @param  int           $maxWidth  Downscale if wider than this (default: 1920). 0 = no resize.
     * @param  int           $quality   WebP quality 1–100 (default: 82).
     * @return string                   Storage-relative path, e.g. "properties/luxury-flat-42-main.webp".
     */
    public static function storeWebp(
        UploadedFile $file,
        string $seoName,
        int $id,
        string $suffix = '',
        string $folder = 'properties',
        int $maxWidth = 1920,
        int $quality = 82
    ): string {
        Log::channel('uploads')->info('===== storeWebp START =====', [
            'original_name' => $file->getClientOriginalName(),
            'mime'          => $file->getClientMimeType(),
            'size_kb'       => round($file->getSize() / 1024, 2),
            'real_path'     => $file->getRealPath(),
            'is_valid'      => $file->isValid(),
            'error_code'    => $file->getError(),
            'seoName'       => $seoName,
            'id'            => $id,
            'suffix'        => $suffix,
            'folder'        => $folder,
        ]);

        if (!$file->isValid()) {
            Log::channel('uploads')->error('Uploaded file is INVALID', [
                'error_code'    => $file->getError(),
                'error_message' => $file->getErrorMessage(),
            ]);
            throw new \RuntimeException('Uploaded file is invalid: ' . $file->getErrorMessage());
        }

        try {
            $manager = new ImageManager(new Driver());
            $image   = $manager->read($file->getRealPath());
            Log::channel('uploads')->info('Image read successfully', [
                'width'  => $image->width(),
                'height' => $image->height(),
            ]);
        } catch (\Throwable $e) {
            Log::channel('uploads')->error('Failed to READ image', [
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }

        if ($maxWidth > 0 && $image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
            Log::channel('uploads')->info('Image scaled', ['new_width' => $maxWidth]);
        }

        $slug     = Str::slug($seoName);
        $base     = $suffix ? "{$slug}-{$id}-{$suffix}" : "{$slug}-{$id}";
        $filename = "{$base}.webp";
        $dir      = public_path("uploads/{$folder}");

        Log::channel('uploads')->info('Target directory', [
            'dir'         => $dir,
            'exists'      => is_dir($dir),
            'parent'      => dirname($dir),
            'parent_writable' => is_writable(dirname($dir)),
        ]);

        if (!is_dir($dir)) {
            if (!mkdir($dir, 0775, true) && !is_dir($dir)) {
                Log::channel('uploads')->error('Failed to CREATE directory', ['dir' => $dir]);
                throw new \RuntimeException("Failed to create directory: {$dir}");
            }
            Log::channel('uploads')->info('Directory created', ['dir' => $dir]);
        }

        if (!is_writable($dir)) {
            Log::channel('uploads')->error('Directory is NOT writable', [
                'dir'  => $dir,
                'perms' => substr(sprintf('%o', fileperms($dir)), -4),
            ]);
            throw new \RuntimeException("Directory not writable: {$dir}");
        }

        $fullPath = "{$dir}/{$filename}";

        try {
            $image->toWebp($quality)->save($fullPath);
        } catch (\Throwable $e) {
            Log::channel('uploads')->error('Failed to SAVE webp', [
                'path'    => $fullPath,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }

        $savedOk = file_exists($fullPath);
        Log::channel('uploads')->info('===== storeWebp END =====', [
            'saved_path'   => $fullPath,
            'file_exists'  => $savedOk,
            'file_size_kb' => $savedOk ? round(filesize($fullPath) / 1024, 2) : 0,
            'return_value' => "uploads/{$folder}/{$filename}",
        ]);

        return "uploads/{$folder}/{$filename}";
    }
}
