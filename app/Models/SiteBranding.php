<?php

namespace App\Models;

use Database\Factories\SiteBrandingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SiteBranding extends Model implements HasMedia
{
    /** @use HasFactory<SiteBrandingFactory> */
    use HasFactory;

    use InteractsWithMedia;

    protected $fillable = [];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('logo_dark')->singleFile();
        $this->addMediaCollection('favicon')->singleFile();
        $this->addMediaCollection('footer_logo')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->format('png')
            ->performOnCollections('logo', 'logo_dark', 'footer_logo')
            ->nonQueued();

        $this->addMediaConversion('og')
            ->fit(Fit::Contain, 512, 512)
            ->format('png')
            ->performOnCollections('logo')
            ->nonQueued();

        $this->addMediaConversion('favicon-32')
            ->width(32)
            ->height(32)
            ->format('png')
            ->performOnCollections('favicon', 'logo')
            ->nonQueued();

        $this->addMediaConversion('favicon-180')
            ->width(180)
            ->height(180)
            ->format('png')
            ->performOnCollections('favicon', 'logo')
            ->nonQueued();
    }

    public static function instance(): self
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('site_brandings')) {
            return new self;
        }

        return static::query()->firstOrCreate([]);
    }
}
