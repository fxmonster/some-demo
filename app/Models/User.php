<?php

namespace App\Models;

use App\Events\User\UserUpdated;
use App\Helpers\StringHelper;
use App\Models\Casino\Game;
use App\Models\Casino\GameFavorite;
use App\Models\Casino\GameTransaction;
use App\Models\Payment\Payment;
use App\Models\Payment\PaymentToken;
use App\Observers\UserObserver;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    use Notifiable;
    use CrudTrait;
    use HasRoles;
    use HybridRelations;

    use LogsActivity;


    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_PROCESSING = 2;
    public const STATUS_VERIFIED = 3;
    public const STATUS_BLOCKED = 99;

    public const ACTIVE_STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_PROCESSING,
        self::STATUS_VERIFIED,
    ];

    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 0;

    public $connection = 'mysql';

    protected $table = 'user';

    protected $dates = [
        "birthday",
    ];

    protected $dispatchesEvents = [
        // Перенес в Adapter
//        'created' => UserCreated::class,
        'updated' => UserUpdated::class,
    ];


    public function isActive(): bool
    {
        return in_array(
            $this->status,
            self::ACTIVE_STATUSES,
            true
        );
    }

    public function isVerified(): bool
    {
        return self::STATUS_VERIFIED === (int)$this->status;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function activeCurrencyAccount(): BelongsTo
    {
        return $this->belongsTo(CurrencyAccount::class, 'currency_account_id');
    }

    public function currencyAccounts(): HasMany
    {
        return $this->hasMany(CurrencyAccount::class, 'user_id');
    }

    public function bonusAccounts(): HasMany
    {
        return $this->hasMany(BonusAccount::class, 'user_id');
    }

    /**
     * Возвращает бонусный счет пользователя по типу
     */
    public function bonusAccount(int $bonusAccountType): BonusAccount
    {
        return $this->bonusAccounts()->byType($bonusAccountType)->first();
    }

    public function userSettings(): HasOne
    {
        return $this->hasOne(UserSettings::class);
    }

    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class);
    }

    public function userPersonal(): HasOne
    {
        return $this->hasOne(UserPersonal::class, 'id');
    }

    public function games(): BelongsToMany
    {
        return $this
            ->belongsToMany(Game::class, 'game_favorites')
            ->using(GameFavorite::class)
            ->cacheTags([Game::MODEL_NAME . $this->id]);
    }

    public function events(): BelongsToMany
    {
        return $this
            ->belongsToMany(Event::class, 'user_event')
            ->using(UserEvent::class)
            ->cacheTags([Event::CACHE_TAG . $this->id]);
    }

    public function devices(): BelongsToMany
    {
        return $this
            ->belongsToMany(Device::class, 'user_devices')
            ->using(UserDevice::class)
            ->cacheTags([Device::MODEL_NAME . $this->id]);
    }

    public function gameTransactions(): HasMany
    {
        return $this->hasMany(GameTransaction::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentTokens(): HasMany
    {
        return $this->hasMany(PaymentToken::class);
    }

    public function betItems(): HasMany
    {
        return $this->hasMany(BetItem::class);
    }

    public function sports(): HasMany
    {
        $relation = $this->hasMany(Sport::class);

        $relation->setQuery(
            Sport::with('betItems')->whereHas(
                'betItems',
                function (Builder $query) {
                    $query->where('user_id', '=', $this->id);
                }
            )->getQuery()
        );

        return $relation;
    }

    public function betslip(): HasOne
    {
        return $this->hasOne(Betslip::class, Betslip::getPrimaryKey());
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)
            ->withPivot('id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', self::ACTIVE_STATUSES);
    }

    public static function boot(): void
    {
        parent::boot();

        self::observe(UserObserver::class);
    }
}
