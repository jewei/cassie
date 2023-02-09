<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property string   $uuid
 * @property string   $file_exists
 * @property string   $path
 * @property string   $disk
 * @property string   $filename
 * @property string   $download_filename
 * @property Template $template
 */
class Certificate extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'email',
        'render_time',
        'rendered_at',
    ];

    /**
     * @var array<string>
     */
    protected $appends = [
        'file_exists',
        'path',
        'disk',
        'filename',
        'download_filename',
    ];

    /**
     * @return BelongsTo<Template, Certificate>
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * @return array<string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * @return Attribute<bool, never>
     */
    protected function fileExists(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::disk($this->disk)->exists($this->filename)
        );
    }

    /**
     * @return Attribute<string, never>
     */
    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::disk($this->disk)->path($this->filename)
        );
    }

    /**
     * @return Attribute<string, never>
     */
    protected function disk(): Attribute
    {
        return Attribute::make(
            get: fn () => 'local'
        );
    }

    /**
     * @return Attribute<string, never>
     */
    protected function filename(): Attribute
    {
        return Attribute::make(
            get: fn () => 'certificates'.DIRECTORY_SEPARATOR.$this->uuid.'.pdf'
        );
    }

    /**
     * @return Attribute<string, never>
     */
    protected function downloadFilename(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->template->properties['title'].' - '.$this->name.'.pdf'
        );
    }
}
