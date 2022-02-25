<?php

namespace App\TagHandlers;

use App\Models\Bet;
use App\Models\Casino\Game;
use App\Models\Casino\GameTransaction;
use App\Models\Payment\Payment;
use App\Models\Tag;
use App\Models\TagUser;
use App\Models\User;
use App\Services\MoneyService;
use App\TagHandlers\Contracts\TagHandler;
use Illuminate\Database\Eloquent\Relations\Pivot;

abstract class AbstractHandler implements TagHandler
{
    /**
     *  Example use:
     *  $tag = $user->tags->first();
     *  $userTag = $tag->object;
     *  $userTag->handle();
     */
    protected array $config = [];

    protected Tag $tag;
    protected User $user;
    protected MoneyService $moneyService;

    public TagUser $userTag;

    public function __construct(Pivot $pivot)
    {
        $this->userTag = TagUser::find($pivot->id);
        $this->tag = Tag::find($pivot->tag_id);
        $this->user = $this->userTag->user;
        $this->moneyService = new MoneyService($this->user);

        // init configuration
        if (empty($this->tag->config)) {
            static::install();
        }
        $this->config = $this->tag->config;
    }

    abstract protected function install(): void;

    abstract public function handle(): void;

    abstract public function check(Bet|GameTransaction|Game $bid): ?int;

    abstract public function charge(): void;

    /**
     * Возвращает false, если обработка тега невозможна
     */
    protected function chargeAvailable(): bool
    {
        if ($this->userTag->progress < 1 || $this->userTag->completed_at) {
            return false;
        }
        return true;
    }

    /**
     * Возвращает false, если обработка тега невозможна
     */
    protected function handleAvailable(): bool
    {
        if ($this->userTag->progress >= 1 || $this->userTag->completed_at) {
            return false;
        }
        return true;
    }

    protected function getFirstPayment(): ?Payment
    {
        return $this->user->payments()
            ->where('direction', Payment::DIRECTION_IN)
            ->where('status', Payment::STATUS_SUCCESS)
            ->get()->first();
    }

}
