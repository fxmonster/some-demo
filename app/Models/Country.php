<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use App\Observers\CountryObserver;
use App\Traits\BackPackTranslationTrait;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * @mixin IdeHelperCountry
 */
class Country extends MysqlModel
{
    use CrudTrait;
    use QueryCacheable;
    use BackPackTranslationTrait;

    public const MODEL_NAME = 'country';

    protected $table = self::MODEL_NAME;
    public $timestamps = false;

    public int $cacheFor = 600;
    public string $cachePrefix = self::MODEL_NAME;
    public array $cacheTags = [self::MODEL_NAME];

    public const IMG_PATH = 'imgs/countries/';

    protected $fillable = [
        'name',
        'enabled',
        'image',
    ];

    public function setImageAttribute($value): void
    {
        $this->attributes['image'] = ImageHelper::addImgToCDN($this->image, self::IMG_PATH, $value);
    }

    public array $translatable = ['name'];

    public function scopeEnabled(Builder $query, int $enabled): Builder
    {
        return $query->where('enabled', '=', $enabled);
    }

    public static function boot(): void
    {
        parent::boot();

        self::observe(CountryObserver::class);
    }
}
