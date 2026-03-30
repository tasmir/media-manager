<?php

namespace Tasmir\MediaManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaFile extends Model
{
    use SoftDeletes;

    protected $table = 'media_files';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getImageSlugAttribute()
    {
        return route('file.show', [$this->slug]);
    }

    public function imageShow($value = null)
    {
        $url = route('file.show', [$this->slug]) . ($value ?: '');
        return "<img src='{$url}' alt='{$this->alt}' loading='lazy' title='{$this->caption}' />";
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? 'id';
        if ($field === 'slug') {
            return $this->whereRaw('LOWER(slug) = ?', [strtolower($value)])->firstOrFail();
        }
        return parent::resolveRouteBinding($value, $field);
    }
}
