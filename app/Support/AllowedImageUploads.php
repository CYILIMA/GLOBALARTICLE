<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

class AllowedImageUploads
{
    private const STANDARD_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
        'webp',
        'gif',
        'svg',
        'bmp',
        'tif',
        'tiff',
        'heif',
        'heic',
        'avif',
    ];

    private const RAW_EXTENSIONS = [
        'raw',
        'dng',
        'cr2',
        'cr3',
        'nef',
        'nrw',
        'arw',
        'sr2',
        'orf',
        'rw2',
        'raf',
        'pef',
    ];

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'image/svg+xml',
        'image/bmp',
        'image/x-ms-bmp',
        'image/tiff',
        'image/heif',
        'image/heic',
        'image/avif',
        'image/x-adobe-dng',
        'image/x-canon-cr2',
        'image/x-canon-cr3',
        'image/x-nikon-nef',
        'image/x-sony-arw',
        'image/x-olympus-orf',
        'image/x-panasonic-rw2',
        'image/x-fuji-raf',
        'image/x-pentax-pef',
    ];

    public static function extensions(): array
    {
        return [
            ...self::STANDARD_EXTENSIONS,
            ...self::RAW_EXTENSIONS,
        ];
    }

    public static function acceptAttribute(): string
    {
        return implode(',', array_map(
            fn (string $extension) => ".{$extension}",
            self::extensions()
        ));
    }

    public static function rules(bool $required = false, int $maxKilobytes = 20480): array
    {
        return [
            $required ? 'required' : 'nullable',
            'file',
            "max:{$maxKilobytes}",
            self::validationRule(),
        ];
    }

    private static function validationRule(): callable
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            if (! $value instanceof UploadedFile) {
                $fail("The {$attribute} must be an image file.");
                return;
            }

            $extension = strtolower($value->getClientOriginalExtension());
            $mimeType = strtolower((string) $value->getMimeType());

            if (! in_array($extension, self::extensions(), true)) {
                $fail("The {$attribute} must be one of these image formats: " . self::readableFormats() . '.');
                return;
            }

            if (str_starts_with($mimeType, 'image/') || in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
                return;
            }

            if (in_array($extension, self::RAW_EXTENSIONS, true) && $mimeType === 'application/octet-stream') {
                return;
            }

            $fail("The {$attribute} must be a valid image file.");
        };
    }

    private static function readableFormats(): string
    {
        return 'JPEG/JPG, PNG, WEBP, GIF, SVG, BMP, TIFF/TIF, RAW, HEIF/HEIC, AVIF';
    }
}
