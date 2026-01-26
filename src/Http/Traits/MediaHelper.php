<?php

namespace Tasmir\MediaManager\Http\Traits;

use Tasmir\MediaManager\Models\MediaFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Encoders\WebpEncoder;

trait MediaHelper
{
    public function uploadMediaFile($data)
    {
        $path = null;
        $media_id = null;
        $quality = null;
        $prefix = null;
        $alt_text = null;
        $caption = null;
        $isYearMonth = true;
        $yearMonth = date('Y/m');
        $addLogo = false;
        $resize = false;
        $width = null;
        $height = null;

        if (isset($data['file'])) $file = $data['file'];
        if (isset($data['path'])) $path = $data['path'];
        if (isset($data['prefix'])) $prefix = $data['prefix'];
        if (isset($data['media_id'])) $media_id = $data['media_id'];
        if (isset($data['quality'])) $quality = $data['quality'];
        
        if (isset($data['alt_text']) && !empty($data['alt_text'])) $alt_text = $data['alt_text'];
        if (isset($data['caption']) && !empty($data['caption'])) $caption = $data['caption'];
        if (isset($data['isYearMonth'])) $isYearMonth = $data['isYearMonth'];
        if (isset($data['addLogo'])) $addLogo = $data['addLogo'];
        if (isset($data['resize'])) $resize = $data['resize'];
        if (isset($data['width'])) $width = $data['width'];
        if (isset($data['height'])) $height = $data['height'];

        if ($file->isValid()) {
            $path = rtrim($path) ?: '/files';
            $path = $isYearMonth ? "uploads/$yearMonth/" . ltrim($path, '/') : "uploads/" . ltrim($path, '/');

            if (!File::exists(public_path($path))) {
                File::makeDirectory(public_path($path), 0755, true);
            }

            $extension = $file->getClientOriginalExtension();
            $isImage = $this->isImage($extension);
            $enableWebp = config('media-manager.enable_webp_convert', true);
            
            if ($isImage && $enableWebp) {
                $file_name = time() . '-' . uniqid() . '.webp';
                $extension = 'webp';
            } else {
                $file_name = time() . '-' . uniqid() . '.' . $extension;
            }

            $destinationPath = public_path($path);
            $img = ImageManager::imagick()->read($file->getRealPath());

            $nameWithoutExtension = Str::beforeLast($file->getClientOriginalName(), '.');
            $slug = $prefix ? "$prefix/" : "";
            if ($isYearMonth) $slug .= "$yearMonth/";
            $slug .= Str::slug($nameWithoutExtension);

            if ($isImage) {
                if ($resize) $img->resize($width, $height);
                if ($addLogo) {
                    $logoPath = public_path('img/icon-50.webp');
                    if (File::exists($logoPath)) {
                        $logo = ImageManager::imagick()->read($logoPath);
                        $img->place($logo, 'top-right', 10, 10);
                    }
                }
                $img->encode(new WebpEncoder($quality ?: config('media-manager.image_quality', 80)))->save($destinationPath . '/' . $file_name);
            } else {
                $file->move($destinationPath, $file_name);
            }

            $dimensions = null;
            if ($isImage && @getimagesize($destinationPath . '/' . $file_name)) {
                [$w, $h] = getimagesize($destinationPath . '/' . $file_name);
                $dimensions = "$w x $h";
            }

            $size = $this->getFileSize($destinationPath . '/' . $file_name);

            $slug = $this->generateUniqueSlug($slug, $media_id);
            $mediaData = [
                'name' => $file_name,
                'slug' => $slug,
                'path' => $path . '/' . $file_name,
                'extension' => $extension,
                'alt' => $alt_text ?? $nameWithoutExtension,
                "caption" => $caption ?? $nameWithoutExtension,
                "dimensions" => $dimensions,
                "size" => $size,
            ];

            if ($media_id) {
                $media = MediaFile::find($media_id);
                if ($media && File::exists(public_path($media->path))) {
                    File::delete(public_path($media->path));
                }
                $media->update($mediaData);
            } else {
                $media = MediaFile::create($mediaData);
            }

            return $media?->id;
        }
        return null;
    }

    public function minimumUpSize()
    {
        return config('media-manager.max_file_size', 5) * 1024 * 1024;
    }

    public function getFileSize($filePath)
    {
        if (!File::exists($filePath)) return "0 KB";
        $size = File::size($filePath);
        if ($size < 1024) return $size . ' bytes';
        if ($size < 1048576) return number_format($size / 1024, 2) . ' KB';
        return number_format($size / 1048576, 2) . ' MB';
    }

    public function bytesToHuman($bytes)
    {
        if ($bytes < 1024) return $bytes . ' bytes';
        if ($bytes < 1048576) return number_format($bytes / 1024, 2) . ' KB';
        return number_format($bytes / 1048576, 2) . ' MB';
    }

    private function isImage($extension)
    {
        return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'tiff']);
    }

    public function deleteMediaFileByPath($path)
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }

    /**
     * Generate a unique slug for the media file.
     *
     * @param string $slug
     * @param int|null $media_id
     * @return string
     */
    private function generateUniqueSlug($slug, $media_id = null)
    {
        $originalSlug = $slug;
        $count = 1;

        while (MediaFile::where('slug', $slug)
            ->when($media_id, function ($query, $media_id) {
                return $query->where('id', '!=', $media_id);
            })
            ->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
