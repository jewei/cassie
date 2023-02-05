<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'thumbnail',
        'properties',
    ];

    protected $casts = [
        'properties' => AsArrayObject::class,
    ];

    protected $appends = [
        'thumbnail_url',
    ];

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->diffForHumans(),
        );
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->thumbnail ? Storage::url($this->thumbnail) : null,
        );
    }
}
