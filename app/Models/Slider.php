<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use App\Observers\SliderObserver;
use App\Traits\BackPackTranslationTrait;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * @property string|null $image
 * @mixin IdeHelperSlider
 */
class Slider extends MysqlModel
{
    use CrudTrait;
    use QueryCacheable;
    use BackPackTranslationTrait;

    public const MODEL_NAME = 'slider';

    protected $table = self::MODEL_NAME;

    public int $cacheFor = 500;

    public $timestamps = false;

    public string $cachePrefix = self::MODEL_NAME;

    public array $cacheTags = [self::MODEL_NAME];

    private string $lang;

    public const IMG_PATH = 'imgs/sliders/';

    public array $translatable = [
        'name',
        'link',
        'image',
    ];

    protected $fillable = [
        'name',
        'description',
        'image',
        'is_active',
        'order',
        'link',
        'group_id',
        'colors',
    ];

    public function __construct(array $attributes = [])
    {
        $this->lang = App::getLocale();

        parent::__construct($attributes);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', '=', 1);
    }

    public function scopeLang(Builder $query, $lang): Builder
    {
        return $query->where('lang', '=', $lang);
    }

    public function scopeGroup(Builder $query, $groupId): Builder
    {
        return $query->where('group_id', '=', $groupId);
    }

    public function setImageAttribute($value): void
    {
        $this->attributes['image'] = ImageHelper::addImgToCDN($this->image, self::getImagePath($this->lang), $value);
    }

    public static function getImagePath(string $lang): string
    {
        return self::IMG_PATH . $lang . '/';
    }

    public static function boot(): void
    {
        parent::boot();

        self::observe(SliderObserver::class);
    }
}
