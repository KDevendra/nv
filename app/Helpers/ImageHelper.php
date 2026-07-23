<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
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
        $manager = new ImageManager(new Driver());
        $image   = $manager->read($file->getRealPath());

        if ($maxWidth > 0 && $image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        $slug     = Str::slug($seoName);
        $base     = $suffix ? "{$slug}-{$id}-{$suffix}" : "{$slug}-{$id}";
        $filename = "{$base}.webp";
        $dir      = public_path("uploads/{$folder}");

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $image->toWebp($quality)->save("{$dir}/{$filename}");

        return "uploads/{$folder}/{$filename}";
    }
}
