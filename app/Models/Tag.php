<?php

namespace App\Models;

use App\Models\Scopes\OrderedScope;
use App\Models\Traits\ScopeEnabled;
use App\TagHandlers\Contracts\TagHandler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read TagHandler $object
 * @mixin IdeHelperTag
 */
class Tag extends MysqlModel
{
    use ScopeEnabled;

    public $cachePrefix = 'tags';

    protected $table = 'tags';

    protected $fillable = [
        'name',
        'description',
        'class',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(TagUser::class);
    }

    public function scopeByNotCompleted(Builder $query): Builder
    {
        return $query->where('completed_at', null);
    }

    public function scopeShowUI(Builder $query): Builder
    {
        return $query->where('show_ui', 1);
    }

    public function bonusAccountType(): BelongsTo
    {
        return $this->belongsTo(BonusAccountType::class, 'bonus_account_type_id');
    }

    public function scopeByTypes(Builder $query, array $accountTypes): Builder
    {
        return $query->whereIn('bonus_account_type_id', $accountTypes);
    }

    /*
     * Магия !!! Возвращает экземпляр класса Тега с бизнес логикой обработки по имени класса в БД
     */
    public function getObjectAttribute()
    {
        return new $this->class($this->pivot);
    }

    protected static function boot():void
    {
        parent::boot();
        static::addGlobalScope(new OrderedScope());
    }

}
