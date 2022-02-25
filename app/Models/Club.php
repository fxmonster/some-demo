<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use App\Observers\ClubObserver;
use App\Traits\BackPackTranslationTrait;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * @mixin IdeHelperClub
 */
class Club extends MysqlModel
{
    use CrudTrait;
    use QueryCacheable;
    use BackPackTranslationTrait;

    public const MODEL_NAME = 'club';

    protected $table = self::MODEL_NAME;
    public $timestamps = false;

    public int $cacheFor = 600;
    public string $cachePrefix = self::MODEL_NAME;
    public array $cacheTags = [self::MODEL_NAME];

    public const IMG_PATH = 'imgs/clubs/';

    protected $fillable = [
        'name',
        'image',
    ];

    public array $translatable = ['name'];

    public function setImageAttribute($value): void
    {
        $this->attributes['image'] = ImageHelper::addImgToCDN($this->image, self::IMG_PATH, $value);
    }

    public static function boot(): void
    {
        parent::boot();

        self::observe(ClubObserver::class);
    }
}
